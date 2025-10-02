<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'cicle',
        'duration',
        'training_days',
        'no_training_days',
        'date_start',
        'weekend',
        'user_id',
        'status'
    ];
}