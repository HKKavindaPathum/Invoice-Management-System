<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(); 
        return view('roles.index', compact('roles'));
    }


    public function create()
    {
        $permissions = Permission::all();
        return view("roles.create", compact('permissions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);
    
        $role = Role::create(['name' => $request->name]);
    
        //Get the permission names from IDs
        $permissions = Permission::whereIn('id', $request->permissions)->get();
    
        $role->syncPermissions($permissions);
    
        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }
    
    
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        return view('roles.show', compact('role'));
    }
    
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }
    
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ]);
    
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
    
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);
    
        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }
    
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
    
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
    
}
