<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $primaryKey = 'id_eventos';

    protected $fillable=[
        'id_ambientes',
        'nombre',
        'precio',
        'descripcion',
        'foto',
        'fecha_inicio',
        'fecha_fin',
        'descuento'
    ];
}
