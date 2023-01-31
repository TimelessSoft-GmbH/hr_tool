<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacationRequest;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'vacationRequests' => VacationRequest::all(),
        ]);
    }
}
