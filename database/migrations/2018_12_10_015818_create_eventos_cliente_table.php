<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_cliente', function (Blueprint $table) {
            $table->increments('id_eventos_cliente');
            $table->unsignedInteger('id_clientes');
            $table->foreign('id_clientes')->references('id_clientes')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('eventos_cliente');
    }
}
