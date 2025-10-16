<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayException extends Model
{
    protected $fillable = [
        'went_workout',
        'date',
        'user_id',
        'workout_id',
        'status',
    ];
}
