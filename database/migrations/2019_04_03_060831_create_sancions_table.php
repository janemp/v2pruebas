<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSancionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('infraccion_id')->unsigned();
			$table->foreign('infraccion_id')->references('id')->on('infracciones');
            $table->string('codigo', 5);
            $table->string('nombre', 100);
            $table->string('descripcion', 500);
            $table->decimal('monto');
            $table->boolean('bloqueante')->default(false);
            $table->string('estado',1);
            $table->string('adjunto')->nullable();
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
        Schema::dropIfExists('sanciones');
    }
}
