<?php 
class TiposMensaje extends Eloquent {
    protected $table = 'TiposMensaje';

    public function mensaje()
    {
		return $this->hasOne('Mensaje', 'tipo_id');
    }
}