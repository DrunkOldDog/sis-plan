<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->increments('id_eventos');
            $table->unsignedInteger('id_ambientes');
            $table->foreign('id_ambientes')->references('id_ambientes')->on('ambientes')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nombre', 45);
            $table->float('precio');
            $table->float('precio_total')->nullable();
            $table->string('descripcion', 500);
            $table->string('foto', 100);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->decimal('descuento', 2, 2)->nullable();
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
        Schema::dropIfExists('eventos');
    }
}
