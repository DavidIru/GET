<?php 
class Encuesta extends Eloquent {
	// Tabla con las encuestas
    protected $table = 'Encuestas';

    protected $guarded = array('id');

    public function preguntas() {
    	return $this->hasMany('PreguntasEnvio', 'encuesta_id');
    }
    //return $this->hasMany('Comment', 'foreign_key', 'local_key');

    public function comentario()
    {
		return $this->hasOne('Comentario', 'encuesta_id');
    }

    public function pedido()
    {
        return $this->belongsTo('Pedido', 'pedido_id');
    }
}
?>