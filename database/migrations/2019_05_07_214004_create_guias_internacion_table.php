<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiasInternacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guias_internacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('persona_id')->unsigned();
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->integer('ruta_id')->unsigned()->nullable();
            $table->foreign('ruta_id')->references('id')->on('rutas');
            $table->string('correlativo');
            $table->string('orden_comunal')->nullable();
            $table->date('fecha_orden_comunal');
            $table->integer('numero_taques');
            $table->integer('libras');
            $table->string('procedencia', 500);
            $table->string('conductor', 500)->nullable();
            $table->string('licencia', 50)->nullable();
            $table->integer('vehiculo_id')->unsigned()->nullable();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos');
            $table->integer('marca_id')->unsigned()->nullable();
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->string('color')->nullable();
            $table->string('numero_placa')->nullable();
            $table->boolean('controlado')->default(false);
            $table->boolean('retenido')->default(false);  
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
        Schema::dropIfExists('guias_internacion');
    }
}
