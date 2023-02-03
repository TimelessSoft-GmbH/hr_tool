<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'vacationRequests' => VacationRequest::all(),
        ]);
    }

    public function store()
    {
        // TODO: Create a new Vacation Request
        $attributes = request()?->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'user_id' => ['required'],
        ]);

        VacationRequest::create($attributes);
        return redirect('/dashboard');
    }
}
