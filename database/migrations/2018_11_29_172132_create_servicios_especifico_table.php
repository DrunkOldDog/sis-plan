<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosEspecificoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_especifico', function (Blueprint $table) {
            $table->increments('id_servicios_especifico');
            $table->unsignedInteger('id_servicios');
            $table->foreign('id_servicios')->references('id_servicios')->on('servicios')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nombre', 45);
            $table->float('precio');
            $table->integer('cantidad')->nullable();
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
        Schema::dropIfExists('servicios_especifico');
    }
}
