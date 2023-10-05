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

    public function index(): UserCollection
    {
        return new UserCollection(User::all());
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

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

        $user = new User($validated);
        $user->password = bcrypt($validated['password']);

        $validatedRoleIds = Role::whereIn('id', $validated['role_ids'])->pluck('id')->toArray();
        $user->roles()->sync($validatedRoleIds);

        $faculty = Faculty::findOrFail($validated['faculty_id']);
        $user->faculty()->associate($faculty);

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/u' . $user->id, 'public');
        }

        $user->save();

        return new UserResource($user);
    }

    public function update(Request $request, User $user): UserResource
    {
        $validated = $request->validate([
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

        $authUser = $request->user();

        if (!$this->isAdmin($authUser) && $authUser->id != $user->id) {
            return response(['message' => 'You do not have permission to do this.'], 401);
        }

        if ($this->isAdmin($authUser) && $request->has('role_ids')) {
            $validatedRoleIds = Role::whereIn('id', $validated['role_ids'])->pluck('id')->toArray();
            $user->roles()->sync($validatedRoleIds);
        }

        $user->update($validated);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->img_path);
            $user->img_path = $request->file('image')->store('img/u' . $user->id, 'public');
        }
        
        $user->save();

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        Storage::disk('public')->delete($user->img_path);

        return $user->delete();
    }
}
