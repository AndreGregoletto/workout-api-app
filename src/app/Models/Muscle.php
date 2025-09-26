<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    protected $table = 'muscles';

    protected $fillable = [
        'name',
        'description',
        'image',
        'muscle_group_id',
        'status'
    ];
}
