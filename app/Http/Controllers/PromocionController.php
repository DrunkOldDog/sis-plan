<?php

namespace App\Http\Controllers;

use App\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $eventos=\App\Promocion::paginate(10);
       $eventos = \App\Promocion::whereNotNull('fecha_inicio')->whereNull('estado')->orderBy('id_eventos')->get();
       return view('promocion.index',compact('eventos'));
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
        //obtener todos las habitaciones disponibles
        $habitaciones = DB::table('habitaciones')
                 ->select('*')
                 ->orderBy('id_habitaciones')
                 ->get();
        //obtener los servicios con servicios especificos
        $serviciosespecificos = DB::table('servicios')
            ->select('servicios.id_servicios','servicios.nombre')
            ->join('productos_servicio', 'servicios.id_servicios', '=', 'productos_servicio.id_servicios')
            ->groupBy('servicios.id_servicios','servicios.nombre')
            ->get();

        return view('promocion.create', compact('servicios', 'ambientes', 'serviciosespecificos', 'habitaciones'));
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
        $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u|max:20',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'required',
            'imagen' => 'required|image|max:5000',
            'descuento' => 'required|numeric|between:0, 1',
            'ambients' => 'required'
        ]);
        if($request->hasfile('imagen'))
         {
            $file = $request->file('imagen');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
         }
        $evento= new \App\Evento;
        $evento->nombre=$request->get('nombre');
        $evento->precio=$request->get('precio');
        $evento->descripcion=$request->get('descripcion');
        $evento->foto = $name;
        $evento->fecha_inicio = $request->get('fecha_inicio');
        $evento->fecha_fin = $request->get('fecha_fin');
        $evento->descuento = $request->get('descuento');
        if($request->get('fecha_inicio') >= $request->get('fecha_fin')){
            return back()->withInput()->withErrors("La fecha de inicio debe ser menor a fecha fin");
        }
        $evento->save();

        //recolectar info de los checkbox y almacenar en la tabla eventos_ambiente
        if($request->input('ambients')){
            $myCheckboxesAmbi = $request->input('ambients');
            foreach($myCheckboxesAmbi as $valueAmbi){
                DB::table('eventos_ambiente')->insert(
                    array(
                        'id_ambientes' => $valueAmbi,
                        'id_eventos' => $evento->id_eventos
                    )
                );
            }
        }

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
        $total = 0;
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
                //encontrar la cantidad de productos mas sus precios para hallar el total
                $total = $total + $myInputs[$aux]*$combi->precio;
                $aux++;
            }
        }

        //insertar datos a evento_habitacion
        $myInputsHab = $request->input('habi');
        $auxHab = 0;
        $totalHab = 0;
        $habitaciones = DB::table('habitaciones')
                        ->select('*')
                        ->orderBy('id_habitaciones')
                        ->get();
                        
        foreach($habitaciones as $habitacion){
                DB::table('habitaciones_evento')->insert(
                    array(
                        'id_habitaciones' => $habitacion->id_habitaciones,
                        'id_eventos' => $evento->id_eventos,
                        'cantidad' => $myInputsHab[$auxHab],
                    )
                );
            $totalHab = $totalHab + $myInputsHab[$auxHab]*$habitacion->precio;
            $auxHab++; 
        }

        //proceso para sacar un total final
        $ambientes = DB::table('eventos_ambiente')
                ->select('ambientes.precio')
                ->join('ambientes', 'ambientes.id_ambientes', '=', 'eventos_ambiente.id_ambientes')
                ->join('eventos', 'eventos.id_eventos', '=', 'eventos_ambiente.id_eventos')
                ->where('eventos.id_eventos', $evento->id_eventos)
                ->orderBy('id_eventos_ambiente')
                ->get();
        $precios = DB::table('servicios_evento')
            ->select('servicios.precio')
            ->join('servicios', 'servicios.id_servicios', '=', 'servicios_evento.id_servicios')
            ->join('eventos', 'eventos.id_eventos', '=', 'servicios_evento.id_eventos')
            ->where('eventos.id_eventos', $evento->id_eventos)
            ->orderBy('id_servicios_evento')
            ->get();
        $replik = 0;
        foreach($ambientes as $ambiente){
            $replik+= $ambiente->precio;
        }    
        $sum = 0;
        foreach($precios as $precio){
        $sum+= $precio->precio;
        }  

        $price = \App\Evento::find($evento->id_eventos);
        $price->precio_total = $total+$totalHab+$sum+$replik+$evento->precio - ($total+$totalHab+$sum+$replik+$evento->precio)*$evento->descuento;
        $price->save();
        
        return redirect('promociones')->with('success', 'La informacion se agrego correctamente.');
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
        //obtener todos las habitaciones disponibles
        $habitaciones = DB::table('habitaciones')
                ->select('*')
                ->orderBy('id_habitaciones')
                ->get();
       //obtener los servicios con servicios especificos
       $serviciosespecificos = DB::table('servicios')
                ->select('servicios.id_servicios','servicios.nombre')
                ->join('productos_servicio', 'servicios.id_servicios', '=', 'productos_servicio.id_servicios')
                ->groupBy('servicios.id_servicios','servicios.nombre')
                ->get();

       $evento = \App\Evento::find($id);

       //obtener los checkboxes marcados
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

        //obtener los checkboxes marcados
        $preciosAmbi = DB::table('eventos_ambiente')
                        ->select('ambientes.id_ambientes')
                        ->join('ambientes', 'ambientes.id_ambientes', '=', 'eventos_ambiente.id_ambientes')
                        ->join('eventos', 'eventos.id_eventos', '=', 'eventos_ambiente.id_eventos')
                        ->where('eventos.id_eventos', $id)
                        ->orderBy('id_eventos_ambiente')
                        ->get();
                        $marcadosAmbi = array();
                        foreach($preciosAmbi as $precioAmbi){
                        array_push($marcadosAmbi, $precioAmbi->id_ambientes);
                        }

       return view('promocion.edit',compact('evento','id','servicios','marcados','marcadosAmbi','ambientes','serviciosespecificos', 'habitaciones'));
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
        $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u|max:20',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'required',
            'imagen' => 'nullable|image|max:5000',
            'descuento' => 'required|numeric|between:0, 1',
            'ambients' => 'required'
        ]);

        $evento= \App\Evento::find($id);
        $evento->nombre=$request->get('nombre');
        $evento->precio=$request->get('precio');
        $evento->descripcion=$request->get('descripcion');
        if($request->hasfile('imagen'))
        {
           $file = $request->file('imagen');
           $name=$file->getClientOriginalName();
           $file->move(public_path().'/images/', $name);
           $evento->foto = $name;
        }
        $evento->fecha_inicio = $request->get('fecha_inicio');
        $evento->fecha_fin = $request->get('fecha_fin');
        $evento->descuento = $request->get('descuento');
        if($request->get('fecha_inicio') >= $request->get('fecha_fin')){
            return back()->withInput()->withErrors("La fecha de inicio debe ser menor a fecha fin");
        }
        $evento->save();
        
        //recolectar info de los checkbox y almacenar en la tabla eventos_ambiente
        if($request->input('ambients')){
            $myCheckboxesAmbi = $request->input('ambients');
    
            DB::table('eventos_ambiente')
                    ->where('id_eventos', $id)
                    ->delete();
    
            foreach($myCheckboxesAmbi as $valueAmbi){
                DB::table('eventos_ambiente')->insert(
                    array(
                        'id_ambientes' => $valueAmbi,
                        'id_eventos' => $evento->id_eventos
                    )
                );
            }
        }else{
            DB::table('eventos_ambiente')
                    ->where('id_eventos', $id)
                    ->delete();
        }

        //recolectar info de los checkbox y almacenar en la tabla servicios_evento
        if($request->input('servi')){
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
        }else{
            DB::table('servicios_evento')
                ->where('id_eventos', $id)
                ->delete();
        }

        //recolectar info de los text inputs y almacenar en la tabla servicios_especifico
        $myInputs = $request->input('espe');
        $aux = 0;
        $total = 0;
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
                DB::table('productos_evento')
                    ->where('id_eventos', $evento->id_eventos)
                    ->where('id_productos_servicio', $getId[0]->id_productos_servicio)
                    ->update(['cantidad' => $myInputs[$aux]]);
                //encontrar la cantidad de productos mas sus precios para hallar el total
                $total = $total + $myInputs[$aux]*$combi->precio ;
                $aux++;
            }
        }

         //insertar datos a evento_habitacion
         $myInputsHab = $request->input('habi');
         $auxHab = 0;
         $totalHab = 0;
         $habitaciones = DB::table('habitaciones')
                         ->select('*')
                         ->orderBy('id_habitaciones')
                         ->get();
                         
         foreach($habitaciones as $habitacion){
             DB::table('habitaciones_evento')
                 ->where('id_habitaciones', $habitacion->id_habitaciones)
                 ->where('id_eventos', $evento->id_eventos)
                 ->update(['cantidad' => $myInputsHab[$auxHab]]);
             $totalHab = $totalHab + $myInputsHab[$auxHab]*$habitacion->precio;
             $auxHab++; 
         }

        //proceso para sacar un total final
        $ambientes = DB::table('eventos_ambiente')
                ->select('ambientes.precio')
                ->join('ambientes', 'ambientes.id_ambientes', '=', 'eventos_ambiente.id_ambientes')
                ->join('eventos', 'eventos.id_eventos', '=', 'eventos_ambiente.id_eventos')
                ->where('eventos.id_eventos', $evento->id_eventos)
                ->orderBy('id_eventos_ambiente')
                ->get();
        $precios = DB::table('servicios_evento')
            ->select('servicios.precio')
            ->join('servicios', 'servicios.id_servicios', '=', 'servicios_evento.id_servicios')
            ->join('eventos', 'eventos.id_eventos', '=', 'servicios_evento.id_eventos')
            ->where('eventos.id_eventos', $evento->id_eventos)
            ->orderBy('id_servicios_evento')
            ->get();
        $replik = 0;
        foreach($ambientes as $ambiente){
            $replik+= $ambiente->precio;
        }    
        $sum = 0;
        foreach($precios as $precio){
        $sum+= $precio->precio;
        } 

        $price = \App\Evento::find($evento->id_eventos);
        $price->precio_total = $total+$totalHab+$sum+$replik+$evento->precio - ($total+$totalHab+$sum+$replik+$evento->precio)*$evento->descuento;
        $price->save();

        return redirect('promociones')->with('success', 'La informacion se edito correctamente.');;
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
        $promocion = \App\Promocion::find($id);
        $promocion->delete();
        DB::table('servicios_evento')
                ->where('id_eventos', $id)
                ->delete();
        DB::table('productos_evento')
                ->where('id_eventos', $id)
                ->delete();
        DB::table('habitaciones_evento')
                ->where('id_eventos', $id)
                ->delete();
        return redirect('promociones')->with('success','Se elimino correctamente.');
    }
}
