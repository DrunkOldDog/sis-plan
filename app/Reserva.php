<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    //
    protected $primaryKey = 'id_reservas';

    protected $fillable=[
        'id_cliente',
        'id_evento',
        'fec_reserva',
        'fec_evento',
        'hor_ini_evento',
        'hor_fin_evento'
    ];
}
