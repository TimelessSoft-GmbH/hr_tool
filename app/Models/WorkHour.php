<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHour extends Model
{
    use HasFactory;

    protected $table = 'work_hours';

    protected $fillable = [
        'user_id',
        'hours',
        'month',
        'year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
