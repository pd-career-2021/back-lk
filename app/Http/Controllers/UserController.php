<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): UserCollection
    {
        return new UserCollection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\Factory  $validator
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): UserResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'surname' => 'required|string|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'sex' => 'required|string|in:male,female',
            'role_ids' => 'required|array',
            'role_ids.*' => 'integer',
            'faculty_id' => 'required|integer',
        ]);

        $user = User::create($validated);
        $user->password = bcrypt($validated['password']);
        $user->roles()->sync($validated['role_ids']);
        $faculty = Faculty::findOrFail($validated['faculty_id']);
        $user->faculty()->associate($faculty);
        $user->save();

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/u' . $user->id, 'public');
            $user->save();
        }

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  \Illuminate\Validation\Factory  $validator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user): UserResource
    {
        $validated = $request->validate( [
            'name' => 'string|max:45',
            'surname' => 'string|max:45',
            'email' => 'email|unique:users,email',
            'password' => 'string|confirmed',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'sex' => 'string|in:male,female',
            'role_ids' => 'array',
            'role_ids.*' => 'integer',
            'faculty_id' => 'integer',
        ]);

        $auth_user = $request->user();

        if (!$this->isAdmin($auth_user)) {
            if ($auth_user->id != $id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else {
            if (isset($validated['role_ids'])) {
                $user->roles()->sync($validated['role_ids']);
            }
        }

        $user->update($validated);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->img_path);
            $user->img_path = $request->file('image')->store('img/u' . $id, 'public');
            $user->save();
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Storage::disk('public')->delete($user->img_path);;
        return $user->delete();
    }

    /**
     * Search for a email.
     *
     * @param  int  $email
     * @return \Illuminate\Http\Response
     */
    // public function search($email)
    // {
    //     return User::where('email', $email)->get();
    // }
}
