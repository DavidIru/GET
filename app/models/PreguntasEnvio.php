<?php 
class Pregunta extends Eloquent {
	// Tabla con las preguntas
    protected $table = 'Preguntas';

    public function pedidos() {
    	return $this->belongsToMany('Pedidos', 'PreguntasEnvio', 'pedido_id', 'pregunta_id');
    }
}
?>