<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosAmbienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_ambiente', function (Blueprint $table) {
            $table->increments('id_eventos_ambiente');
            $table->unsignedInteger('id_ambientes');
            $table->foreign('id_ambientes')->references('id_ambientes')->on('ambientes')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('eventos_ambiente');
    }
}
