<?php

namespace App\Http\Controllers;

use App\Models\FileHistory;
use App\Models\PastSalary;
use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use GuzzleHttp\Client;

class UpdateUserController extends Controller
{
    public function index($id)
    {
        $fileHistories = FileHistory::where('user_id', Auth::id())->get();

        return view('update-user', [
            'user' => User::findOrFail($id),
            'roles' => Role::all(),
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),
            'fileHistories' => $fileHistories,

        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        //Get Data without effective Dates, which get handled in separate function:
        $data = $request->except(array_merge(["_token"], preg_grep('/^effective_date_/', array_keys($request->all()))));

        foreach ($data as $index => $value) {
            if ($index === 'hasrole') {
                $this->changeUserRole($id, $value);
            }
            if ($index === 'contract' && $request->hasFile('contract')) {
                // Store the new file
                $this->pdfFileUpload($request, $user);
            }
            if ($index === 'salary' && $value !== $user->salary) {
                // Save new past salary in the past_salaries table
                $this->newPastSalary($id, $value);
            }

            if ($value !== null && $index !== "contract") {
                DB::table('users')
                    ->where('id', $id)
                    ->update([$index => $value]);
            }
        }

        //Update the past salaries
        $this->updateOldPastSalariesDates($user, $request);
        return redirect('/admin');
    }

    public function destroy($id)
    {
        VacationRequest::where('user_id', $id)->delete();
        SicknessRequest::where('user_id', $id)->delete();
        User::find($id)->delete();
        return redirect('/admin');
    }

    public function destroyVacationRequest($id)
    {
        $totalDays = VacationRequest::where('id', $id)->value('total_days');
        $user = User::find(VacationRequest::where('id', $id)->value('user_id'));
        VacationRequest::where('id', $id)->delete();
        //Reset the vacationDays_left
        $newTotalDays = $user->vacationDays_left + $totalDays;
        $user->vacationDays_left = $newTotalDays;
        $user->save();
        return redirect('/admin');
    }

    function changeUserRole($userId, $newRoleName)
    {
        $user = User::findOrFail($userId);
        $oldRole = $user->roles()->first();
        $newRole = Role::findByName($newRoleName);

        if ($oldRole) {
            $user->removeRole($oldRole);
        }

        $user->assignRole($newRole);

        DB::table('users')
            ->where('id', $userId)
            ->update(['hasrole' => $newRole]);

        return true;
    }

    public function pdfFileUpload($request, $user)
    {
        $pdf = $request->file('contract');
        $path = $pdf->store('contracts', 'public');

        // Create a new file history record
        DB::table('file_history')->insert([
            'user_id' => $user->id,
            'file_name' => $pdf->getClientOriginalName(),
            'file_path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')
            ->where('id', $user->id)
            ->update(["contract" => Storage::url($path)]);
    }

    public function newPastSalary($id, $value)
    {
        $pastSalary = new PastSalary;
        $pastSalary->user_id = $id;
        $pastSalary->salary = $value;
        $pastSalary->effective_date = now();
        $pastSalary->save();
    }

    public function updateOldPastSalariesDates($user, $request)
    {
        $pastSalaries = $user->pastSalaries;

        // Update only changed effective_dates
        foreach ($pastSalaries as $pastSalary) {
            if ($pastSalary->salary !== $user->salary) {
                $effectiveDate = $request->input("effective_date_{$pastSalary->id}");
                if ($effectiveDate !== null && $effectiveDate !== $pastSalary->effective_date) {
                    $pastSalary->update([
                        'effective_date' => $effectiveDate,
                    ]);
                }
            }
        }
    }

    function getWorkingDaysInMonth(User $user)
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        //Get Public Holidays from API
        $client = new Client();
        $response = $client->get("https://date.nager.at/api/v3/PublicHolidays/{$year}/AT");
        $holidays = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $holiday_dates[] = (string)$holiday['date'];
        }

        //Get the user's workdays
        $workdays = json_decode($user->workdays, true, 512, JSON_THROW_ON_ERROR) ?? ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

        //Calculate total workdays in the month
        $total_days = Carbon::createFromDate($year, $month)->daysInMonth;
        $workdays_in_month = 0;
        for ($day = 1; $day <= $total_days; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            if (in_array($date->format('l'), $workdays) && !in_array($date->format('Y-m-d'), $holiday_dates)) {
                $workdays_in_month++;
            }
        }

        return $workdays_in_month;
    }

    function getWorkingHoursInMonth(User $user)
    {
        $workdays = json_decode($user->workdays, true, 512, JSON_THROW_ON_ERROR) ?? ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        $workingDaysInMonth = $this->getWorkingDaysInMonth($user);
        $averageHoursPerDay = $user->hours_per_week / count($workdays);

        return $workingDaysInMonth * $averageHoursPerDay;
    }
}
