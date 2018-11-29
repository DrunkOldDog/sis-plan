<?php

use Illuminate\Database\Seeder;

class ServiciosEspecificoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('servicios_especifico')->insert([
            [
              'id_servicios' => 1,
              'nombre' => 'Almuerzos',
              'precio' => 30
            ],
            [
                'id_servicios' => 1,
                'nombre' => 'Gaseosas',
                'precio' => 10
            ],
            [
                'id_servicios' => 4,
                'nombre' => 'Ron Abuelo 1Lt',
                'precio' => 125
            ],
            [
                'id_servicios' => 4,
                'nombre' => 'Vodka 1825 1.25Lt',
                'precio' => 175
            ],
            [
                'id_servicios' => 4,
                'nombre' => 'Tequila Jose Cuervo 1Lt',
                'precio' => 125
            ]
          ]);
    }
}
