<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ambientes=\App\Ambiente::paginate(10);
        $ambientes = \App\Ambiente::orderBy('id_ambientes')->get();
        return view('ambiente.index',compact('ambientes'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('ambiente.create');
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
        $ambiente= new \App\Ambiente;
        $ambiente->nombre=$request->get('nombre');
        $ambiente->capacidad=$request->get('capacidad');
        $ambiente->precio=$request->get('precio');
        $ambiente->save();
        
        return redirect('ambientes')->with('success', 'La informacion se almaceno correctamente.');
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
        return \App\Ambiente::where('id_ambientes', $id)->get();
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
        $ambiente = \App\Ambiente::find($id);
        return view('ambiente.edit',compact('ambiente','id'));
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
        $ambiente= \App\Ambiente::find($id);
        $ambiente->nombre=$request->get('nombre');
        $ambiente->capacidad=$request->get('capacidad');
        $ambiente->precio=$request->get('precio');
        $ambiente->save();
        return redirect('ambientes')->with('success', 'La informacion se edito correctamente.');;
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
        $ambiente = \App\Ambiente::find($id);
        $ambiente->delete();
        return redirect('ambientes')->with('success','Se elimino correctamente.');
    }
}
