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
        $reservas = \App\Reserva::orderBy('id_reservas')->get();
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
        $chartRes->dataset('', 'line', $arrayDataset);

        //cantidad de eventos vs cantidad promociones

        return view('dashboard.index',compact('chart', 'chartRes'));
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
