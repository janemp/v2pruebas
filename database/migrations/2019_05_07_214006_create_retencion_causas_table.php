<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetencionCausasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retencion_causas', function (Blueprint $table) {
            $table->integer('retencion_id')->unsigned()->nullable();
            $table->foreign('retencion_id')->references('id')->on('retenciones');
            $table->integer('causa_retencion_id')->unsigned()->nullable();
            $table->foreign('causa_retencion_id')->references('id')->on('causas_retencion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retencion_causas');
    }
}
