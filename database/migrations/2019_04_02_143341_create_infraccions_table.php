<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfraccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infracciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 5);
            $table->string('nombre', 100);
            $table->string('descripcion', 500);
            $table->string('severidad', 10);
            $table->boolean('bloqueante')->default(false);
            $table->string('estado',1);
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
        Schema::dropIfExists('infracciones');
    }
}
