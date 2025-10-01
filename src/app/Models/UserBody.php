<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBody extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'height', // Altura
        'weight', // Peso
        'r_biceps', // Bicipes
        'l_biceps',
        'r_forearm', // antebraço
        'l_forearm', 
        'chest', // peito
        'waist', // cintura
        'pelvic_girdle', // cintura pelvica
        'r_thigh', // coxa
        'l_thigh',
        'r_shin', // canela
        'l_shin',
        'shoulder_length', // comprimento de ombro
        'measurement_date',
        'status',
    ];
}
