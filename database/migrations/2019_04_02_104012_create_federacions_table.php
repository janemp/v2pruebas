<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFederacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('federaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('municipio_id')->unsigned();
			$table->foreign('municipio_id')->references('id')->on('municipios');
            $table->string('codigo', 5)->nullable();
            $table->string('nombre', 100);
            $table->string('descripcion', 500)->nullable();
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
        Schema::dropIfExists('federaciones');
    }
}
