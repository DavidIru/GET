<?php 
class EnviosController extends BaseController {

	public function listado() {
		$envios = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'FechaEntrega', 'HoraEntrega')
							->whereNull('Situacion')
							->where(function($query) {
								$query->whereNotNull('FechaEntrega')
									->whereNotNull('HoraEntrega');
							})
							->orderBy('FechaEntrega', 'asc')
							->orderBy('HoraEntrega', 'asc')->get();
		$exito = Session::get('exito', false);
		return View::make('envios.listado', array('envios' => $envios, 'exito' => $exito));
	}

	public function detalles($envio_id) {
		$envio = Pedido::select('IdDocumento', 'NumeroDocumento', 'FechaDocumento', 'CLNombre', 'CLNombreEnvio', 
							'CLDireccionEnvio', 'CLCiudadEnvio', 'CLProviniciaEnvio', 'CLCodPostalEnvio', 
							'CLTelefonoEnvio', 'Situacion', 'ImporteAcuenta', 'DescripcionFormaPagoDocumento', 
							'FechaEntrega', 'HoraEntrega', 'telefonoAviso')
							->whereNull('Situacion')
							->where(function($query) {
								$query->whereNotNull('FechaEntrega')
									->whereNotNull('HoraEntrega');
							})
							->where('IdDocumento', $envio_id)
							->firstOrFail();
		$productos = PedidosDetalle::select('ArticuloDescripcion', 'Cantidad', 'Precio')
							->where('NumeroDocumento', $envio->NumeroDocumento)
							->get();
		$exito = Session::get('exito', false);
		return View::make('envios.formulario', array('envio' => $envio, 'productos' => $productos, 'exito' => $exito));
	}

	public function verProgramar($envio_id) {
		$envio = Pedido::select('IdDocumento', 'NumeroDocumento', 'FechaEntrega', 'HoraEntrega', 'CLTelefonoEnvio', 'telefonoAviso')
							->whereNull('Situacion')
							->where(function($query) {
								$query->whereNotNull('FechaEntrega')
									->whereNotNull('HoraEntrega');
							})
							->where('IdDocumento', $envio_id)
							->firstOrFail();
		return View::make('envios.programar', array('envio' => $envio));
	}

	public function programar($envio_id) {
		//Redirigir a envíos y mostrar mensaje de confirmación
		$envio = Pedido::find($envio_id);
		$datos = array(
			'fecha' => Input::get('envio_fecha'),
			'hora' => Input::get('hora'),
			'avisar' => (Input::get('avisarp'))? true : false,
			'telefono' => Input::get('telefonop')
		);

		$validacion = array(
			'fecha' => array('required', 'date'),
			'hora' => array('required', 'dateformat:H:i'),
			'telefono' => array('required_if:avisar,true', 'numeric')
		);

		$mensajes = array(
			'required_if' => 'El :attribute es requerido si quiere avisar al cliente.',
		);

		$validacion = Validator::make($datos, $validacion, $mensajes);
		 
		if($validacion->fails()) {
			$errores = $validacion->messages();
			return View::make('envios.programar', array('envio' => $envio, 'errores' => $errores->all(), 'error' => 1));
		}
		else {
			$envio->FechaEntrega = $datos['fecha']." ".$datos['hora'];
			$envio->HoraEntrega = $datos['fecha']." ".$datos['hora'];
			$envio->telefonoAviso = $datos['telefono'];
			//Guardamos el envío
			$envio->save();
			if($datos['avisar']) {
				//Avisamos al cliente
				$telefono = $datos['telefono'];
			}
			return Redirect::to(URL::to('envio/'.$envio->IdDocumento))
								->with('exito', true);
		}
	}

	public function cancelar($envio_id) {
		//Redirigir a pedidos y mostrar mensaje de cancelación
		$envio = Pedido::find($envio_id);

		$datos = array(
			'avisar' => (Input::get('avisarc'))? true : false,
			'telefono' => Input::get('telefonoc')
		);

		$validacion = array(
			'telefono' => array('required_if:avisar,true', 'numeric')
		);

		$mensajes = array(
			'required_if' => 'El :attribute es requerido si quiere avisar al cliente.',
		);

		$validacion = Validator::make($datos, $validacion, $mensajes);
		 
		if($validacion->fails()) {
			$errores = $validacion->messages();
			return View::make('envios.programar', array('envio' => $envio, 'errores' => $errores->all(), 'error' => 2));
		}
		else {
			$envio->FechaEntrega = NULL;
			$envio->HoraEntrega = NULL;
			$envio->telefonoAviso = NULL;
			//Guardamos el envío
			$envio->save();
			if($datos['avisar']) {
				//Avisamos al cliente
				$telefono = $datos['telefono'];
			}
			return Redirect::to(URL::to('pedido/'.$envio->IdDocumento))
								->with('exito', true);
		}
	}

	public function entregado($envio_id) {
		//Redirigir a pedidos y mostrar mensaje de cancelación
		$envio = Pedido::whereNull('Situacion')
						->where(function($query) {
								$query->whereNotNull('FechaEntrega')
									->whereNotNull('HoraEntrega');
							})
						->where('IdDocumento', $envio_id)
						->firstOrFail();
		
		$envio->Situacion = "Entregado";
		//Guardamos el envío
		$envio->save();

		//Generar encuesta
		$encuesta = Encuesta::create(array(
			'pedido_id'  => $envio_id,
			'url' => 'encuesta/'.md5(date('YmdHis'))
		));

		$productos = PedidosDetalle::select('Articulos.IdSubfamilia')
							->join('Articulos', 'Articulos.IdArticulo', '=', 'PedidosDetalle.IdArticulo')
							->where('PedidosDetalle.NumeroDocumento', $envio->NumeroDocumento)
							->distinct()->get();
		$ids = array();

		foreach($productos as $producto)
			array_push($ids, $producto->IdSubfamilia);

		$articulos = Familia::select('Familias Agrupacion.IdAgrupacion', 'Familias.IdFamilia', 'Subfamilias.IdSubfamilia')
						->join('Subfamilias', 'Familias.IdFamilia', '=', 'Subfamilias.IdFamilia')
						->join('Familias Agrupacion', 'Familias Agrupacion.IdAgrupacion', '=', 'Familias.IdAgrupacion')
						->whereIn('Subfamilias.IdSubfamilia', $ids)
						->get();
	
		$a_agru = array();
		$a_fam = array();
		$a_sub = array();

		foreach($articulos as $articulo) {
			//Llenar arrays
			if(!in_array($articulo->IdAgrupacion, $a_agru))
				array_push($a_agru, $articulo->IdAgrupacion);

			if(!in_array($articulo->IdFamilia, $a_fam))
				array_push($a_fam, $articulo->IdFamilia);

			if(!in_array($articulo->IdSubfamilia, $a_sub))
				array_push($a_sub, $articulo->IdSubfamilia);
		}

		$preguntas = PreguntaEncuesta::select('id')
							->where('activa', '1')
							->where(function($query) use ($a_agru, $a_fam, $a_sub) {
								$query->Where(function($query2) {
											$query2->whereNull('agrupacion_id')
												->whereNull('familia_id')
												->whereNull('subfamilia_id');
										})
									->orWhere(function($query2) use ($a_agru) {
											$query2->whereIn('agrupacion_id', $a_agru)
												->whereNull('familia_id')
												->whereNull('subfamilia_id');
										})
									->orWhere(function($query2) use ($a_fam) {
											$query2->whereIn('familia_id', $a_fam)
												->whereNull('subfamilia_id');
										})
									->orWhere(function($query2) use ($a_sub) {
											$query2->whereIn('subfamilia_id', $a_sub);
										});
							})
							->distinct()->get();

		foreach($preguntas as $pregunta) {
			PreguntasEnvio::create(array(
				'encuesta_id'  => $encuesta->id,
				'pregunta_id' => $pregunta->id,
				'resultado' => 0
			));
		}
		/*
		Comentario::create(array(
			'encuesta_id'  => $encuesta->id,
			'comentario' => ""
		));
		*/
		return Redirect::to(URL::to('envios'))
							->with('exito', true);
	}
}