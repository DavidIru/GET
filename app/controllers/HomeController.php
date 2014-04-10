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
		$pregunta = Pregunta::find(1);

		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();
    	$familias = Familia::orderBy('Familia', 'asc')->get();
    	$subfamilias = Subfamilia::orderBy('Subfamilia', 'asc')->get();

		$usuarios = Usuario::all();
		return View::make('pruebas')->with("pregunta", $pregunta)
		->with("agrupaciones", $agrupaciones)
		->with("familias", $familias)
		->with("subfamilias", $subfamilias);

	}

}