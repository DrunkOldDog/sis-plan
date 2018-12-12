<?php
namespace App\Interfaces;
 
use Illuminate\Support\ServiceProvider;
 
class BackendServiceProvider extends ServiceProvider {
	
	public function register()
	{
		$this->app->bind('App\Interfaces\PrecioRepositoryInterface', 'App\Interfaces\DbPrecioRepository');
	}
}