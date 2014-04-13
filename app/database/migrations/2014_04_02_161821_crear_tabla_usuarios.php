<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaUsuarios extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Usuarios', function($table)
        {
            $table->increments('id');
            
            $table->integer('rol_id');
            $table->string('nombre', 100);
            $table->string('usuario', 50)->unique();
            $table->string('password', 64);
            $table->integer('notificaciones')->default(0);
            
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
		Schema::drop('Usuarios');
	}

}
