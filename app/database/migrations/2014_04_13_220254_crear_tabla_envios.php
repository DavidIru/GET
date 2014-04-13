<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaEnvios extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Envios', function($table)
        {
            $table->increments('id');
            
            $table->integer('pedido_id');
            $table->boolean('entregado')->default(0);
            $table->string('url', 200)->unique();
            $table->boolean('avisar');
            $table->string('telefono', 20);
            
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
		Schema::drop('Envios');
	}

}
