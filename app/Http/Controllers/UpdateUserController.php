<?php

namespace App\Http\Controllers;

use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UpdateUserController extends Controller
{
    public function index($id)
    {
        return view('update-user', [
            'user' => User::findOrFail($id),
            'roles' => Role::all(),
            'vacationRequests' => VacationRequest::all(),
            'sicknessRequests' => SicknessRequest::all(),

        ]);
    }

    //CURRENTLY NOT USED (For User-Update)
    public function update(Request $request, $id){
        $data = $request->except("_token");

        foreach ($data as $index => $value) {
            if($value !== null){
                DB::table('users')
                    ->where('id', $id)
                    ->update([$index => $value]);
            }
        }

        return redirect('/admin');
    }

    public function destroy($id){
        VacationRequest::where('user_id', $id)->delete();
        SicknessRequest::where('user_id', $id)->delete();
        User::find($id)->delete();
        return redirect('/admin');
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

        return redirect('update-user');
    }
}
