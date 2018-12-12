<?php
namespace App\Interfaces;
 
use \App\Ambiente;
use Illuminate\Support\Facades\DB;

class DbPrecioRepository implements PrecioRepositoryInterface {
	
	public function selectAllAmbientes()
	{
		return Ambiente::all();
    }
    
    public function selectAllHabitaciones()
	{
        $habitaciones = DB::table('habitaciones')
                        ->select('*')
                        ->orderBy('id_habitaciones')
                        ->get();
		return $habitaciones;
    }
    
    public function selectAllServicios()
	{
		$servicios = DB::table('servicios')
                        ->select('*')
                        ->orderBy('id_servicios')
                        ->get();
		return $servicios;
    }
    
    public function selectAllProductos()
	{
		$productos = DB::table('productos')
                        ->select('*')
                        ->orderBy('id_productos')
                        ->get();
		return $productos;
	}
	
	public function find($id)
	{
		return Ambiente::find($id);
	}
}