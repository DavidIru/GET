<?php 
class PreguntaEncuesta extends Eloquent {
	// Tabla con las preguntas
    protected $table = 'Preguntas';

    //protected $guarded = array('id');

    //public static $unguarded = true;

    protected $fillable = array('texto', 'agrupacion_id', 'familia_id', 'subfamilia_id', 'activa');

    protected $guarded = array();

    public function encuestas() {
    	return $this->belongsToMany('Encuestas', 'PreguntasEnvio', 'encuesta_id', 'pregunta_id');
    }

    public function agrupacion() {
		return $this->belongsTo('FamiliasAgrupacion', 'agrupacion_id');
	}

	public function familia() {
		return $this->belongsTo('Familia', 'familia_id');
	}

	public function subfamilia() {
		return $this->belongsTo('Subfamilia', 'subfamilia_id');
	}
}