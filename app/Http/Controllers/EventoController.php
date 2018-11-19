<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        //
        return view('evento.create');
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
        $evento = \App\Evento::find($id);
        return view('evento.edit',compact('evento','id'));
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
        $evento= \App\Evento::find($id);
        $evento->nombre=$request->get('nombre');
        $evento->precio=$request->get('precio');
        $evento->descripcion=$request->get('descripcion');
        $evento->save();
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
        return redirect('eventos')->with('success','Information has been  deleted');
    }
}
