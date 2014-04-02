<?php

class RolesSeeder extends Seeder {
 
    public function run()
    {
    	/*
        $roles = [
            ['rol' => 'Administrador'],
            ['rol' => 'Vendedor'],
            ['rol' => 'Repartidor'],
        ];
 
        DB::table('Roles')->insert($roles);
        */
	    Rol::create(array(
	        'rol'  => 'Administrador'
	    ));

	    Rol::create(array(
	        'rol'  => 'Vendedor'
	    ));

	    Rol::create(array(
	        'rol'  => 'Repartidor'
	    ));
    }
 
}
?>