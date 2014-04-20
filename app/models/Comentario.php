<?php 
class Comentario extends Eloquent {
    protected $table = 'Comentarios';

    protected $guarded = array();

    public function encuesta() {
		return $this->belongsTo('Encuesta', 'encuesta_id');
	}
}