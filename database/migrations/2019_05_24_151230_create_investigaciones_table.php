<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestigacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('decomiso_id')->unsigned()->nullable();
            $table->foreign('decomiso_id')->references('id')->on('decomisos');
            $table->integer('resolucion_administrativa_id')->unsigned()->nullable();
            $table->foreign('resolucion_administrativa_id')->references('id')->on('resoluciones_administrativas');
            $table->integer('entidad_investigacion_id')->unsigned()->nullable();
            $table->foreign('entidad_investigacion_id')->references('id')->on('entidades_investigacion');            
            $table->integer('hoja_ruta_id')->unsigned()->nullable();
            $table->foreign('hoja_ruta_id')->references('id')->on('hoja_rutas');
            $table->integer('numero_taques');
            $table->string('destino');
            $table->text('observaciones');
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
        Schema::dropIfExists('investigaciones');
    }
}
