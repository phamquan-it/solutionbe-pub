<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json([$roles], 200);
    }

    public function store()
    {
        $roles = Role::all();
        return response()->json([$roles], 200);
    }
}
