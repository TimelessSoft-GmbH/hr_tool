<?php

namespace App\Http\Controllers;

use App\Mail\MyEmail;
use App\Models\User;
use App\Models\VacationRequest;
use App\Models\SicknessRequest;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

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
        $attributes = $this->getAttributes();
        //Mail::to('paul.hager888@gmail.com')->send(new MyEmail());

        VacationRequest::create($attributes);
        return redirect('/dashboard');
    }

    public function storeSick()
    {
        $attributes = $this->getAttributes();

        SicknessRequest::create($attributes);
        return redirect('/dashboard');
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        $attributes = request()?->validate([
            'start_date' => ['required', 'date:Y-m-d'],
            'end_date' => ['required', 'date:Y-m-d'],
            'total_days' => [],
            'user_id' => ['required'],
        ]);

        $client = new Client();
        $year = date('Y');
        $response = $client->get("https://date.nager.at/api/v3/PublicHolidays/{$year}/AT");
        $holidays = json_decode($response->getBody(), true);

        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $holiday_dates[] = $holiday['date'];
        }
        $start_date = Carbon::parse($attributes['start_date']);
        $end_date = Carbon::parse($attributes['end_date']);
        $total_days = 0;
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            if ($date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_dates)) {
                $total_days++;
            }
        }

        $attributes['total_days'] = $total_days;

        return $attributes;
    }
}
