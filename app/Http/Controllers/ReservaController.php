<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $eventos = DB::table('eventos')
                    ->select('*')
                    ->orderBy('id_eventos')
                    ->get();
        
        $clientes = DB::table('clientes')
                    ->select('*')
                    ->orderBy('id_clientes')
                    ->get();
                    
        date_default_timezone_set('America/La_Paz');

        $reservas=\App\Reserva::paginate(10);
        if(auth()->user()->isAdmin == 0){
            $reservas = \App\Reserva::where('id_clientes', Auth::id())->orderBy('fec_evento')->get();
        }else{
            if(auth()->user()->isAdmin == 1){
                $reservas = \App\Reserva::orderBy('fec_evento')->get();
            }else{
                $reservas = \App\Reserva::where('fec_evento', '>=', date("Y-m-d"))->orderBy('fec_evento')->get();
            } 
        }
        return view('reserva.index',compact('reservas','eventos','clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $eventos = DB::table('eventos')
                 ->select('*')
                 ->orderBy('id_eventos')
                 ->get();

        return view('reserva.create', compact('eventos'));
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
            'apellido' => 'required|regex:/^[\pL\s\-]+$/u|max:20',
            'ci' => 'required|alpha_num|max:10',
        ]);

        if(auth()->user()->isAdmin != 0){
            DB::table('clientes')->insert(
                array(
                    'nombre' => $request->get('nombre'),
                    'apellido' => $request->get('apellido'),
                    'ci' => $request->get('ci'),
                )
            );
        }

        $cliente = DB::table('clientes')
                    ->select('*')
                    ->where('nombre', $request->get('nombre'))
                    ->get();

        date_default_timezone_set('America/La_Paz');
        $mytime = Carbon\Carbon::now();
        //echo $mytime->toDateTimeString();

        $reserva= new \App\Reserva;
        $reserva->id_clientes=$cliente[0]->id_clientes;
        $reserva->id_eventos=$request->get('id_eventos');
        $reserva->fec_reserva=$mytime->toDateTimeString();
        $reserva->fec_evento=$request->get('fec_evento');
        $reserva->hor_ini_evento=$request->get('hor_ini_evento');
        $reserva->hor_fin_evento=$request->get('hor_fin_evento');

        //parsear el string de horas a tiempo
        $hora_ap = Carbon\Carbon::parse($reserva->hor_ini_evento);
        $hora_ci = Carbon\Carbon::parse($reserva->hor_fin_evento);

        //validaciones
        //ver si reserva esta en rango de promociones
        $promocion = DB::table('eventos')
                    ->select('*')
                    ->where('id_eventos', $reserva->id_eventos)
                    ->get();
        if($promocion[0]->fecha_inicio){
            if($reserva->fec_evento < $promocion[0]->fecha_inicio || $reserva->fec_evento > $promocion[0]->fecha_fin){
                return back()->withInput()->withErrors("La fecha del evento debe estar dentro de los limites de la promocion");
            }
        }
        //hora inicio menor a hora fin
        if($reserva->hor_ini_evento >= $reserva->hor_fin_evento){
            if($hora_ci->hour > 4){
                return back()->withInput()->withErrors("La hora de inicio: " .$reserva->hor_ini_evento. " debe ser menor a la hora fin: " .$reserva->hor_fin_evento. " y la hora fin debe ser menor a las 5am.");
            }
        }
        //hora apertura mayor a 10am
        if($hora_ap->hour < 10){
            return back()->withInput()->withErrors("Los eventos en el hotel empiezan a las 10am.");
        }
        //la reserva debe ser en una hora exacta
        if($hora_ap->minute != 0 || $hora_ci->minute != 0){
            return back()->withInput()->withErrors("Los eventos unicamente funcionan en horas exactas. (Ejemplo 10:00-12:00)");
        }

        //ver si hay reservas en esos ambientes
        $reserChocadas = DB::table('reservas')
                    ->select('*')
                    ->where('fec_evento', $reserva->fec_evento)
                    ->get();
                    
        $ambiReser = DB::table('eventos_ambiente')
                    ->select('*')
                    ->where('id_eventos', $reserva->id_eventos)
                    ->get();

        foreach($reserChocadas as $reser){
            $ambientes = DB::table('eventos_ambiente')
                        ->select('*')
                        ->where('id_eventos',$reser->id_eventos)
                        ->get();
            foreach($ambientes as $ambi){
               foreach($ambiReser as $ambir){
                   if($ambi->id_ambientes == $ambir->id_ambientes){
                       echo $reserva->hor_ini_evento. " es mayorigual a ". $reser->hor_ini_evento . " y ". $reserva->hor_ini_evento . " menor a ". $reser->hor_fin_evento. "<br>";
                       if(strtotime($reserva->hor_ini_evento) >= strtotime($reser->hor_ini_evento) && strtotime($reserva->hor_ini_evento) < strtotime($reser->hor_fin_evento)){
                            return back()->withInput()->withErrors("Ya existe una reserva con algun ambiente del sistema la fecha: " . $reserva->fec_evento . " de: ". $reser->hor_ini_evento . "-". $reser->hor_fin_evento);
                       }else{
                           if(strtotime($reserva->hor_ini_evento) >= strtotime($reser->hor_ini_evento) && $hora_ci->hour < 5){
                                return back()->withInput()->withErrors("Ya existe una reserva con algun ambiente del sistema la fecha: " . $reserva->fec_evento . " de: ". $reser->hor_ini_evento . "-". $reser->hor_fin_evento);
                           }
                       }
                   }
               }
            }
        }
        //echo $ambiReser;
        //$reserva->save();  
        //return redirect('reservas')->with('success', 'Se almaceno la informacion correctamente.');
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
        $clientes = DB::table('clientes')
                    ->select('*')
                    ->orderBy('id_clientes')
                    ->get();
        $eventos = DB::table('eventos')
                    ->select('*')
                    ->orderBy('id_eventos')
                    ->get();

        $reserva = \App\Reserva::find($id);
        return view('reserva.edit',compact('reserva','id','clientes','eventos','ambientes'));
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
            'apellido' => 'required|regex:/^[\pL\s\-]+$/u|max:20',
            'ci' => 'required|alpha_num|max:10',
        ]);
        
        $reserva= \App\Reserva::find($id);
        $reserva->id_eventos=$request->get('id_eventos');
        $reserva->fec_evento=$request->get('fec_evento');
        $reserva->hor_ini_evento=$request->get('hor_ini_evento');
        $reserva->hor_fin_evento=$request->get('hor_fin_evento');
        
        //parsear el string de horas a tiempo
        $hora_ap = Carbon\Carbon::parse($reserva->hor_ini_evento);
        $hora_ci = Carbon\Carbon::parse($reserva->hor_fin_evento);

        //validaciones
        //ver si reserva esta en rango de promociones
        $promocion = DB::table('eventos')
                    ->select('*')
                    ->where('id_eventos', $reserva->id_eventos)
                    ->get();
        if($promocion[0]->fecha_inicio){
            if($reserva->fec_evento < $promocion[0]->fecha_inicio || $reserva->fec_evento > $promocion[0]->fecha_fin){
                return back()->withInput()->withErrors("La fecha del evento debe estar dentro de los limites de la promocion");
            }
        }
        //hora inicio menor a hora fin
        if($reserva->hor_ini_evento >= $reserva->hor_fin_evento){
            if($hora_ci->hour > 4){
                return back()->withInput()->withErrors("La hora de inicio: " .$reserva->hor_ini_evento. " debe ser menor a la hora fin: " .$reserva->hor_fin_evento. " y la hora fin debe ser menor a las 5am.");
            }
        }
        //hora apertura mayor a 10am
        if($hora_ap->hour < 10){
            return back()->withInput()->withErrors("Los eventos en el hotel empiezan a las 10am.");
        }
        //la reserva debe ser en una hora exacta
        if($hora_ap->minute != 0 || $hora_ci->minute != 0){
            return back()->withInput()->withErrors("Los eventos unicamente funcionan en horas exactas. (Ejemplo 10:00-12:00)");
        }

        //ver si hay reservas en esos ambientes
        $reserChocadas = DB::table('reservas')
                    ->select('*')
                    ->where('fec_evento', $reserva->fec_evento)
                    ->get();

        $ambiReser = DB::table('eventos_ambiente')
                    ->select('*')
                    ->where('id_eventos', $reserva->id_eventos)
                    ->get();

        foreach($reserChocadas as $reser){
            $ambientes = DB::table('eventos_ambiente')
                        ->select('*')
                        ->where('id_eventos',$reser->id_eventos)
                        ->get();
            foreach($ambientes as $ambi){
               foreach($ambiReser as $ambir){
                   if($ambi->id_ambientes == $ambir->id_ambientes){
                       //echo $reser->hor_ini_evento. " comparado a ". $reserva->hor_ini_evento . "con hora fin ". $reser->hor_fin_evento . "<br>";
                       if($reserva->hor_ini_evento >= $reser->hor_ini_evento && $reserva->hor_ini_evento < $reser->hor_fin_evento){
                            return back()->withInput()->withErrors("Ya existe una reserva con algun ambiente del sistema la fecha: " . $reserva->fec_evento . " de: ". $reser->hor_ini_evento . "-". $reser->hor_fin_evento);
                       }
                   }
               }
            }
        }
        //echo $ambiReser;
        $reserva->save();  
        return redirect('reservas')->with('success', 'Se edito la informacion correctamente.');
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
        $reserva = \App\Reserva::find($id);
        $reserva->delete();
        return redirect('reservas')->with('success','Se elimino correctamente.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear($id_crear)
    {
        //
        $eventos = DB::table('eventos')
                 ->select('*')
                 ->orderBy('id_eventos')
                 ->get();
        
        $usuario = DB::table('users')
                ->select('*')
                ->where('id', Auth::id())
                ->get();
        
        $incHabitacion = DB::table('habitaciones')
                        ->select('*')
                        ->join('habitaciones_evento', 'habitaciones_evento.id_habitaciones', '=', 'habitaciones.id_habitaciones')
                        ->where('habitaciones_evento.id_eventos', $id_crear)
                        ->where('habitaciones_evento.cantidad', '!=', 0)
                        ->orderBy('habitaciones.id_habitaciones')
                        ->get();

        $incServicio = DB::table('servicios')
                        ->select('*')
                        ->join('servicios_evento', 'servicios_evento.id_servicios', '=', 'servicios.id_servicios')
                        ->where('servicios_evento.id_eventos', $id_crear)
                        ->orderBy('servicios.id_servicios')
                        ->get();

        $incAmbiente = DB::table('ambientes')
                        ->select('*')
                        ->join('eventos_ambiente', 'eventos_ambiente.id_ambientes', '=', 'ambientes.id_ambientes')
                        ->where('eventos_ambiente.id_eventos', $id_crear)
                        ->orderBy('ambientes.id_ambientes')
                        ->get();

        $incProducto = DB::table('productos')
                        ->select('*')
                        ->join('productos_servicio', 'productos_servicio.id_productos', '=', 'productos.id_productos')
                        ->join('productos_evento', 'productos_evento.id_productos_servicio', '=', 'productos_servicio.id_productos_servicio')
                        ->where('productos_evento.id_eventos', $id_crear)
                        ->where('productos_evento.cantidad', '!=', 0)
                        ->orderBy('productos.id_productos')
                        ->get();

        return view('reserva.create', compact('eventos','id_crear','usuario','incAmbiente','incHabitacion','incServicio','incProducto'));
    }
}
