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
            ],
            [
                'id_servicios' => 1,
              'id_productos' => 2,
            ],
            [
                'id_servicios' => 4,
              'id_productos' => 3,
            ],
            [
                'id_servicios' => 1,
              'id_productos' => 4,
            ],
            [
                'id_servicios' => 4,
              'id_productos' => 5,
            ]
          ]);
    }
}
