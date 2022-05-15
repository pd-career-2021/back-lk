<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request) {
        if ((\DB::table('roles')->count() == 0) && (\DB::table('users')->count() == 0)) {
            $this -> registerAdmin($request);
        }
        $fields = $request->validate([
            'surname' => 'string|max:45',
            'name' => 'string|max:45',
            'nickname' => 'required|string|max:45|unique:users,nickname',
            'sex' => 'required|string|in:male,female',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'surname' => $fields['surname'],
            'name' => $fields['name'],
            'nickname' => $fields['nickname'],
            'sex' => $fields['sex'],
            'password' => bcrypt($fields['password'])
        ]);

        $role = Role::find(4);
        $user->role()->associate($role);
        $user->save();

        $token = $user->createToken('polytoken', ['user'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'nickname' => 'required|string|max:45',
            'password' => 'required|string'
        ]);

        $user = User::where('nickname', $fields['nickname'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong credentials.'
            ], 401);
        }

        $role = $user->role_id;
        if ($role === 1) {
            $token = $user->createToken('polytoken', ['admin'])->plainTextToken;
        } else if ($role === 2) {
            $token = $user->createToken('polytoken', ['student'])->plainTextToken;
        } else if ($role === 3) {
            $token = $user->createToken('polytoken', ['employer'])->plainTextToken;
        } else if ($role === 4) {
            $token = $user->createToken('polytoken', ['user'])->plainTextToken;
        }

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out.'
        ];
    }

    private function registerAdmin(Request $request) {
        $fields = $request->validate([
            'surname' => 'string|max:45',
            'name' => 'string|max:45',
            'nickname' => 'required|string|max:45|unique:users,nickname',
            'sex' => 'required|string|in:male,female',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'surname' => $fields['surname'],
            'name' => $fields['name'],
            'nickname' => $fields['nickname'],
            'sex' => $fields['sex'],
            'password' => bcrypt($fields['password'])
        ]);

        Role::create(['name' => 'Admin', 'desc' => 'Роль администратора']);
        Role::create(['name' => 'Student', 'desc' => 'Роль студента']);
        Role::create(['name' => 'Employer', 'desc' => 'Роль представителя организации']);
        Role::create(['name' => 'User', 'desc' => 'Временная роль']);

        $role = Role::find(1);
        $user->role()->associate($role);
        $user->save();

        $token = $user->createToken('polytoken', ['admin'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
