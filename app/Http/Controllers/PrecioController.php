<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Interfaces\PrecioRepositoryInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;


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
        //Ejemplo de hacer un get en una api online
        /*$client = new \GuzzleHttp\Client();
        try {
            $res = $client->get('https://jsonplaceholder.typicode.com/todos/1',array(
          'timeout' => 2, // timeout respuesta
          'connect_timeout' => 2, // timeout conexion
          ));
            echo "Codigo de Estado HTTP: ". $res->getStatusCode() . "<br>"; // 200
            echo "Mensaje del API :" .$res->getBody();
        }
        catch (\Exception $e) {
            echo "no existe";
        }*/

        $servicios = $this->precio->SelectAllServicios();
        $productos = $this->precio->SelectAllProductos();
        $habitaciones = $this->precio->SelectAllHabitaciones();
        $ambientes = $this->precio->SelectAllAmbientes();

        return view('precio.index',compact('servicios','productos','habitaciones','ambientes'));
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
        //insertar datos a habitacion
        $myInputsHab = $request->input('habi');
        $myInputsHabId = $request->input('habiId');
        for($i = 0; $i < count($myInputsHabId); $i++){
            $this->precio->updateHabitaciones($myInputsHabId[$i], $myInputsHab[$i]);
        }

        //insertar datos a ambientes
        $myInputsAmb = $request->input('ambi');
        $myInputsAmbId = $request->input('ambiId');
        for($i = 0; $i < count($myInputsAmbId); $i++){
            $this->precio->updateAmbientes($myInputsAmbId[$i], $myInputsAmb[$i]);
        }

        //insertar datos a servicios
        $myInputsSer = $request->input('serv');
        $myInputsSerId = $request->input('servId');
        for($i = 0; $i < count($myInputsSerId); $i++){
            $this->precio->updateServicios($myInputsSerId[$i], $myInputsSer[$i]);
        }

        //insertar datos a productos
        $myInputsProd = $request->input('prod');
        $myInputsProdId = $request->input('prodId');
        for($i = 0; $i < count($myInputsProdId); $i++){
            $this->precio->updateProductos($myInputsProdId[$i], $myInputsProd[$i]);
        }

        return redirect('precios')->with('success', 'La informacion se edito correctamente.');;
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
