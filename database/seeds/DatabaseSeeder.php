<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AmbientesTableSeeder::class);
        $this->call(ServiciosTableSeeder::class);
        $this->call(ProductosTableSeeder::class);
        $this->call(ProductosServiciosTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
