<?php
namespace App\Interfaces;
 
interface PrecioRepositoryInterface {
	
	public function selectAllAmbientes();
    
    public function selectAllHabitaciones();

    public function selectAllServicios();

    public function selectAllProductos();

    public function updateHabitaciones($id, $precios);
    
    public function updateAmbientes($id, $precios);

    public function updateServicios($id, $precios);

    public function updateProductos($id, $precios);
}
