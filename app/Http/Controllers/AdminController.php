<?php

namespace App\Http\Controllers;

use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function update($id){
        $role = User::find($id)->hasrole;
        if($role === 'user'){
            DB::table('users')
                ->where('id', $id)
                ->update(['hasrole' => 'admin']);
        }
        else{
            DB::table('users')
                ->where('id', $id)
                ->update(['hasrole' => 'user']);
        }

        return redirect('/admin');
    }
//TODO: IT DOESNT GET REDIRECTED TO THE CORRECT FORM
    public function updateAnswerDB($request){
        ddd($request);
        //DB::table('vacation_requests')
            //->where('id', $id)
            //->update(['antwort' => $antwort]);

        //return redirect('/admin');
    }
}
