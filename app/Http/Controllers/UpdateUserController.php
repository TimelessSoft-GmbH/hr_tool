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
use Illuminate\Support\Facades\Log;


class UpdateUserController extends Controller
{
    public function index($id)
    {
        $user = User::findOrFail($id);
        $fileHistories = FileHistory::where('user_id', $user->id)->get();

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
        // Process files separately
        if ($request->hasFile('files')) {
            $this->pdfFileUpload($request, $user);
        }

        // Process other fields
        $data = $request->except(array_merge(["_token", "files"], preg_grep('/^effective_date_/', array_keys($request->all()))));
        foreach ($data as $index => $value) {
            if ($index === 'hasrole') {
                $this->changeUserRole($id, $value);
            }
            if ($index === 'salary' && $value !== $user->salary) {
                $this->newPastSalary($id, $value);
            }
            if ($value !== null) {
                DB::table('users')
                    ->where('id', $id)
                    ->update([$index => $value]);
            }
        }

        //Update the past salaries
        $this->updateOldPastSalariesDates($user, $request);
        return redirect('/users');
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
        $files = $request->file('files');
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $filename = str_replace(array('-', ' '), '_', $filename);

            $path = $file->storeAs('pdfs', $filename, 'public');
            $fileHistory = new FileHistory();
            $fileHistory->user_id = $user->id;
            $fileHistory->file_name = $filename;
            $fileHistory->file_path = $path;
            $fileHistory->save();
        }
    }

    public function deleteFile(FileHistory $fileHistory)
    {
        if (Storage::exists($fileHistory->file_path)) {
            Storage::delete($fileHistory->file_path);
            // Delete the file history record from the database
            $fileHistory->delete();
        }

        return redirect()->back();
    }


    public function newPastSalary($id, $value)
    {
        $pastSalary = new PastSalary;
        $pastSalary->user_id = $id;
        $pastSalary->salary = $value;
        $pastSalary->effective_date = now();
        $pastSalary->save();

        return;
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

    function getWorkingDaysInMonth(User $user, $month)
    {
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
        $workdays = $user->workdays ? json_decode($user->workdays, true) : ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

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

    function getWorkingHoursInMonth(User $user, $month)
    {
        $workdays = json_decode($user->workdays, true);
        if ($workdays === null && json_last_error() !== JSON_ERROR_NONE) {
            $workdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        }
        $workingDaysInMonth = $this->getWorkingDaysInMonth($user, $month);
        $averageHoursPerDay = $user->hours_per_week / count($workdays);

        return $workingDaysInMonth * $averageHoursPerDay;
    }

    public function destroy($id)
    {
        VacationRequest::where('user_id', $id)->delete();
        SicknessRequest::where('user_id', $id)->delete();
        FileHistory::where('user_id', $id)->delete();
        User::find($id)->delete();
        return redirect('/users');
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

    public function destroySicknessRequest($id)
    {
        $user = User::find(SicknessRequest::where('id', $id)->value('user_id'));
        SicknessRequest::where('id', $id)->delete();

        $user->save();
        return redirect('/admin');
    }
}
