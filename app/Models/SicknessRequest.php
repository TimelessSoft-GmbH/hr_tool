<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SicknessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'total_days',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
