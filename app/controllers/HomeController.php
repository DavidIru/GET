<?php

class HomeController extends BaseController {
	public function inicial() {
		$pedidos = Pedido::whereNull('Situacion')
							->orWhere('Situacion', '!=', 'Entregado', 'AND')
							->where('Situacion', '!=', 'Anulado', 'AND')
							->where('Situacion', '!=', 'PRESUPUESTO')
							->orderBy('FechaDocumento', 'asc')->get();
		return View::make('home', array('pedidos' => $pedidos));
	}

	public function pruebas() {
		$pedidos = Pedido::all();
		return View::make('pruebas')->with("pedidos", $pedidos);

	}

}