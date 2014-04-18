<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPreguntasEnvio extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('PreguntasEnvio', function($table)
        {
            $table->increments('id');
            
            $table->integer('encuesta_id');
            $table->integer('pregunta_id');
            $table->integer('resultado');
            
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
		Schema::drop('PreguntasEnvio');
	}

}
