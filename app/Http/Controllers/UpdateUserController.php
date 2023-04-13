<?php

namespace App\Http\Controllers;

use App\Models\PastSalary;
use App\Models\SicknessRequest;
use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function update(Request $request, $id){
        $data = $request->except("_token");
        $user = User::findOrFail($id);

        foreach ($data as $index => $value) {
            if ($index === 'hasrole') {
                $this->changeUserRole($id, $value);
            }
            if ($index === 'contract' && $request->hasFile('contract')){
                // store the new file
                $pdf = $request->file('contract');
                $path = $pdf->store('contracts', 'public');
                $new_contract_url = Storage::url($path);

                // delete the old file if it exists
                $old_contract_url = $user->contract;
                if ($old_contract_url && Storage::disk('public')->exists(str_replace('/storage/', '', $old_contract_url))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $old_contract_url));
                }

                DB::table('users')
                    ->where('id', $id)
                    ->update(["contract" => $new_contract_url]);
            }
            if ($index === 'salary' && $value !== $user->salary) {
                // Save new past salary in the past_salaries table
                $pastSalary = new PastSalary;
                $pastSalary->user_id = $id;
                $pastSalary->salary = $value;
                $pastSalary->effective_date = now();
                $pastSalary->save();
            }
            if($value !== null && $index !== "contract"){
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
    public function downloadContract($id)
    {
        $user = User::findOrFail($id);
        $filePath = $user->contract;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            abort(404);
        }
    }
}
