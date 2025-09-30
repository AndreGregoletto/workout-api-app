<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkoutPivotet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workout_id',
        'exercise_id',
        'day_id',
        'ordering',
        'status'
    ];
}
