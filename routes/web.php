<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//home page
Route::get('/', ['uses'=>'HomecenterController@index']);       

//vistas
Route::resource('eventos', 'EventoController');
Route::resource('reservas', 'ReservaController');
Route::get('/reservas/create/{id}',[
    'uses' => 'ReservaController@crear', 
    'as' => 'test.route'
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
