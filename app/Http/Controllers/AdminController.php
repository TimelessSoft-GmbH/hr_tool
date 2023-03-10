<?php

namespace App\Http\Controllers;

use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin', [
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),
            'users' => User::all(),
            'roles' => Role::all()
        ]);
    }
    public function roleChange($id){
        //Get necessary Data
        $user = User::findOrFail($id);
        $adminRole = Role::findOrFail(1);
        $userRole = Role::findOrFail(2);
        //Detach current role of user
        $user->roles()->detach();

        //If user is user
        if ($user->hasrole === 'user') {
            $user->syncRoles([$adminRole->name]);

            DB::table('users')
              ->where('id', $id)
              ->update(['hasrole' => 'admin']);
        }

        //If user is admin
        if ($user->hasrole === 'admin') {
            $user->syncRoles([$userRole->name]);

            DB::table('users')
                ->where('id', $id)
                ->update(['hasrole' => 'user']);
        }

        return redirect('/admin')->with('success', 'Role updated successfully.');
    }
    public function destroy($id){
        User::find($id)->delete();
        return redirect('/admin');
    }
    public function update(Request $request, $id){
        foreach ($request->all() as $index => $value) {
            DB::table('users')
                ->where('id', $id)
                ->update([$index => $value]);
        }
        return redirect('/admin');
    }

    public function answerUpdateVacation(Request $request, $id){
        DB::table('vacation_requests')
            ->where('id', $id)
            ->update(['accepted' => $request->antwort]);

        if($request->antwort === 'accepted'){
            $this->updateVacationDays($id);
        }
        return redirect('/admin');
    }

    public function answerUpdateSickness(Request $request, $id){
        DB::table('sickness_requests')
            ->where('id', $id)
            ->update(['accepted' => $request->antwortSicknessRequest]);

        if($request->antwortSicknessRequest === 'accepted'){
            $this->updateSicknessLeave($id);
        }
        return redirect('/admin');
    }

    public function updateVacationDays($id){
        $vacationreq = VacationRequest::findOrFail($id);
        $user = User::findOrFail($vacationreq->user_id);
        $vacationDays_left = $user->vacationDays - $vacationreq->total_days;

        DB::table('users')
            ->where('id', $vacationreq->user_id)
            ->update(['vacationDays_left' => $vacationDays_left]);
    }

    public function updateSicknessLeave($id){
        $sicknessReq = VacationRequest::findOrFail($id);
        $user = User::findOrFail($sicknessReq->user_id);
        $sicknessLeave = $user->sicknessLeave + $sicknessReq->total_days;

        DB::table('users')
            ->where('id', $sicknessReq->user_id)
            ->update(['sicknessLeave' => $sicknessLeave]);
    }
}
