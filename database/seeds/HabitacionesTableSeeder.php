<?php

use Illuminate\Database\Seeder;

class HabitacionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('habitaciones')->insert([
            [
              'nombre' => 'Simple',
              'precio' => 200,
            ],
            [
                'nombre' => 'Doble',
                'precio' => 350,
            ],
            [
                'nombre' => 'Matrimonial',
                'precio' => 400,
            ],
            [
                'nombre' => 'Triple',
                'precio' => 450,
            ],
            [
                'nombre' => 'Premium',
                'precio' => 650,
            ]
          ]);
    }
}
