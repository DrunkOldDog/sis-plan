<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHabitacionesEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitaciones_evento', function (Blueprint $table) {
            $table->increments('id_habitaciones_evento');
            $table->unsignedInteger('id_habitaciones');
            $table->foreign('id_habitaciones')->references('id_habitaciones')->on('habitaciones')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_eventos');
            $table->foreign('id_eventos')->references('id_eventos')->on('eventos')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('cantidad');
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
        Schema::dropIfExists('habitaciones_evento');
    }
}
