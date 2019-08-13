<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRutaPuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruta_puesto', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ruta_id');
            $table->foreign('ruta_id')->references('id')->on('rutas');
            $table->unsignedInteger('puesto_control_id');
            $table->foreign('puesto_control_id')->references('id')->on('puestos_de_control');
            $table->timestamps();
            $table->softDeletes();
        });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruta_puesto');
    }
}
