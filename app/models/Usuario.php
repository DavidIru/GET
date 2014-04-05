<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Usuario extends Eloquent implements UserInterface {
	// Tabla con los usuarios
    protected $table = 'Usuarios';

    // Campos excluidos del JSON
    protected $hidden = array('password');

    public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	public function rol() {
		return $this->belongsTo('Rol', 'rol_id');
	}
}
?>