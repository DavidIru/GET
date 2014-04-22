<?php

class HomeController extends BaseController {
	public function inicial() {
		$pedidos = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'CLTelefono', 'Situacion')
							->whereNotIn('Situacion', array('Entregado', 'Anulado', 'PRESUPUESTO'))
							->orwhereNull('Situacion')
							->where(function($query) {
								$query->whereNull('FechaEntrega')
									->orwhereNull('HoraEntrega');
							})
							->orderBy('FechaDocumento', 'asc')->get();

		$comentarios = Comentario::select('id', 'comentario', 'leido')
							->where('leido', 0)
							->orderBy('created_at', 'desc')
							->get();

		$media = PreguntasEnvio::join('Encuestas', 'Encuestas.id', '=', 'PreguntasEnvio.encuesta_id')
							->where('Encuestas.respondida', 1)
							->avg('resultado');
		
		return View::make('home', array('pedidos' => $pedidos, 'comentarios' => $comentarios, 'media' => $media));
	}

	public function pruebas() {
		return View::make('pruebas');
	}

}