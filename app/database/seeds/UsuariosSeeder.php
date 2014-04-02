<?php

class UsuariosSeeder extends Seeder {
 
    public function run()
    {
        /*
        $usuarios = [
            ['rol_id' => 1, 'nombre' => 'David Iruzubieta', 'usuario' => 'admin', 'password' => Hash::make('prueba')]
        ];
 
        DB::table('Usuarios')->insert($usuarios);
        */
        Usuario::create(array(
            'rol_id'  => 1,
            'nombre'     => 'David Iruzubieta',
            'usuario'=> 'admin',
            'password' => Hash::make('admin') // Hash::make() nos va generar una cadena con nuestra contraseña encriptada
        ));
    }
}
?>