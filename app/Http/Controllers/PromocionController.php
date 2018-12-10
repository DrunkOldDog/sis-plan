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
        $ambientes = DB::table('ambientes')
                ->select('*')
                ->orderBy('id_ambientes')
                ->get(); 
       $eventos=\App\Promocion::paginate(10);
       $eventos = \App\Promocion::whereNotNull('fecha_inicio')->whereNull('estado')->orderBy('id_eventos')->get();
       return view('promocion.index',compact('eventos','ambientes'));
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

        return view('promocion.create', compact('servicios', 'ambientes', 'serviciosespecificos', 'complementos'));
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
            'descuento' => 'required|numeric|between:0, 1' 
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
        $evento->fecha_inicio = $request->get('fecha_inicio');
        $evento->fecha_fin = $request->get('fecha_fin');
        $evento->descuento = $request->get('descuento');
        if($request->get('fecha_inicio') >= $request->get('fecha_fin')){
            return back()->withInput()->withErrors("La fecha de inicio debe ser menor a fecha fin");
        }
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

        //proceso para sacar un total final
        $ambientes = DB::table('ambientes')
            ->select('*')
            ->orderBy('id_ambientes')
            ->get(); 
        $precios = DB::table('servicios_evento')
            ->select('servicios.precio')
            ->join('servicios', 'servicios.id_servicios', '=', 'servicios_evento.id_servicios')
            ->join('eventos', 'eventos.id_eventos', '=', 'servicios_evento.id_eventos')
            ->where('eventos.id_eventos', $evento->id_eventos)
            ->orderBy('id_servicios_evento')
            ->get();
        $replik = 0;
        foreach ($ambientes as $ambiente) {
            if($ambiente->id_ambientes == $evento->id_ambientes){
                $replik = $ambiente->precio;
            }
        }
        $sum = 0;
        foreach($precios as $precio){
        $sum+= $precio->precio;
        }    
        $price = \App\Evento::find($evento->id_eventos);
        $price->precio_total = $total+$sum+$replik+$evento->precio - ($total+$sum+$replik+$evento->precio)*$evento->descuento;
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

       return view('promocion.edit',compact('evento','id','servicios','marcados','ambientes','serviciosespecificos'));
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
            'descuento' => 'required|numeric|between:0, 1' 
        ]);

        $evento= \App\Evento::find($id);
        $evento->id_ambientes = $request->input('id_ambientes');
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

        //proceso para sacar un total final
        $ambientes = DB::table('ambientes')
            ->select('*')
            ->orderBy('id_ambientes')
            ->get(); 
        $precios = DB::table('servicios_evento')
            ->select('servicios.precio')
            ->join('servicios', 'servicios.id_servicios', '=', 'servicios_evento.id_servicios')
            ->join('eventos', 'eventos.id_eventos', '=', 'servicios_evento.id_eventos')
            ->where('eventos.id_eventos', $evento->id_eventos)
            ->orderBy('id_servicios_evento')
            ->get();
        $replik = 0;
        foreach ($ambientes as $ambiente) {
            if($ambiente->id_ambientes == $evento->id_ambientes){
                $replik = $ambiente->precio;
            }
        }
        $sum = 0;
        foreach($precios as $precio){
        $sum+= $precio->precio;
        }    
        $price = \App\Evento::find($evento->id_eventos);
        $price->precio_total = $total+$sum+$replik+$evento->precio - ($total+$sum+$replik+$evento->precio)*$evento->descuento;
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
        return redirect('promociones')->with('success','Se elimino correctamente.');
    }
}
