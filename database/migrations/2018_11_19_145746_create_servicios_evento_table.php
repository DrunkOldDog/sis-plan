<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_evento', function (Blueprint $table) {
            $table->increments('id_servicios_evento');
            $table->unsignedInteger('id_servicios');
            $table->foreign('id_servicios')->references('id_servicios')->on('servicios')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_eventos');
            $table->foreign('id_eventos')->references('id_eventos')->on('eventos')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios_evento');
    }
}
