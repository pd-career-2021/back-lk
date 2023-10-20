<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\SocialCollection;
use App\Http\Resources\SocialResource;
use App\Models\Social;
use App\Models\Employer;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    use ApiHelpers;

    public function index(): SocialCollection
    {
        return new SocialCollection(Social::all());
    }

    public function show(Social $social): SocialResource
    {
        return new SocialResource($social);
    }

    public function store(Request $request): SocialResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'link' => 'required|string|max:255',
            'employer_id' => 'integer',
        ]);

        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
        } else {
            $employer = Employer::findOrFail($validated['employer_id']);
        }

        $social = Social::create($validated);
        $social->employer()->associate($employer);
        $social->save();

        return new SocialResource($social);
    }

    public function update(Request $request, Social $social): SocialResource
    {
        $validated = $request->validate([
            'name' => 'string|max:45',
            'link' => 'string|max:255',
            'employer_id' => 'integer',
        ]);

        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
            if ($social->employer_id !== $employer->id) {
                return response()->json(['message' => 'You do not have permission to do this.'], 403);
            }
        }

        if ($this->isAdmin($user) && $request->has('employer_id')) {
            $employer = Employer::findOrFail($validated['employer_id']);
            $social->employer()->associate($employer);
        }

        $social->update($validated);
        $social->save();

        return new SocialResource($social);
    }

    public function destroy(Request $request, Social $social)
    {
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();

            if ($social->employer_id !== $employer->id) {
                return response()->json(['message' => 'You do not have permission to do this.'], 403);
            }
        }

        return $social->delete();
    }
}
