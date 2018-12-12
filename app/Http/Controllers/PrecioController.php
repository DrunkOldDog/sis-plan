<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PrecioRepositoryInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class PrecioController extends Controller
{
    public function __construct(PrecioRepositoryInterface $precio)
	{
		$this->precio = $precio;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //API GET 
        $client = new \GuzzleHttp\Client();
        $res = $client->get('http://httpbin.org/get');
        $request = Request::create('http://localhost:7000/api/ambientes/1', 'GET');
        $response = Laracurl::get('http://localhost:7000/api/ambientes/1');
        echo $res->getStatusCode(); // 200
        echo $res->getBody();
        echo $request . "<br>";
        echo $response;

        $productos = $this->precio->SelectAllProductos();
        $servicios = $this->precio->SelectAllServicios();
        $habitaciones = $this->precio->SelectAllHabitaciones();
        $ambientes = $this->precio->SelectAllAmbientes();
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
