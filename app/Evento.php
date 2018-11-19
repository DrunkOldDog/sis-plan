<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $primaryKey = 'id_eventos';

    protected $fillable=[
        'nombre',
        'precio',
        'descripcion',
        'foto'
    ];
}
