<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ambiente extends Model
{
    //
    protected $primaryKey = 'id_ambientes';

    protected $fillable=[
        'nombre',
        'capacidad',
        'precio'
    ];
}
