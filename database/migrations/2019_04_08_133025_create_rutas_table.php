<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('municipio_id')->unsigned();
			$table->foreign('municipio_id')->references('id')->on('municipios');
            $table->string('codigo', 5);
            $table->string('nombre', 100);
            $table->string('descripcion', 500);
            $table->integer('origen_id')->unsigned();
            $table->foreign('origen_id')->references('id')->on('puestos_de_control');
            $table->integer('destino_id')->unsigned();
			$table->foreign('destino_id')->references('id')->on('puestos_de_control');
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
        Schema::dropIfExists('rutas');
    }
}
