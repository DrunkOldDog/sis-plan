<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

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
        $ambientes = DB::table('ambientes')
                 ->select('*')
                 ->orderBy('id_ambientes')
                 ->get(); 
        $eventos=\App\Evento::paginate(10);
        $eventos = \App\Evento::orderBy('id_eventos')->get();
        return view('evento.index',compact('eventos','ambientes'));
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
        //obtener los servicios con servicios especificos
        $serviciosespecificos = DB::table('servicios')
                                ->select('servicios.id_servicios','servicios.nombre')
                                ->join('productos_servicio', 'servicios.id_servicios', '=', 'productos_servicio.id_servicios')
                                ->groupBy('servicios.id_servicios','servicios.nombre')
                                ->get();

        /*//API GET 
        $client = new \GuzzleHttp\Client();
        $res = $client->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
        echo $res->getStatusCode(); // 200
        echo $res->getBody();*/

        return view('evento.create', compact('servicios', 'ambientes', 'serviciosespecificos', 'complementos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|alpha|max:20',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'required',
            'imagen' => 'required|image|max:5000'
        ]);

        if($request->hasfile('imagen'))
         {
            $file = $request->file('imagen');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
         }
        $evento= new \App\Evento;
        $evento->id_ambientes = $request->input('id_ambientes');
        $evento->nombre=$request->get('nombre');
        $evento->precio=$request->get('precio');
        $evento->descripcion=$request->get('descripcion');
        $evento->foto = $name;
        $evento->save();
        
        //recolectar info de los checkbox y almacenar en la tabla servicios_evento
        if($request->input('servicios')){
            $myCheckboxes = $request->input('servicios');
            foreach($myCheckboxes as $value){
                DB::table('servicios_evento')->insert(
                    array(
                        'id_servicios' => $value,
                        'id_eventos' => $evento->id_eventos
                    )
                );
            }
        }

        //recolectar info de los text inputs y almacenar en la tabla servicios_especifico
        $myInputs = $request->input('espe');
        $aux = 0;

        //procedimiento para poder almacenar la cantidad de los productos de acuerdo al evento
        $serviciosespecificos = DB::table('servicios')
                                ->select('servicios.id_servicios','servicios.nombre')
                                ->join('productos_servicio', 'servicios.id_servicios', '=', 'productos_servicio.id_servicios')
                                ->groupBy('servicios.id_servicios','servicios.nombre')
                                ->get();
        foreach($serviciosespecificos as $seresp){
            $complementos = DB::table('productos')
                            ->select('*')
                            ->join('productos_servicio', 'productos.id_productos', '=', 'productos_servicio.id_productos')
                            ->where('productos_servicio.id_servicios', $seresp->id_servicios)
                            ->where('productos.estado', true)
                            ->orderBy('productos.id_productos')
                            ->get();
            //echo "espaciado <br>";
            foreach($complementos as $combi){
                //echo $combi->nombre;
                $getId = DB::table('productos_servicio')
                        ->select('*')
                        ->where('id_servicios', $seresp->id_servicios)
                        ->where('id_productos', $combi->id_productos)
                        ->get();
                //echo 'id ' . $getId[0]->id_productos_servicio . "<br>";
                //echo 'inputs ' . $myInputs[$aux] . "<br>";
                DB::table('productos_evento')->insert(
                    array(
                        'id_eventos' => $evento->id_eventos,
                        'id_productos_servicio' => $getId[0]->id_productos_servicio,
                        'cantidad' => $myInputs[$aux]
                    )
                );
                $aux++;
            }
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
        $evento->id_ambientes = $request->input('id_ambientes');
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
