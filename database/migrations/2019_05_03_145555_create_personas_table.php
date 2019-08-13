<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_persona_id')->unsigned()->nullable();
            $table->foreign('tipo_persona_id')->references('id')->on('tipos_persona');
            $table->string('nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('carnet_identidad');
            $table->integer('departamento_extension_id')->unsigned();
            $table->foreign('departamento_extension_id')->references('id')->on('departamentos');
            $table->string('estado_civil')->nullable();
            $table->string('genero')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('domicilio', 500)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('pais_nacimiento_id')->unsigned()->nullable();
            $table->foreign('pais_nacimiento_id')->references('id')->on('paises');
            $table->integer('departamento_nacimiento_id')->unsigned()->nullable();
            $table->foreign('departamento_nacimiento_id')->references('id')->on('departamentos');
            $table->integer('provincia_nacimiento_id')->unsigned()->nullable();
            $table->foreign('provincia_nacimiento_id')->references('id')->on('provincias');
            $table->string('fotografia')->nullable();
            $table->string('biometrico')->nullable();
            $table->string('carnet_escaneado')->nullable();
            $table->integer('sustitucion_id')->unsigned()->nullable();
            $table->foreign('sustitucion_id')->references('id')->on('sustituciones');
            $table->integer('persona_id')->nullable();
            $table->string('adjunto_sustitucion')->nullable();
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('personas');
    }
}
