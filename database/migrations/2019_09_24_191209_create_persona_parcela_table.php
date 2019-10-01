<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaParcelaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_parcela', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('persona_id')->unsigned();
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->integer('parcela_id')->unsigned();
            $table->foreign('parcela_id')->references('id')->on('parcelas');
            // $table->integer('autorizacion_id')->unsigned()->nullable();;
            // $table->foreign('autorizacion_id')->references('id')->on('autorizacion_renovaciones');
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
        Schema::dropIfExists('productores_parcelas');
    }
}
