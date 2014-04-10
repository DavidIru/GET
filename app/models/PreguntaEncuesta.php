<?php 
class PreguntaEncuesta extends Eloquent {
	// Tabla con las preguntas
    protected $table = 'Preguntas';

    //protected $guarded = array('id');

    //public static $unguarded = true;

    protected $fillable = array('texto', 'agrupacion_id', 'familia_id', 'subfamilia_id', 'activa');

    protected $guarded = array();

    public function pedidos() {
    	return $this->belongsToMany('Pedidos', 'PreguntasEnvio', 'pedido_id', 'pregunta_id');
    }
}