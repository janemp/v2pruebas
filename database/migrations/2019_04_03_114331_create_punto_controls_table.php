<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntoControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puestos_de_control', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('municipio_id')->unsigned();
			$table->foreign('municipio_id')->references('id')->on('municipios');
            $table->string('codigo', 5);
            $table->string('nombre', 100);
            $table->string('descripcion', 500);
            $table->double('latitud', 3, 16);
            $table->double('longitud', 3, 16);
            $table->string('direccion', 500);
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
        Schema::dropIfExists('puestos_de_control');
    }
}
