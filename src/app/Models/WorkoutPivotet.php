<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Exercise;
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

    public function exercise()
    {
        return $this->hasOne(Exercise::class, 'id', 'exercise_id');
    }
}
