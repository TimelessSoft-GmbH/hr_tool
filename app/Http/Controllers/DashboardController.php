<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use App\Models\SicknessRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        //TODO: Give Admin roles to one user and display based on that role
        if (Auth::user()->hasrole = '') {
            return view('admin-dashboard', [
                'vacationRequests' => VacationRequest::all(),
                'sicknessRequests' => SicknessRequest::all(),
            ]);
        }

        return view('dashboard', [
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),
        ]);
    }

    public function store()
    {
        $attributes = request()?->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'user_id' => ['required'],
        ]);

        VacationRequest::create($attributes);
        return redirect('/dashboard');
    }

    public function storeSick()
    {
        $attributes = request()?->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'user_id' => ['required'],
        ]);

        SicknessRequest::create($attributes);
        return redirect('/dashboard');
    }
}
