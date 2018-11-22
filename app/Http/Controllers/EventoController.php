<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $eventos=\App\Evento::paginate(10);
        $eventos = \App\Evento::orderBy('id_eventos')->get();
        return view('evento.index',compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //obtener todos los servicios disponibles
        $servicios = DB::table('servicios')
                 ->select('*')
                 ->orderBy('id_servicios')
                 ->get();
        //obtener todos los ambientes disponibles
        $ambientes = DB::table('ambientes')
                 ->select('*')
                 ->orderBy('id_ambientes')
                 ->get();

        return view('evento.create', compact('servicios', 'ambientes'));
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
        if($request->hasfile('filename'))
         {
            $file = $request->file('filename');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
         }
        $evento= new \App\Evento;
        $evento->nombre=$request->get('nombre');
        $evento->precio=$request->get('precio');
        $evento->descripcion=$request->get('descripcion');
        $evento->foto = $name;
        $evento->save();
        
        //recolectar info de los checkbox y almacenar en la tabla servicios_evento
        $myCheckboxes = $request->input('servi');
        foreach($myCheckboxes as $value){
            DB::table('servicios_evento')->insert(
                array(
                    'id_servicios' => $value,
                    'id_eventos' => $evento->id_eventos
                )
            );
        }
        return redirect('eventos')->with('success', 'Information has been added');
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
        $servicios = DB::table('servicios')
                 ->select('*')
                 ->orderBy('id_servicios')
                 ->get();
        
        $ambientes = DB::table('ambientes')
                 ->select('*')
                 ->orderBy('id_ambientes')
                 ->get();
        
        $evento = \App\Evento::find($id);

        $precios = DB::table('servicios_evento')
                        ->select('servicios.id_servicios')
                        ->join('servicios', 'servicios.id_servicios', '=', 'servicios_evento.id_servicios')
                        ->join('eventos', 'eventos.id_eventos', '=', 'servicios_evento.id_eventos')
                        ->where('eventos.id_eventos', $id)
                        ->orderBy('id_servicios_evento')
                        ->get();
                        $marcados = array();
                        foreach($precios as $precio){
                        array_push($marcados, $precio->id_servicios);
                        }

        return view('evento.edit',compact('evento','id','servicios','marcados','ambientes'));
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
        $evento= \App\Evento::find($id);
        $evento->nombre=$request->get('nombre');
        $evento->precio=$request->get('precio');
        $evento->descripcion=$request->get('descripcion');
        $evento->save();
        
        //recolectar info de los checkbox y almacenar en la tabla servicios_evento
        $myCheckboxes = $request->input('servi');

        DB::table('servicios_evento')
                ->where('id_eventos', $id)
                ->delete();

        foreach($myCheckboxes as $value){
            DB::table('servicios_evento')->insert(
                array(
                    'id_servicios' => $value,
                    'id_eventos' => $evento->id_eventos
                )
            );
        }
        
        return redirect('eventos');
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
        $evento = \App\Evento::find($id);
        $evento->delete();
        DB::table('servicios_evento')
                ->where('id_eventos', $id)
                ->delete();
        return redirect('eventos')->with('success','Information has been  deleted');
    }
}
