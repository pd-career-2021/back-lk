<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:45',
            'name' => 'required|string|max:45',
            'email' => 'required|email|unique:users,email',
            'sex' => 'required|string|in:male,female',
            'password' => 'required|string|confirmed',
            'faculty_id' => 'required|integer',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $user = new User($validated);
        $user->password = bcrypt($validated['password']);

        $role = Role::where('slug', 'user')->firstOrFail();
        $user->roles()->sync($role);

        $faculty = Faculty::findOrFail($validated['faculty_id']);
        $user->faculty()->associate($faculty);

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/u' . $user->id, 'public');
        }

        $user->save();
        $token = $user->createToken('polytoken', ['user'])->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Wrong credentials.'], 401);
        }

        $token = $user->createToken('polytoken', $user->roles->pluck('slug')->toArray())->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out.'], 200);
    }

    public function user(): UserResource
    {
        return new UserResource(auth()->user());
    }
}
