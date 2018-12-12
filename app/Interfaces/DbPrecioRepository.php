<?php
namespace App\Interfaces;
 
use \App\Ambiente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class DbPrecioRepository implements PrecioRepositoryInterface {
	
	  public function selectAllAmbientes()
	  {
		  return \App\Ambiente::orderBy('id_ambientes')->get();
    }
    
    public function selectAllHabitaciones()
	{
     $client = new \GuzzleHttp\Client();
        try {
            $res = $client->get('http://192.168.10.165:8000/api/habitaciones' , array(
              'timeout' => 2, // timeout respuesta
              'connect_timeout' => 2, // timeout conexion
              ));
              $habitaciones = $res->getBody();
        }
        catch (\Exception $e) {
          $habitaciones = DB::table('habitaciones')
          ->select('*')
          ->orderBy('id_habitaciones')
          ->get();
        }
		return $habitaciones;
    }
    
    public function selectAllServicios()
	{
    //verificar si existe la api de servicios para poder extraer datos, si no, usar los datos locales.
    $request = Request::create('/api/servicios');
        $servicios = app()->handle($request);
        if($servicios->status() == 200){
            $request = Request::create('/api/servicios', 'GET');
            $servicios = app()->handle($request);
            $servicios = $servicios->content();
        }else{
          $servicios = DB::table('servicios')
            ->select('*')
            ->orderBy('id_servicios')
            ->get();
        }

		return $servicios;
    }
    
    public function selectAllProductos()
	{
    $client = new \GuzzleHttp\Client();
    try {
        $res = $client->get('http://192.168.10.165:8000/api/productos' , array(
          'timeout' => 2, // timeout respuesta
          'connect_timeout' => 2, // timeout conexion
          ));
          $productos = $res->getBody();
    }
    catch (\Exception $e) {
      $productos = DB::table('productos')
      ->select('*')
      ->orderBy('id_productos')
      ->get();
    }

		return $productos;
	}
	
	public function find($id)
	{
		return Ambiente::find($id);
	}
}