<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index() {
        $permissions = Permission::paginate(10);
        return view('pages.permissions', ["permissions"=>$permissions]);
    }
}

