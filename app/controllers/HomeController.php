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
		return View::make('home', array('pedidos' => $pedidos));
	}

	public function pruebas() {
		return View::make('pruebas');
	}

}