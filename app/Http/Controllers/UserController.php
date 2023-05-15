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
        // $users = User::all();
        // foreach ($users as $user) {
        //     $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        //     $user['image'] = asset('public/storage/' . $path);
        //     $user['roles'] = $user->roles()->pluck('name');
        // }

        return new UserCollection(User::all());
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
            'role_ids' => 'required|array',
            'role_ids.*' => 'integer',
            'faculty_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $user = new User($request->all());
        $user->password = bcrypt($request->input('password'));

        $validated = array();
        foreach ($request->input('role_ids') as $id) {
            if (Role::find($id))
                array_push($validated, $id);
        }
        $user->roles()->sync($validated);

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
        // $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        // $user['image'] = asset('public/storage/' . $path);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = User::find($id);
        // $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        // $user['image'] = asset('public/storage/' . $path);
        // $user['roles'] = $user->roles()->pluck('name');
        // $user->faculty;

        return new UserResource(User::find($id));
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
            'role_ids' => 'array',
            'role_ids.*' => 'integer',
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
            if ($request->has('role_ids')) {
                $validated = array();
                foreach ($request->input('role_ids') as $id) {
                    if (Role::find($id))
                        array_push($validated, $id);
                }
                $user->roles()->sync($validated);
            }
        }

        $user->update($request->all());
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->img_path);
            $user->img_path = $request->file('image')->store('img/u' . $id, 'public');
        }

        $user->save();
        // $path = ($user->img_path) ? $user->img_path : 'img/blank.jpg';
        // $user['image'] = asset('public/storage/' . $path);
        // $user['roles'] = $user->roles()->pluck('name');
        // $user->faculty;

        return new UserResource($user);
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
