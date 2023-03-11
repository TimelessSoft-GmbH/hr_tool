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
        return view('dashboard', [
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),
        ]);
    }

    public function store()
    {
        //Get Attributes
        $attributes = $this->getAttributes();

        VacationRequest::create($attributes);

        //Send Email Notification
        $this->emailNotification($attributes, 'Urlaubsantrag');
        return redirect('/dashboard');
    }

    public function storeSick()
    {
        //Get Attributes
        $attributes = $this->getAttributes();

        SicknessRequest::create($attributes);

        //Send Email Notification
        $this->emailNotification($attributes, 'Krankheitsurlaub');
        return redirect('/dashboard');
    }

    /**
     * @return array
     * @throws \JsonException
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
        //Get Public Holidays from API
        $year = date('Y');
        $response = $client->get("https://date.nager.at/api/v3/PublicHolidays/{$year}/AT");
        $holidays = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $holiday_dates[] = $holiday['date'];
        }

        //Calculate total Days without public Hollidays and Weekends
        $start_date = Carbon::parse($attributes['start_date']);
        $end_date = Carbon::parse($attributes['end_date']);
        $total_days = 0;
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            if ($date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_dates, true)) {
                $total_days++;
            }
        }
        //Add Total Days to attribute to safe it in DB
        $attributes['total_days'] = $total_days;

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
}
