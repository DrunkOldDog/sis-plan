<?php
namespace App\Interfaces;
 
interface PrecioRepositoryInterface {
	
	public function selectAllAmbientes();
    
    public function selectAllHabitaciones();

    public function selectAllServicios();

    public function selectAllProductos();

	public function find($id);
	
}
