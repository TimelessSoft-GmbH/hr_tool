<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $fileHistories = [];

        return view('create-user', [
            'roles' => Role::all(),
            'fileHistories' => $fileHistories,
        ]);
    }
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'adress' => 'nullable',
            'password' => 'required',
            'hours_per_week' => 'nullable',
            'vacationDays' => 'nullable',
            'hasrole' => 'required',
            'start_of_work' => 'required',
            'phoneNumber' => 'required',
            'salary' => 'nullable'
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->adress = $request->adress;
        $user->password = Hash::make($request->password);
        $user->hours_per_week = $request->hours_per_week;
        $user->vacationDays = $request->vacationDays;
        $user->hasrole = $request->hasrole;
        $user->start_of_work = $request->start_of_work;
        $user->phoneNumber = $request->phoneNumber;
        $user->salary = $request->salary;
        $user->initials = $this->initials($request->name);

        // Convert workdays to JSON and assign it to the user
        $workdays = $request->workdays ? json_encode($request->workdays) : null;
        $user->workdays = $workdays;
        $user->save();

        return redirect('/admin');
    }

    public function initials ($str) {
        $ret = '';
        foreach(explode(' ', $str) as $word) {
            $ret .= strtoupper($word[0]);
        }
        return $ret;
    }

    public function destroyVacationRequest(){

    }
}
