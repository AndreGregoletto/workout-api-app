<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status',
    ];

    public function muscle()
    {
        return $this->belongsTo(Muscle::class);
    }
}
