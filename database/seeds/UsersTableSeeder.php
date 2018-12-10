<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            [
              'name' => 'Juan',
              'last_name' => 'Reyes',
              'username' => 'admin',
              'ci' => '6170152LP',
              'email' => 'rata_reyes1@hotmail.com',
              'password' => Hash::make('admin'),
              'isAdmin' => 1,
            ]
        ]);
        DB::table('clientes')->insert(
            array(
                'nombre' => 'Juan',
                'apellido' => 'Reyes',
                'ci' => '6170152LP'
            )
        );
    }
}
