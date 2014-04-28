<?php
/*
|--------------------------------------------------------------------------
| Controlador de los envíos
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con los envíos
|
*/
class EnviosController extends BaseController {
	/**
	* Muestra la página con los envíos programados
	* @return Vista envios.listado => envios, exito
	*/
	public function listado() {
		// Obtenemos el listado de envíos programados
		$envios = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'FechaEntrega', 'HoraEntrega')
							->whereNull('Situacion')
							->where(function($query) {
								$query->whereNotNull('FechaEntrega')
									->whereNotNull('HoraEntrega');
							})
							->orderBy('FechaEntrega', 'asc')
							->orderBy('HoraEntrega', 'asc')->get();
		// Obtenemos el valor de la variable de sesión exito. Si no existe se le asigna false
		$exito = Session::get('exito', false);
		return View::make('envios.listado', array('envios' => $envios, 'exito' => $exito));
	}

	/**
	* Muestra la página con los datos del envío con el id seleccionado
	* @param int $envio_id Identificador del envío
	* @return Vista envios.formulario => envio, productos, exito
	*/
	public function detalles($envio_id) {
		// Obtenemos el envío con el ide $envio_id
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
		// Obtenemos los productos del envío
		$productos = PedidosDetalle::select('ArticuloDescripcion', 'Cantidad', 'Precio')
							->where('NumeroDocumento', $envio->NumeroDocumento)
							->get();
		// Obtenemos el valor de la variable de sesión exito. Si no existe se le asigna false
		$exito = Session::get('exito', false);
		return View::make('envios.formulario', array('envio' => $envio, 'productos' => $productos, 'exito' => $exito));
	}

	/**
	* Muestra la página para programar el envío con el id seleccionado
	* @param int $envio_id Identificador del envío
	* @return Vista envios.programar => envio
	*/
	public function verProgramar($envio_id) {
		// Obtenemos el envío con el ide $envio_id
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

	/**
	* Procesa la programación del envío con el id seleccionado
	* @param int $envio_id Identificador del envío
	* @return Si la validación es correcta -> Redirección a /envio/$envio_id con exito
	*		  Si la validación es errónea -> Vista envios.programar => envio, errores, error
	*/
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

		// Mensaje de error personalizado para la validación required_if
		$mensajes = array(
			'required_if' => 'El :attribute es requerido si quiere avisar al cliente.',
		);

		$validacion = Validator::make($datos, $validacion, $mensajes);
		 
		if($validacion->fails()) { // Los datos no son válidos
			$errores = $validacion->messages();
			return View::make('envios.programar', array('envio' => $envio, 'errores' => $errores->all(), 'error' => 1));
		}
		else { // Los datos son válidos
			$envio->FechaEntrega = $datos['fecha']." ".$datos['hora'];
			$envio->HoraEntrega = $datos['fecha']." ".$datos['hora'];
			$envio->telefonoAviso = $datos['telefono'];
			//Guardamos el envío
			$envio->save();
			if($datos['avisar']) {
				//Avisamos al cliente
				$telefono = $datos['telefono'];
				/*
					AVISAR AL CLIENTE
				*/
			}
			return Redirect::to(URL::to('envio/'.$envio->IdDocumento))
								->with('exito', true);
		}
	}

	/**
	* Procesa la cancelación del pedido con el id seleccionado
	* @param int $envio_id Identificador del envío
	* @return Si la validación es correcta -> Redirección a /pedido/$envio_id con exito
	*		  Si la validación es errónea -> Vista envios.programar => envio, errores, error
	*/
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

		// Mensaje de error personalizado para la validación required_if
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
				/*
					AVISAR AL CLIENTE
				*/
			}
			return Redirect::to(URL::to('pedido/'.$envio->IdDocumento))
								->with('exito', true);
		}
	}

	/**
	* Procesa la entrega del envío con el id seleccionado y genera la encuesta correspondiente
	* @param int $envio_id Identificador del envío
	* @return Redirección a /envios con exito
	*/
	public function entregado($envio_id) {
		// Seleccionamos el envío a marcar como entregado
		$envio = Pedido::whereNull('Situacion')
						->where(function($query) {
								$query->whereNotNull('FechaEntrega')
									->whereNotNull('HoraEntrega');
							})
						->where('IdDocumento', $envio_id)
						->firstOrFail();
		// Cambiamos la situación del pedido
		$envio->Situacion = "Entregado";
		// Guardamos el envío
		$envio->save();

		// Generamos la encuesta
		$encuesta = Encuesta::create(array(
			'pedido_id'  => $envio_id,
			'url' => 'encuesta/'.md5(date('YmdHis'))
		));

		// Obtenemos los productos del envío
		$productos = PedidosDetalle::select('Articulos.IdSubfamilia')
							->join('Articulos', 'Articulos.IdArticulo', '=', 'PedidosDetalle.IdArticulo')
							->where('PedidosDetalle.NumeroDocumento', $envio->NumeroDocumento)
							->distinct()->get();
		// Listado de ids de subfamilias
		$ids = array();

		// Llenamos el listado de $ids con los ids de las subfamilias de los productos del envío
		foreach($productos as $producto)
			array_push($ids, $producto->IdSubfamilia);

		// Obtenemos todas las pertenencias de cada producto del envío
		$articulos = Familia::select('Familias Agrupacion.IdAgrupacion', 'Familias.IdFamilia', 'Subfamilias.IdSubfamilia')
						->join('Subfamilias', 'Familias.IdFamilia', '=', 'Subfamilias.IdFamilia')
						->join('Familias Agrupacion', 'Familias Agrupacion.IdAgrupacion', '=', 'Familias.IdAgrupacion')
						->whereIn('Subfamilias.IdSubfamilia', $ids)
						->get();
	
		$a_agru = array();
		$a_fam = array();
		$a_sub = array();

		// Llenamos las listas con los ids de las agrupaciones, familias y subfamilias de los prodcutos
		foreach($articulos as $articulo) {
			//Llenar arrays
			if(!in_array($articulo->IdAgrupacion, $a_agru))
				array_push($a_agru, $articulo->IdAgrupacion);

			if(!in_array($articulo->IdFamilia, $a_fam))
				array_push($a_fam, $articulo->IdFamilia);

			if(!in_array($articulo->IdSubfamilia, $a_sub))
				array_push($a_sub, $articulo->IdSubfamilia);
		}

		// Obtenemos las preguntas que corresponden a la encuesta
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

		// Añadimos las preguntas a la tabla intermedias PreguntasEnvio para generar la encuesta
		foreach($preguntas as $pregunta) {
			PreguntasEnvio::create(array(
				'encuesta_id'  => $encuesta->id,
				'pregunta_id' => $pregunta->id,
				'resultado' => 0
			));
		}

		/*
			AVISAR AL CLIENTE MEDIANTE SMS
		*/
		
		return Redirect::to(URL::to('envios'))
							->with('exito', true);
	}
}