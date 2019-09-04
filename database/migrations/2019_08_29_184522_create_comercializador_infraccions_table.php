<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComercializadorInfraccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comercializador_infraccions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comercializador_id')->unsigned()->nullable();
            $table->foreign('comercializador_id')->references('id')->on('personas');
            $table->integer('infraccion_id')->unsigned()->nullable();
            $table->foreign('infraccion_id')->references('id')->on('infracciones');
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
        Schema::dropIfExists('comercializador_infraccions');
    }
}
