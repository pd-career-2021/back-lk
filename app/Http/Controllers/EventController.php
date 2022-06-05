<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Audience;
use App\Models\Employer;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        foreach ($events as $event) {
            $path = ($event->img_path) ? $event->img_path : 'img/blank.jpg';
            $event['image'] = asset('storage/' . $path);
        }

        return $events;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'date' => 'required|date',
            'desc' => 'required|string|max:1000',
            'audience_id' => 'required|integer',
            'employer_ids' => 'required|array',
            'employer_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $event = new Event($request->all());
        $user = $request->user;

        if ($this->isEmployer($user)) {
            $validated = array();
            $employer_id = Employer::where('user_id', $user->id)->first()->id;
            if (!in_array($employer_id, $request->input('employer_ids'))) {
                array_push($validated, $employer_id);
            }
            foreach ($request->input('employer_ids') as $id) {
                if (Employer::find($id))
                    array_push($validated, $id);
            }
            $event->employers()->sync($validated);
        }

        $audience = Audience::find($request->input('audience_id'));
        if (!$audience) {
            return response([
                'message' => 'Audience not found.'
            ], 401);
        } else {
            $event->audience()->associate($audience);
        }
        $event->save();

        if ($request->hasFile('image')) {
            $event->img_path = $request->file('image')->store('img/event' . $event->id, 'public');
        }
        $event->save();
        $path = ($event->img_path) ? $event->img_path : 'img/blank.jpg';
        $event['image'] = asset('storage/' . $path);
        $event->employers;

        return $event;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);
        $path = ($event->img_path) ? $event->img_path : 'img/blank.jpg';
        $event['image'] = asset('storage/' . $path);
        $event->audience;
        $event->employers;

        return $event;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'date' => 'date',
            'desc' => 'string|max:1000',
            'audience_id' => 'integer',
            'employer_ids' => 'array',
            'employer_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $event = Event::find($id);

        if ($request->has('employer_ids')) {
            $user = $request->user();
            if ($this->isEmployer($user)) {
                if (in_array(Employer::where('user_id', $user->id)->first()->id, $request->input('employer_ids'))) {
                    $validated = array();
                    foreach ($request->input('employer_ids') as $id) {
                        if (Employer::find($id))
                            array_push($validated, $id);
                    }
                    $event->employers()->sync($validated);
                } else {
                    return response([
                        'message' => 'Your employer_id must be in the employer_ids array.'
                    ], 401);
                }
            } else if ($this->isAdmin($user)) {
                $validated = array();
                foreach ($request->input('employer_ids') as $id) {
                    if (Employer::find($id))
                        array_push($validated, $id);
                }
                $event->employers()->sync($validated);
            }
        }

        $event->update($request->all());

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($event->img_path);
            $event->img_path = $request->file('image')->store('img/event' . $event->id, 'public');
        }

        if ($request->has('audience_id')) {
            $audience = Audience::find($request->input('audience_id'));
            if (!$audience) {
                return response([
                    'message' => 'Audience not found.'
                ], 401);
            } else {
                $event->audience()->associate($audience);
            }
        }

        $event->save();
        $path = ($event->img_path) ? $event->img_path : 'img/blank.jpg';
        $event['image'] = asset('storage/' . $path);
        $event->audience;
        $event->employers;

        return $event;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($this->isEmployer($user)) {
            if (Event::find($id)->employers()->where('user_id', $user->id)->exists()) {
                $event = Event::find($id);
                $event->employers()->detach();
                Storage::disk('public')->delete(Event::find($id)->img_path);
                return Event::destroy($id);
            } else {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else if ($this->isAdmin($user)) {
            $event = Event::find($id);
            $event->employers()->detach();
            Storage::disk('public')->delete(Event::find($id)->img_path);
            return Event::destroy($id);
        }
    }
}
