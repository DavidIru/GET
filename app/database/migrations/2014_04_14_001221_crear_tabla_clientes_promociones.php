<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaClientesPromociones extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ClientesPromociones', function($table)
		{
			$table->increments('id');

			$table->string('telefono', 20)->unique()->nullable();
			$table->string('email', 100)->unique()->nullable();
			$table->string('nombre', 100);
			
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
		Schema::drop('ClientesPromociones');
	}

}
