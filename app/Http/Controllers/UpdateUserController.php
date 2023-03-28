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
        $user = User::findOrFail($id);

        foreach ($data as $index => $value) {
            if ($index === 'hasrole') {
                $this->changeUserRole($id, $value);
            }
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


    function changeUserRole($userId, $newRoleName) {
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
}
