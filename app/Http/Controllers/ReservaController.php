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

        $reservas=\App\Reserva::paginate(10);
        $reservas = \App\Reserva::orderBy('fec_evento')->get();
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

        DB::table('clientes')->insert(
            array(
                'nombre' => $request->get('nombre'),
                'apellido' => $request->get('apellido'),
                'ci' => $request->get('ci')
            )
        );

        $cliente = DB::table('clientes')
                    ->select('*')
                    ->where('nombre', $request->get('nombre'))
                    ->get();

        date_default_timezone_set('America/La_Paz');
        $mytime = Carbon\Carbon::now();
        echo $mytime->toDateTimeString();

        $reserva= new \App\Reserva;
        $reserva->id_clientes=$cliente[0]->id_clientes;
        $reserva->id_eventos=$request->get('id_eventos');
        $reserva->fec_reserva=$mytime->toDateTimeString();
        $reserva->fec_evento=$request->get('fec_evento');
        $reserva->hor_ini_evento=$request->get('hor_ini_evento');
        $reserva->hor_fin_evento=$request->get('hor_fin_evento');
        $reserva->save();
        
        return redirect('reservas')->with('success', 'Se almaceno la informacion correctamente.');
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
                
        return view('reserva.create', compact('eventos','id_crear','usuario'));
    }
}
