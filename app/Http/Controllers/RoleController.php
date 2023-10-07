<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(): RoleCollection
    {
        return new RoleCollection(Role::all());
    }

    public function show(Role $role): RoleResource
    {
        return new RoleResource($role);
    }

    public function store(Request $request): RoleResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000',
            'slug' => 'required|string|max:15',
            'permissions' => 'required|string',
        ]);

        $role = Role::create($validated);

        return new RoleResource($role);
    }

    public function update(Request $request, Role $role): RoleResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000',
            'slug' => 'required|string|max:15',
            'permissions' => 'required|string',
        ]);

        $role->update($validated);

        return new RoleResource($role);
    }

    public function destroy(Role $role)
    {
        return $role->delete();
    }
}
