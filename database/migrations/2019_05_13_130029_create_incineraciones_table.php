<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncineracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incineraciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('resolucion_administrativa_id')->unsigned()->nullable();
            $table->foreign('resolucion_administrativa_id')->references('id')->on('resoluciones_administrativas');
            $table->integer('motivo_incineracion_id')->unsigned()->nullable();
            $table->foreign('motivo_incineracion_id')->references('id')->on('motivos_incineracion');
            $table->integer('numero_taques');
            $table->string('lugar');
            $table->date('plazo');
            $table->string('destino');
            $table->string('autorizacion');
            $table->string('transporte');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('incineraciones');
    }
}
