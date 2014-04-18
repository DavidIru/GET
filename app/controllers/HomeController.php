<?php

class HomeController extends BaseController {
	public function inicial() {
		$pedidos = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'CLTelefono', 'Situacion')
							->whereNotIn('Situacion', array('Entregado', 'Anulado', 'PRESUPUESTO'))
							->orwhereNull('Situacion')
							->whereRaw('(FechaEntrega is NULL or HoraEntrega is NULL)')
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