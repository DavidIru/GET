<?php
/*
|--------------------------------------------------------------------------
| Controlador de la página principal
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con la página principal
|
*/
class HomeController extends BaseController {
	/**
	* Muestra la página inicial de la aplicación
	* @return Vista home => pedidos, comentarios, media
	*/
	public function inicial() {
		// Obtenemos los pedidos pendientes de programar
		$pedidos = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'CLTelefono', 'Situacion')
							->whereNotIn('Situacion', array('Entregado', 'Anulado', 'PRESUPUESTO'))
							->orwhereNull('Situacion')
							->where(function($query) {
								$query->whereNull('FechaEntrega')
									->orwhereNull('HoraEntrega');
							})
							->orderBy('FechaDocumento', 'asc')->get();

		// Obtenemos los comentarios pendientes de leer
		$comentarios = Comentario::select('id', 'comentario', 'leido')
							->where('leido', 0)
							->orderBy('created_at', 'desc')
							->get();

		// Obtenemos la nota media de las encuestas respondidas
		$media = PreguntasEnvio::join('Encuestas', 'Encuestas.id', '=', 'PreguntasEnvio.encuesta_id')
							->where('Encuestas.respondida', 1)
							->avg('resultado');
		
		return View::make('home', array('pedidos' => $pedidos, 'comentarios' => $comentarios, 'media' => $media));
	}
}