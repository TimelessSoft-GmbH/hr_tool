<?php

namespace App\Http\Controllers;

use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('urlaub', [
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),
            'users' => User::all(),
        ]);
    }
}
