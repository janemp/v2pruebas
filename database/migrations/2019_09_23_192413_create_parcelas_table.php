<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParcelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcelas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fecha_parcela');
            $table->string('codigo');
            $table->integer('comunidad_id')->unsigned();
            $table->foreign('comunidad_id')->references('id')->on('comunidades');
            $table->string('latitud');
            $table->string('longitud');
            $table->string('descripcion', 500);
            $table->double('hectareas');
            $table->integer('motivo_actualizacion_id')->unsigned()->nullable();
            $table->foreign('motivo_actualizacion_id')->references('id')->on('motivos_actualizacion');
            $table->integer('zona_racionalizada_id')->unsigned();
            $table->foreign('zona_racionalizada_id')->references('id')->on('zona_racionalizadas');
            $table->string('codigo_catastral');
            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regionales');
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
        Schema::dropIfExists('parcelas');
    }
}
