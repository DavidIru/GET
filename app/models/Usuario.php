<?php 
class Usuario extends Eloquent {
	// Tabla con los usuarios
    protected $table = 'Usuarios';

    // Campos excluidos del JSON
    protected $hidden = array('password');

    public $timestamps = false;
}
?>