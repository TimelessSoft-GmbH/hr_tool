<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class DisplayUserController extends Controller
{
    public function index()
    {
        return view('display-user', [
            'users' => User::all(),
            'roles' => Role::all(),
        ]);
    }
}
