<?php

class Pedido extends Eloquent {
    protected $table = 'Pedidos';

    protected $primaryKey = "IdDocumento";

    public $timestamps = false;

    public function encuesta()
    {
		return $this->hasOne('Encuesta', 'pedido_id');
    }

    
}
?>