<?php

namespace App\Http\Controllers;

use App\Filters\Event\EventAudienceFilter;
use App\Filters\Event\EventDateFilter;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Employer;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    use ApiHelpers;

    public function index(): EventCollection
    {
        $events = Event::query();
        $response =
            app(Pipeline::class)
            ->send($events)
            ->through([
                EventAudienceFilter::class,
                EventDateFilter::class,
            ])
            ->via('apply')
            ->then(function ($events) {
                return $events->get();
            });

        return new EventCollection($response);
    }

    public function show($id): EventResource
    {
        return new EventResource(Event::find($id));
    }

    public function store(Request $request): EventResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'date' => 'required|date',
            'desc' => 'required|string|max:1000',
            'audience_id' => 'required|integer|exists:audiences,id',
            'employer_ids' => 'required|array',
            'employer_ids.*' => 'integer|exists:employers,id',
        ]);

        $event = new Event($validated);
        $event->save();

        array_push($validated['employer_ids'], Employer::where('user_id', $request->user()->id)->first()->id);
        $event->employers()->sync($validated['employer_ids']);

        if ($request->hasFile('image')) {
            $event->img_path = $request->file('image')->store('img/event' . $event->id, 'public');
            $event->save();
        }

        return new EventResource($event);
    }

    public function update(Request $request, $id): EventResource
    {
        $validated = $request->validate([
            'title' => 'string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'date' => 'date',
            'desc' => 'string|max:1000',
            'audience_id' => 'integer|exists:audiences,id',
            'employer_ids' => 'array',
            'employer_ids.*' => 'integer|exists:employers,id',
        ]);

        $event = Event::findOrFail($id);

        $user = $request->user();

        if ($this->isEmployer($user)) {
            if (!$event->employers()->where('user_id', $user->id)->exists()) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        }

        if (isset($validated['employer_ids'])) {
            array_push($validated['employer_ids'], Employer::where('user_id', $user->id)->first()->id);
            $event->employers()->sync($validated);
        }

        if (isset($validated['audience_id'])) {
            $event->audience()->associate($validated['audience_id']);
        }

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($event->img_path);
            $event->img_path = $request->file('image')->store('img/event' . $event->id, 'public');
        }

        $event->update($validated);

        return new EventResource($event);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $event = Event::findOrFail($id);

            if (!$event->employers()->where('user_id', $user->id)->exists()) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        }

        $event->employers()->detach();
        Storage::disk('public')->delete(Event::find($id)->img_path);

        return Event::destroy($id);
    }
}
