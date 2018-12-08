<?php

use Illuminate\Database\Seeder;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('productos')->insert([
            [
              'nombre' => 'Gaseosa',
              'precio' => 10,
              'estado' => true
            ],
            [
                'nombre' => 'Almuerzo',
                'precio' => 25,
                'estado' => true
            ],
            [
                'nombre' => 'Tequila Jose Cuervo 1Lt',
                'precio' => 90,
                'estado' => true
            ],
            [
                'nombre' => 'Desayuno',
                'precio' => 20,
                'estado' => false
            ],
            [
                'nombre' => 'Ron Abuelo 1Lt',
                'precio' => 80,
                'estado' => false
            ]
          ]);
    }
}
