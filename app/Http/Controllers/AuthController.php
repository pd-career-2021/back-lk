<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Faculty;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required|string|max:45',
            'name' => 'required|string|max:45',
            'email' => 'required|email|unique:users,email',
            'sex' => 'required|string|in:male,female',
            'password' => 'required|string|confirmed',
            'faculty_id' => 'required|integer',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        if (\DB::table('users')->count() == 0) {
            $this->registerAdmin($request);
        }

        $user = new User($request->all());
        $user->password = bcrypt($request->input('password'));

        $role = Role::find(4);
        $user->role()->associate($role);
        $faculty = Faculty::find($request->input('faculty_id'));
        $user->faculty()->associate($faculty);

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/u' . $user->id, 'public');
        }
        $user->save();
        $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        $user['image'] = asset('storage/' . $path);

        $token = $user->createToken('polytoken', ['user'])->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response([
                'message' => 'Wrong credentials.'
            ], 401);
        }

        $role = $user->role_id;
        $abilities = ['', 'admin', 'student', 'employer', 'user'];
        if ($role) {
            $token = $user->createToken('polytoken', [$abilities[$role]])->plainTextToken;
        }

        $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        $user['image'] = asset('storage/' . $path);

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logged out.'
        ], 200);
    }

    private function registerAdmin(Request $request)
    {
        $user = new User($request->all());
        $user->password = bcrypt($request->input('password'));

        $role = Role::find(1);
        $user->role()->associate($role);

        Faculty::create(['title' => 'Факультет информационных технологий', 'desc' => 'Описание факультета']);
        $faculty = Faculty::find(1);
        $user->faculty()->associate($faculty);

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/u' . $user->id, 'public');
        }
        $user->save();
        $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        $user['image'] = asset('storage/' . $path);

        $token = $user->createToken('polytoken', ['admin'])->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
