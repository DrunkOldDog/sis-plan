<?php
namespace App\Interfaces;
 
use \App\Ambiente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;

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
        $request = Request::create('/api/servicios' , 'GET');
        if(Route::dispatch($request)->status() == 200){
            $servicios = Route::dispatch($request);
            $servicios = $servicios->getContent();
            $servicios = json_decode($servicios);
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
	
	public function updateHabitaciones($id, $precio)
	{
        DB::table('habitaciones')
            ->where('id_habitaciones', $id)
            ->update(['precio' => $precio]);
    }
    
    public function updateAmbientes($id, $precio)
	{
        $ambiente= \App\Ambiente::find($id);
        $ambiente->precio = $precio;
        $ambiente->save();
    }
    
    public function updateServicios($id, $precio)
	{
		DB::table('servicios')
            ->where('id_servicios', $id)
            ->update(['precio' => $precio]);
    }
    
    public function updateProductos($id, $precio)
	{
		DB::table('productos')
            ->where('id_productos', $id)
            ->update(['precio' => $precio]);
	}
}