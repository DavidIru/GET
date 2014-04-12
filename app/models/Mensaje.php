<?php 
class Mensaje extends Eloquent {
    protected $table = 'Mensajes';

    public function tipo()
    {
		return $this->belongsTo('TiposMensaje', 'tipo_id');
    }
}