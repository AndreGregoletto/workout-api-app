<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Muscle;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'video_url',
        'user_id',
        'workout_id',
        'muscle_id',
        'muscle_group_id',
        'status',
    ];

    public function muscle()
    {
        return $this->hasOne(Muscle::class, 'id', 'muscle_id');
    }
    
    public function muscleGroup()
    {
        return $this->hasOne(MuscleGroup::class, 'id', 'muscle_group_id');
    }
}
