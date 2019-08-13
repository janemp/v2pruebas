<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarnetProductorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carnet_productor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('persona_id')->unsigned();
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->integer('provincia_id')->unsigned();
            $table->foreign('provincia_id')->references('id')->on('provincias');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->integer('federacion_id')->unsigned();
            $table->foreign('federacion_id')->references('id')->on('federaciones');
            $table->integer('regional_id')->unsigned();
            $table->foreign('regional_id')->references('id')->on('regionales');
            $table->integer('central_id')->unsigned();
            $table->foreign('central_id')->references('id')->on('centrales');
            $table->integer('comunidad_id')->unsigned();
            $table->foreign('comunidad_id')->references('id')->on('comunidades');
            $table->string('codigo_socio');
            $table->string('codigo');
            $table->string('carnet');
            $table->double('superficie', 8, 2);
            $table->date('fecha_emision');
            $table->date('fecha_conclusion');
            $table->integer('duplicado')->default(0);
            $table->boolean('renovado')->default(false);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('carnet_productor');
    }
}
