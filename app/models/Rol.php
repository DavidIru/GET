<?php 
class Rol extends Eloquent {
	// Tabla con los roles
    protected $table = 'Roles';

    public function usuarios() {
    	return $this->hasMany('Usuario', 'rol_id');
    }
}
?>