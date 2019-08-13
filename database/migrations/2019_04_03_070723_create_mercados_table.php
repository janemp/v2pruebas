<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMercadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('departamento_id')->unsigned();
			$table->foreign('departamento_id')->references('id')->on('departamentos');
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
        Schema::dropIfExists('mercados');
    }
}
