<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    //
    protected $primaryKey = 'id_servicios';

    protected $fillable=[
        'nombre',
        'precio'
    ];
}
