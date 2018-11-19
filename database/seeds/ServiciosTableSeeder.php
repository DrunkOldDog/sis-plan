<?php

use Illuminate\Database\Seeder;

class ServiciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=ServiciosTableSeeder
        DB::table('servicios')->insert([
          [
            'nombre' => 'Alimentos y Bebidas',
            'precio' => 3000
          ],
          [
            'nombre' => 'Anfitrion',
            'precio' => 700
          ],
          [
            'nombre' => 'Decorado',
            'precio' => 500
          ],
          [
            'nombre' => 'Bebidas Alcoholicas',
            'precio' => 2000
          ],
          [
            'nombre' => 'Musica y Parlantes',
            'precio' => 500
          ],
          [
            'nombre' => 'DJ',
            'precio' => 300
          ],
          [
            'nombre' => 'Seguridad',
            'precio' => 600
          ]
        ]);
    }
}
