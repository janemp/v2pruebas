<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetencionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retenciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('persona_id')->unsigned()->nullable();
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->integer('guia_internacion_id')->unsigned()->nullable();
            $table->foreign('guia_internacion_id')->references('id')->on('guias_internacion');
            $table->integer('hoja_ruta_id')->unsigned()->nullable();
            $table->foreign('hoja_ruta_id')->references('id')->on('hoja_rutas');
            $table->string('correlativo');
            $table->string('inicial_embalaje', 15)->nullable();
            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->integer('provincia_id')->unsigned();
            $table->foreign('provincia_id')->references('id')->on('provincias');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->integer('localidad_id')->unsigned();
            $table->foreign('localidad_id')->references('id')->on('localidades');
            $table->string('lugar', 500);
            $table->text('descripcion');
            $table->integer('numero_taques');
            $table->integer('libras');
            
            $table->string('numero_placa')->nullable();
            $table->integer('marca_id')->unsigned()->nullable();
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->integer('vehiculo_id')->unsigned()->nullable();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos');

            $table->string('numero_vuelo')->nullable();
            $table->string('empresa_vuelo')->nullable();
            $table->string('otros_vuelo')->nullable();

            $table->string('nombre_embarcacion')->nullable();
            $table->integer('embarcacion_id')->unsigned()->nullable();
            $table->foreign('embarcacion_id')->references('id')->on('embarcaciones');
            $table->string('numero_embarcacion')->nullable();

            $table->enum('responsable', ['GECC', 'DIGCOIN']);
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('retenciones');
    }
}
