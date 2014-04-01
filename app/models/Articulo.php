<?php 
class Articulo extends Eloquent {
    protected $table = 'Articulos';

    protected $primaryKey = "IdArticulo";

    public $timestamps = false;
}
?>