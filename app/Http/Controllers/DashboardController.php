<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Charts\DashboardChart;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //ambientes por precio
        $ambientes = \App\Ambiente::orderBy('precio')->get();
        $arrayLabels = array();
        $arrayDataset = array();
        
        foreach($ambientes as $ambiente){
            array_push($arrayLabels, $ambiente->nombre);
            array_push($arrayDataset, $ambiente->precio);
        }

        $chart = new DashboardChart('bar', 'minimalist');
        $chart->height(300);
        $chart->title("Reporte Precios Ambientes");
        $chart->labels($arrayLabels);
        $chart->dataset('', 'bar', $arrayDataset);

        //reservas por dia
        $reservas = \App\Reserva::orderBy('fec_evento')->get();
        $arrayLabels = array();
        $arrayDataset = array();
        //echo $reservas;
        foreach($reservas as $reserva){
            array_push($arrayLabels, $reserva->fec_evento);
        }

        $counts = array_count_values($arrayLabels);
        $arrayLabels = array_values(array_unique($arrayLabels));

        for($i=0; $i < count($arrayLabels); $i++){
            array_push($arrayDataset, $counts[$arrayLabels[$i]]);
        }

        $chartRes = new DashboardChart('line', 'resperday');
        $chartRes->height(300);
        $chartRes->title("Reporte Reservas por Dia");
        $chartRes->labels($arrayLabels);
        $chartRes->dataset('', 'line', $arrayDataset)->color('#7a7a52')->backgroundColor('#b8b894');

        //cantidad de eventos vs cantidad promociones
        $eventos = \App\Evento::orderBy('id_eventos')->whereNull('fecha_inicio')->whereNull('estado')->get();
        $promociones = \App\Evento::orderBy('id_eventos')->whereNull('estado')->whereNotNull('fecha_inicio')->get();

        $chartEve = new DashboardChart('doughnut', 'resperday');
        $chartEve->height(300);
        $chartEve->title("Eventos vs Promociones");
        $chartEve->labels(["Eventos","Promociones"]);
        $chartEve->dataset('Evento', 'doughnut', [count($eventos),count($promociones)])->color(['#ff3333','#3366ff'])->backgroundColor(['#ff6666','#668cff']);

        //eventos mas reservados por clientes
        $reserEve = \App\Reserva::orderBy('id_eventos')->get();
        $arrayLabels = array();
        $arrayDataset = array();
        $arrayNames = array();

        foreach($reserEve as $res){
            //echo $res->id_eventos;
            array_push($arrayLabels, $res->id_eventos);
        }

        $counts = array_count_values($arrayLabels);
        $arrayLabels = array_values(array_unique($arrayLabels));

        for($i=0; $i < count($arrayLabels); $i++){
            array_push($arrayDataset, $counts[$arrayLabels[$i]]);
            $event = \App\Evento::where('id_eventos', $arrayLabels[$i])->orderBy('id_eventos')->get();
            array_push($arrayNames, $event[0]->nombre);
        }
        //dd($arrayDataset);
        $chartReq = new DashboardChart('pie', 'eveReq');
        $chartReq->height(300);
        $chartReq->title("Eventos Populares");
        $chartReq->labels($arrayNames);
        $chartReq->dataset('', 'pie', $arrayDataset)->backgroundColor(['#5cd65c','#aa80ff','#4dffd2','#d2a679','#80ffd4','#85a3e0','#ffff4d']);

        return view('dashboard.index',compact('chart', 'chartRes', 'chartEve', 'chartReq'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
