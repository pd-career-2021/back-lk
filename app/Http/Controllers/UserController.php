<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

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
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'surname' => 'string|max:45',
            'name' => 'string|max:45',
            'nickname' => 'required|string|max:45|unique:users,nickname',
            'sex' => 'required|string|in:male,female',
            'role_id' => 'required|integer',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'surname' => $fields['surname'],
            'name' => $fields['name'],
            'nickname' => $fields['nickname'],
            'sex' => $fields['sex'],
            'password' => bcrypt($fields['password'])
        ]);

        $role = Role::find($request->input('role_id'));
        if(!$role) {
            return response([
                'message' => 'Role not found.'
            ], 401);
        } else {
            $user->role()->associate($role);
        }
        
        $user->save();
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
        return User::find($id);
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
        $auth_user = $request->user();
        $user = User::find($id);
        if (!$this->isAdmin($user)) {
            if ($auth_user->id != $id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else {
            if ($request->has('role_id')) {
                $role = Role::find($request->input('role_id'));
                if(!$role) {
                    return response([
                        'message' => 'Role not found.'
                    ], 401);
                } else {
                    $user->role()->associate($role);
                }
            }
        }
        
        $user->update($request->all());
        
        $user->save();
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
        return User::destroy($id);
    }

    /**
     * Search for a nickname.
     *
     * @param  int  $nickname
     * @return \Illuminate\Http\Response
     */
    public function search($nickname)
    {
        return User::where('nickname', $nickname)->get();
    }
}
