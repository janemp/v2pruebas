<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutorizacionRenovacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizacion_renovaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('informe_adjunto',255)->nullable();;
            $table->string('observaciones',255)->nullable();;            
            $table->boolean('verificacion_destruccion')->nullable();;
            $table->integer('persona_parcela_id')->unsigned()->nullable();;
            $table->foreign('persona_parcela_id')->references('id')->on('persona_parcela');
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
        Schema::dropIfExists('autorizacion_renovaciones');
    }
}
