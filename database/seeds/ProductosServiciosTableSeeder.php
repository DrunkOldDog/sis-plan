<?php

use Illuminate\Database\Seeder;

class ProductosServiciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('productos_servicio')->insert([
            [
              'id_servicios' => 1,
              'id_productos' => 1,
              'cantidad' => 30
            ],
            [
                'id_servicios' => 1,
              'id_productos' => 2,
              'cantidad' => 50
            ],
            [
                'id_servicios' => 4,
              'id_productos' => 3,
              'cantidad' => 50
            ],
            [
                'id_servicios' => 1,
              'id_productos' => 4,
              'cantidad' => 40
            ],
            [
                'id_servicios' => 4,
              'id_productos' => 5,
              'cantidad' => 70
            ]
          ]);
    }
}
