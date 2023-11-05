<?php

namespace App\Http\Controllers;

use App\Filters\Event\EventAudienceFilter;
use App\Filters\Event\EventDateFilter;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Audience;
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
                return $events->paginate(10);
            });

        return new EventCollection($response);
    }

    public function show(Event $event): EventResource
    {
        return new EventResource($event);
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

        $user = $request->user();

        $audience = Audience::findOrFail($validated['audience_id']);
        $validatedEmployerIds = Employer::whereIn('id', $validated['employer_ids'])->pluck('id')->toArray();

        if ($this->isEmployer($user)) {
            $validatedEmployerIds = array_merge([$user->employer->id], $validatedEmployerIds);
        }

        $event = Event::create($validated);
        $event->employers()->sync($validatedEmployerIds);
        $event->audience()->associate($audience);

        if ($request->hasFile('image')) {
            $event->img_path = $request->file('image')->store('img/event' . $event->id, 'public');
        }

        $event->save();

        return new EventResource($event);
    }

    public function update(Request $request, Event $event): EventResource
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

        $user = $request->user();

        if ($this->isEmployer($user)) {
            if (!$event->employers()->where('user_id', $user->id)->exists()) {
                return response()->json(['message' => 'You do not have permission to do this.'], 403);
            }

            if (!in_array($user->employer->id, $validated['employer_ids'])) {
                return response()->json(['message' => 'You do not have permission to do this.'], 403);
            }
        }

        $audience = Audience::findOrFail($validated['audience_id']);
        $validatedEmployerIds = Employer::whereIn('id', $validated['employer_ids'])->pluck('id')->toArray();

        $event->update($validated);
        $event->employers()->sync($validatedEmployerIds);
        $event->audience()->associate($audience);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($event->img_path);
            $event->img_path = $request->file('image')->store('img/event' . $event->id, 'public');
        }

        $event->save();

        return new EventResource($event);
    }

    public function destroy(Request $request, Event $event)
    {
        $user = $request->user();

        if ($this->isEmployer($user)) {
            if (!$event->employers()->where('user_id', $user->id)->exists()) {
                return response(['message' => 'You do not have permission to do this.'], 401);
            }
        }

        $event->employers()->detach();
        Storage::disk('public')->delete($event->img_path);

        return $event->delete();
    }
}
