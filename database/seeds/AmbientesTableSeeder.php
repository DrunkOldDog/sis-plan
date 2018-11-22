<?php

use Illuminate\Database\Seeder;

class AmbientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('ambientes')->insert([
            [
              'nombre' => 'Salon de Eventos',
              'capacidad' => 500,
              'precio' => 2000
            ],
            [
              'nombre' => 'Patio Posterior',
              'capacidad' => 300,
              'precio' => 3000
            ],
            [
              'nombre' => 'Sala Presidencial',
              'capacidad' => 150,
              'precio' => 4000
            ],
            [
              'nombre' => 'Sala con Piscina',
              'capacidad' => 100,
              'precio' => 1500
            ],
            [
              'nombre' => 'Comedor',
              'capacidad' => 200,
              'precio' => 1250
            ]
          ]);
    }
}
