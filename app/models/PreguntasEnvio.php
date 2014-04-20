<?php 
class PreguntasEnvio extends Eloquent {
	// Tabla con las preguntas
    protected $table = 'PreguntasEnvio';

    protected $guarded = array('id');

    public function pregunta() {
		return $this->belongsTo('PreguntaEncuesta', 'pregunta_id');
	}

	public function encuesta() {
		return $this->belongsTo('Encuesta', 'encuesta_id');
	}
}
?>