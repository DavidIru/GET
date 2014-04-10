<?php

class Pedido extends Eloquent {
    protected $table = 'Pedidos';

    protected $primaryKey = "IdDocumento";

    public $timestamps = false;

    public function preguntas() {
    	return $this->belongsToMany('Preguntas', 'PreguntasEnvio', 'pregunta_id', 'pedido_id');
    }
}
?>