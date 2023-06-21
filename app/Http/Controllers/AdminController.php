<?php

namespace App\Http\Controllers;

use App\Mail\AnswerEmail;
use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin', [
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),
            'users' => User::all(),
            'roles' => Role::all(),
            'holiday_dates' => $this->getHolidayOfCurrentMonth()
        ]);
    }

    public function getHolidayOfCurrentMonth()
    {
        //Get Public Holidays from API
        $currentMonth = Carbon::now()->format('m');
        $year = date('Y');
        $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/AT";

        $client = new Client();
        $response = $client->get($url);
        $holidays = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $currentMonth = Carbon::now()->format('m');
        $holidayDates = [];
        foreach ($holidays as $holiday) {
            $holidayDate = Carbon::parse($holiday['date'])->format('m');
            if ($holidayDate === $currentMonth) {
                $holidayDates[] = $holiday['date'];
            }
        }
        return $holidayDates;
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect('/admin');
    }

    public function updateVacationAnswer(Request $request, $id)
    {
        DB::table('vacation_requests')
            ->where('id', $id)
            ->update(['accepted' => $request->antwort]);

        if ($request->antwort === 'accepted') {
            $this->updateVacationDays($id);
        }

        $user = User::findOrFail(VacationRequest::findOrFail($id)->user_id);

        $this->emailNotification($request->antwort, $user, 'Urlaubsantrag');
        return redirect('/admin');
    }

    public function updateSicknessAnswer(Request $request, $id)
    {
        DB::table('sickness_requests')
            ->where('id', $id)
            ->update(['accepted' => $request->antwortSicknessRequest]);

        if ($request->antwortSicknessRequest === 'accepted') {
            $this->updateSicknessLeave($id);
        }

        $user = User::findOrFail(SicknessRequest::findOrFail($id)->user_id);
        $this->emailNotification($request->antwortSicknessRequest, $user, 'Krankheitsurlaub');

        return redirect('/admin');
    }

    public function updateVacationDays($id)
    {
        $vacationreq = VacationRequest::findOrFail($id);
        $user = User::findOrFail($vacationreq->user_id);
        $vacationDays_left = $user->vacationDays_left - $vacationreq->total_days;

        DB::table('users')
            ->where('id', $vacationreq->user_id)
            ->update(['vacationDays_left' => $vacationDays_left]);
    }

    public function updateSicknessLeave($id)
    {
        $sicknessReq = SicknessRequest::findOrFail($id);
        $user = User::findOrFail($sicknessReq->user_id);
        $sicknessLeave = $user->sicknessLeave + $sicknessReq->total_days;

        DB::table('users')
            ->where('id', $sicknessReq->user_id)
            ->update(['sicknessLeave' => $sicknessLeave]);
    }

    public function emailNotification($answer, $user, $typeOfNotification): void
    {
        $email = new AnswerEmail();

        //Get Data for Email
        $email->data = [
            'user_id' => $user,
            'answer' => $answer,
            'type_of_notification' => $typeOfNotification,
        ];

        //Send Email to all Admins
        Mail::to($user->email)->send($email);
    }

    public function storePastVac(Request $request)
    {
        //Get Attributes
        $attributes = $this->getAttributes();
        //Create Vacation Request
        $newVac = VacationRequest::create($attributes);

        //Setting it directly to accepted
        DB::table('vacation_requests')
            ->where('id', $newVac->id)
            ->update(['accepted' => 'accepted']);

        $this->updateVacationDays($newVac->id);

        return redirect('/admin');
    }

    public function storePastSick(Request $request)
    {
        //Get Attributes
        $attributes = $this->getAttributes();
        //Create Vacation Request
        $newSick = SicknessRequest::create($attributes);

        //Setting it directly to accepted
        DB::table('sickness_requests')
            ->where('id', $newSick->id)
            ->update(['accepted' => 'accepted']);

        return redirect('/admin');
    }

    public function getAttributes(): array
    {
        $attributes = request()?->validate([
            'start_date' => ['required', 'date:Y-m-d'],
            'end_date' => ['required', 'date:Y-m-d'],
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
}
