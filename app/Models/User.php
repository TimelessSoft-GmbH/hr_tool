<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hasrole',
        'name',
        'adress',
        'phoneNumber',
        'initials',
        'image',
        'email',
        'salary',
        'password',
        'vacationDays',
        'vacationDays_left',
        'sicknessLeave',
        'start_of_work',
        'workdays',
        'hours_per_week',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pastSalaries()
    {
        return $this->hasMany(PastSalary::class);
    }

    public function vacationRequests()
    {
        return $this->hasMany(VacationRequest::class);
    }

    public function getProfileImageAttribute()
    {
        if ($this->image) {
            return asset('storage/uploads/' . $this->image);
        }

        return null;
    }
}
