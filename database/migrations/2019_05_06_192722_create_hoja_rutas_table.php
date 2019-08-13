<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHojaRutasTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
       Schema::create('hoja_rutas', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('user_id')->unsigned();
           $table->foreign('user_id')->references('id')->on('users');
           $table->integer('persona_id')->unsigned();
           $table->foreign('persona_id')->references('id')->on('personas');
           $table->string('codigo_comercializador', 100);
           // $table->string('direccion_puesto', 500);
           $table->unsignedInteger('puesto_venta_id');
           $table->foreign('puesto_venta_id')->references('id')->on('puestos_venta');
           $table->integer('taques');
           $table->date('fecha_inicio');
           $table->date('fecha_final');
           $table->unsignedInteger('procedencia_id');
           $table->foreign('procedencia_id')->references('id')->on('departamentos');
           $table->unsignedInteger('destino_id');
           $table->foreign('destino_id')->references('id')->on('departamentos');
           $table->unsignedInteger('localidad_id');
           $table->foreign('localidad_id')->references('id')->on('localidades');
           $table->string('destino_coca', 500);
           $table->unsignedInteger('vehiculo_id')->nullable();
           $table->foreign('vehiculo_id')->references('id')->on('vehiculos');
           $table->unsignedInteger('marca_id')->nullable();
           $table->foreign('marca_id')->references('id')->on('marcas');
           $table->string('numero_placa', 15)->nullable();
           $table->string('color')->nullable();
           $table->string('nombre_conductor', 255)->nullable();
           $table->unsignedInteger('ruta_id');
           $table->foreign('ruta_id')->references('id')->on('rutas');
           $table->boolean('estado')->default(false);
           $table->string('correlativo');
           $table->boolean('control')->default(false);
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
       Schema::dropIfExists('hoja_rutas');
   }
}