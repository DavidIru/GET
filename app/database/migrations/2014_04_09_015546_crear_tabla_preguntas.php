<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPreguntas extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Preguntas', function($table)
        {
            $table->increments('id');
            
            $table->integer('agrupacion_id')->nullable();
            $table->integer('familia_id')->nullable();
            $table->integer('subfamilia_id')->nullable();
            $table->string('texto', 200);
            $table->boolean('activa')->default(1);
            
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
		Schema::drop('Preguntas');
	}

}
