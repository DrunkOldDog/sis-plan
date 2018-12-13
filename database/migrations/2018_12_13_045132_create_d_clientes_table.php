<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_clientes', function (Blueprint $table) {
            $table->increments('idd_clientes');
            $table->string('nombre', 45);
            $table->string('apellido', 45);
            $table->timestamps();
        });

        DB::unprepared('
            create trigger dim_clientes
            after insert on `clientes`
            for each row
            begin
                insert into d_clientes (`id_clientes`, `nombre`, `apellido`) values (`new.id_clientes`,`new.nombre`,`new.apellido`);
            end
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_clientes');
        DB::unprepared('DROP TRIGGER `dim_clientes`');
    }
}
