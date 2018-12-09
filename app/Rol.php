<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    //
    protected $table = 'users';

    protected $fillable = [
        'isAdmin'
    ];
}
