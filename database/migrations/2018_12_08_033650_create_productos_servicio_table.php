<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_servicio', function (Blueprint $table) {
            $table->increments('id_productos_servicio');
            $table->unsignedInteger('id_servicios');
            $table->foreign('id_servicios')->references('id_servicios')->on('servicios')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_productos');
            $table->foreign('id_productos')->references('id_productos')->on('productos')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('productos_servicio');
    }
}
