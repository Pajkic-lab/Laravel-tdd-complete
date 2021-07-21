<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);
        return view('roles.index', ["roles" => $roles]);
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', ["permissions" => $permissions]);
    }

    public function store(CreateRoleRequest $request)
    {

        $data = $request->validated();

        $role = Role::create($data);

        $permissions = $data['permission_name'];
        foreach ($permissions as $permission_name) {
            $role->givePermissionTo($permission_name);
        }

        return redirect('/roles');
    }

    public function show(Role $role)
    {

        $permissions = Permission::all();
        $usedPermissions = $role->permissions()->get();

        return view('roles.show', [
            "role" => $role, "permissions" => $permissions, "usedPermissions" => $usedPermissions
        ]);
    }

    public function edit(Role $role)
    {

        $permissions = Permission::all();
        $usedPermissions = $role->permissions()->get();

        return view('roles.edit', [
            "role" => $role, "permissions" => $permissions, "usedPermissions" => $usedPermissions
        ]);
    }

    public function update(Request $request, Role $role)
    {

        $validationRules = [
            'name' => 'string|min:3|max:20|required',
            'guard_name ' => 'string|nullable',
            'permission_name' => 'required|array|min:1',
        ];

        $role = Role::where('id', $role->id)->first();

        if ($request->name == $role->name) {
            $data = request()->validate($validationRules);
        } else {
            $data = request()->validate(array_merge($validationRules, ['name' => 'string|min:3|max:20|required|unique:roles,name']));
        }

        $role->update($data);

        $permissions = Permission::all();
        foreach ($permissions as $permission_name) {
            $role->revokePermissionTo($permission_name);
        }

        $permissions = $data['permission_name'];
        foreach ($permissions as $permission_name) {
            $role->givePermissionTo($permission_name);
        }

        return redirect('/roles');
    }

    public function destroy(Role $role)
    {

        $role->delete();

        return response()->json(['message' => 'success'], 200);
    }

}
