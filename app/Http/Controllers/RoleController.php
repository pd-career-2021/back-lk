<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
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
    public function index()
    {
        return new RoleCollection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\Factory  $validator
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Factory $validator) 
    {
        $validated = $validator->make($request->all(), [
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000',
        ])->validated();

        $role = new Role($validated);
        $role->save();

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return new RoleResource(Role::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  \Illuminate\Validation\Factory  $validator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Factory $validator) 
    {
        $validated = $validator->make($request->all(), [
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000',
        ])->validated();

        $role = Role::findOrFail($id);
        $role->update($validated)->save();

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Role::destroy($id);
    }
}
