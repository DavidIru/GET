<?php 
class Pedido extends Eloquent {
    protected $table = 'Pedidos';

    protected $primaryKey = "IdDocumento";

    public $timestamps = false;
}
?>