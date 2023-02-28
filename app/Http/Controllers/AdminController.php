<?php

namespace App\Http\Controllers;

use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
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

    public function answerUpdateVacation(Request $request, $id){
        DB::table('vacation_requests')
            ->where('id', $id)
            ->update(['accepted' => $request->antwort]);

        return redirect('/admin');
    }

    public function answerUpdateSickness(Request $request, $id){
        DB::table('sickness_requests')
            ->where('id', $id)
            ->update(['accepted' => $request->antwort]);

        return redirect('/admin');
    }

    public function destroy($id){
        User::find($id)->delete();
        return redirect('/admin');
    }
}
