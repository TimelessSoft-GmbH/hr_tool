<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VacationRequest;
use App\Models\SicknessRequest;
use App\Models\WorkHour;
use Illuminate\Http\Request;

class EnterHoursController extends Controller
{
    public function index()
    {
        return view('enter-hours', [
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),
            'workHours' => WorkHour::all(),
            'users' => User::all(),
        ]);
    }

    public function updateHoursAsAdmin(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'year' => 'required|numeric',
            'month' => 'required|numeric|min:1|max:12',
            'hours' => 'required',
        ]);

        $workHour = WorkHour::where('user_id', $validatedData['user_id'])
            ->where('year', $validatedData['year'])
            ->where('month', $validatedData['month'])
            ->first();

        if ($workHour) {
            // Update the existing entry
            $workHour->update(['hours' => $validatedData['hours']]);
        } else {
            // Create a new entry
            WorkHour::create([
                'user_id' => $validatedData['user_id'],
                'year' => $validatedData['year'],
                'month' => $validatedData['month'],
                'hours' => $validatedData['hours'],
            ]);
        }

        // Redirect the user back to the previous page or to a success page
        return back();
    }

    public function updateTable(Request $request)
    {
        $year = $request->input('year');
        $users = User::all();
        $workHours = WorkHour::where('year', $year)->get();

        return view('components._table', compact('users', 'workHours', 'year'));
    }


}
