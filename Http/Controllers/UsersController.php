<?php

namespace App\Http\Controllers;

use App\Http\Requests\users\DeleteUsersRequest;
use App\Http\Requests\users\EditUsersRequest;
use App\Http\Requests\users\ListUsersRequest;
use App\Http\Requests\users\ShowUserRequest;
use App\Http\Requests\users\StoreUsersRequest;
use App\Http\Requests\users\UpdateUsersRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(ListUsersRequest $request)
    {
        $users = User::paginate(10);
        return view('users.index', ["users" => $users]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', ["roles" => $roles]);
    }

    public function store(StoreUsersRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $role = Role::where('name', $data['role_name'])->first();
        if(! $role) {
            return response()->json(['message' => 'error'], 401);
        }

        $user = User::create($data);
        $res = $user->assignRole($data['role_name']);

        if($res) {
            return redirect('/users');
        }
        return response()->json(['message' => 'error'], 402);
    }

    public function show(ShowUserRequest $request, User $user)
    {
        $role = $user->role;
        $permissions = Permission::all();
        $usedPermissions = $role->permissions()->get();

        return view('users.show', [
            "user" => $user, "role" => $role, "permissions" => $permissions
            ,"usedPermissions" => $usedPermissions
        ]);
    }

    public function edit(EditUsersRequest $request, User $user)
    {
        $role = $user->role;
        $roles = Role::all();
        $permissions = Permission::all();
        $usedPermissions = $role->permissions()->get();

        return view('users.edit', [
            "user" => $user, "roles" => $roles, "permissions" => $permissions
            ,"usedPermissions" => $usedPermissions, "role" => $role,
        ]);
    }

    public function update(UpdateUsersRequest $request, User $user)
    {
        $validationRules = [
            'name' => 'string|min:3|max:20|required',
            'email' => 'required|email',
            'password' => 'string|required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z]).*$/',
            'role_name' => 'required|string',
        ];

        if ($request->name == $user->name) {
            $data = request()->validate($validationRules);
        } else {
            $data = request()->validate(array_merge($validationRules, ['name' => 'string|min:3|max:20|required|unique:users,name']));
        }

        $user->update($data);

        $role = Role::first();
        $user->removeRole($role['name']);
        $user->assignRole($data['role_name']);

        return redirect('/users');
    }

    public function destroy(DeleteUsersRequest $request, User $user)
    {
        $user->delete();

        return response()->json(['message' => 'success'], 200);
    }
}
