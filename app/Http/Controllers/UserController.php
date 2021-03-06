<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
            $user['image'] = asset('storage/' . $path);
        }

        return $users;
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
            'name' => 'required|string|max:45',
            'surname' => 'required|string|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'sex' => 'required|string|in:male,female',
            'role_id' => 'required|integer',
            'faculty_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $user = new User($request->all());
        $user->password = bcrypt($request->input('password'));

        $role = Role::find($request->input('role_id'));
        if (!$role) {
            return response([
                'message' => 'Role not found.'
            ], 401);
        } else {
            $user->role()->associate($role);
        }

        $faculty = Faculty::find($request->input('faculty_id'));
        if (!$faculty) {
            return response([
                'message' => 'Faculty not found.'
            ], 401);
        } else {
            $user->faculty()->associate($faculty);
        }
        $user->save();

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/u' . $user->id, 'public');
        }
        $user->save();
        $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        $user['image'] = asset('storage/' . $path);

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        $user['image'] = asset('storage/' . $path);
        $user->role;
        $user->faculty;

        return $user;
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
            'name' => 'string|max:45',
            'surname' => 'string|max:45',
            'email' => 'email|unique:users,email',
            'password' => 'string|confirmed',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'sex' => 'string|in:male,female',
            'role_id' => 'integer',
            'faculty_id' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $auth_user = $request->user();
        $user = User::find($id);
        if (!$this->isAdmin($auth_user)) {
            if ($auth_user->id != $id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else {
            if ($request->has('role_id')) {
                $role = Role::find($request->input('role_id'));
                if (!$role) {
                    return response([
                        'message' => 'Role not found.'
                    ], 401);
                } else {
                    $user->role()->associate($role);
                }
            }
        }

        $user->update($request->all());
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->img_path);
            $user->img_path = $request->file('image')->store('img/u' . $id, 'public');
        }

        $user->save();
        $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        $user['image'] = asset('storage/' . $path);
        $user->role;
        $user->faculty;

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Storage::disk('public')->delete(User::find($id)->img_path);
        return User::destroy($id);
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
