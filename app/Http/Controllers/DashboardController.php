<?php

namespace App\Http\Controllers;

use App\Mail\MyEmail;
use App\Models\User;
use App\Models\VacationRequest;
use App\Models\SicknessRequest;
use App\Models\WorkHour;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;

        $user = Auth::user();
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;

        $vacationRequests = VacationRequest::where('user_id', $user->id)
            ->whereYear('start_date', '>=', $currentYear)
            ->whereYear('start_date', '<=', $nextYear)
            ->get();

        $sicknessRequests = SicknessRequest::where('user_id', $user->id)
            ->whereYear('start_date', '>=', $currentYear)
            ->whereYear('start_date', '<=', $nextYear)
            ->get();

        return view('user-dashboard', [
            'vacationRequests' => $vacationRequests,
            'sicknessRequests' => $sicknessRequests,
        ]);
    }

    public function storeVac(Request $request)
    {
        //Get Attributes
        $attributes = $this->getAttributes();
        //Create Vacation Request
        VacationRequest::create($attributes);
        //Send Email Notification
        $this->emailNotification($attributes, 'Urlaubsantrag');
        return redirect('/dashboard');
    }

    public function storeSick()
    {
        //Get Attributes
        $attributes = $this->getAttributes();
        //Create Sickness Request
        SicknessRequest::create($attributes);
        //Send Email Notification
        $this->emailNotification($attributes, 'Krankheitsurlaub');
        return redirect('/dashboard');
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getAttributes(): array
    {
        $attributes = request()?->validate([
            'start_date' => ['required', 'date:Y-m-d'],
            'end_date' => ['required', 'date:Y-m-d', 'after:start_date'],
            'total_days' => [],
            'user_id' => ['required'],
        ]);

        //Get Public Holidays from API
        $client = new Client();
        $year = date('Y');
        $response = $client->get("https://date.nager.at/api/v3/PublicHolidays/{$year}/AT");
        $holidays = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $holiday_dates[] = (string)$holiday['date'];
        }

        // Get user's workdays
        $user = User::findOrFail($attributes['user_id']);
        $workdays = json_decode($user->workdays, true);
        if ($workdays === null && json_last_error() !== JSON_ERROR_NONE) {
            $workdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        }
        //Calculate total Days without public Holidays
        $start_date = Carbon::parse($attributes['start_date'])->startOfDay();
        $end_date = Carbon::parse($attributes['end_date'])->startOfDay();
        $total_days = 0;

        //Check if the start date is a workday of the user before adding them to the total days:
        if (in_array($start_date->format('l'), $workdays, true) && !in_array($start_date->format('Y-m-d'), $holiday_dates, true)) {
            $total_days++;
        }
        //Loop for adding total days
        for ($date = $start_date->copy()->addDay(); $date->lt($end_date); $date->addDay()) {
            if (in_array($date->format('l'), $workdays, true) && !in_array($date->format('Y-m-d'), $holiday_dates, true)) {
                $total_days++;
            }
        }
        //Add one if End-day is a workday
        if (in_array($end_date->format('l'), $workdays, true) && !in_array($end_date->format('Y-m-d'), $holiday_dates, true)) {
            $total_days++;
        }

        //Add Total Days to attribute to save it in DB
        $attributes['total_days'] = (string)$total_days;

        return $attributes;
    }

    /**
     * @param $attributes
     * @return void
     */
    public function emailNotification($attributes, $typeOfNotification): void
    {
        $email = new MyEmail();

        //Get Data for Email
        $email->data = [
            'user_id' => $attributes['user_id'],
            'start_date' => $attributes['start_date'],
            'end_date' => $attributes['end_date'],
            'total_days' => $attributes['total_days'],
            'type_of_notification' => $typeOfNotification,
        ];

        //Send Email to all Admins
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send($email);
        }
    }

    public function storeHours(Request $request)
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

    public function destroyVacationRequest($id)
    {
        $user = User::find(VacationRequest::where('id', $id)->value('user_id'));
        VacationRequest::where('id', $id)->delete();
        return redirect('/dashboard');
    }

    public function destroySicknessRequest($id)
    {
        $user = User::find(SicknessRequest::where('id', $id)->value('user_id'));
        SicknessRequest::where('id', $id)->delete();
        return redirect('/dashboard');
    }
}
