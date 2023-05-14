<?php

namespace App\Http\Controllers;

use App\Models\Role;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() // отображение списка элементов
    {
        return new RoleCollection(Role::all());
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
            'desc' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $role = new Role($request->all());

        $role->save();

        return new UserResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return new RoleResource(Role::find($id));
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
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $role = Role::find($id);
        $role->update($request->all());
        $role->save();
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) // удаление записи из базы
    {
        return Role::destroy($id);
    }
}
