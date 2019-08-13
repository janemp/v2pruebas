<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncineracionAdjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incineracion_adjuntos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('incineracion_id')->unsigned();
            $table->foreign('incineracion_id')->references('id')->on('incineraciones');
            $table->string('nombre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incineracion_adjuntos');
    }
}
