<?php
/*
|--------------------------------------------------------------------------
| Controlador de los pedidos
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con los pedidos
|
*/
class PedidosController extends BaseController {
	/**
	* Muestra la página con los 100 últimos pedidos
	* @return Vista pedidos.listado => pedidos, todos = false
	*/
	public function listado() {
		// Obtenemos los 100 últimos pedidos
		$pedidos = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'CLTelefono')
							->orderBy('IdDocumento', 'desc')
							->take(100)
							->get();

		return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => false));
	}

	/**
	* Muestra la página con los detalles del pedido con el id seleccionado
	* @param int $pedido_id Identificador del pedido
	* @return Vista pedidos.formulario => pedido, productos, exito
	*/
	public function detalles($pedido_id) {
		// Obtenemos los datos del pedido con id $pedido_id
		$pedido = Pedido::select('IdDocumento', 'NumeroDocumento', 'FechaDocumento', 'CLNombre', 'CLNombreEnvio', 
							'CLDireccionEnvio', 'CLCiudadEnvio', 'CLProviniciaEnvio', 'CLCodPostalEnvio', 
							'CLTelefonoEnvio', 'Situacion', 'ImporteAcuenta', 'DescripcionFormaPagoDocumento', 
							'FechaEntrega', 'HoraEntrega')
							->find($pedido_id);
		// Obtenemos los productos pertenecientes al pedido
		$productos = PedidosDetalle::select('ArticuloDescripcion', 'Cantidad', 'Precio')
							->where('NumeroDocumento', $pedido->NumeroDocumento)
							->get();
		// Obtenemos el valor de la variable de sesión exito. Si no existe se le asigna false
		$exito = Session::get('exito', false);
		return View::make('pedidos.formulario', array('pedido' => $pedido, 'productos' => $productos, 'exito' => $exito));
	}

	/**
	* Muestra la página con todos los pedidos
	* @return Vista pedidos.listado => pedidos, todos = true
	*/
	public function mostrarTodos() {
		$pedidos = Pedido::orderBy('IdDocumento', 'desc')->get();

		return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => true));
	}

	/**
	* Muestra la página para programar el pedido con el id seleccionado
	* @return Vista pedidos.programar => pedido
	*/
	public function verProgramar($pedido_id) {
		// Obtenemos el pedido con el id $pedido_id
		$pedido_id = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLTelefonoEnvio', 'telefonoAviso')
							->whereNull('Situacion')
							->where('IdDocumento', $pedido_id)
							->firstOrFail();
		return View::make('pedidos.programar', array('pedido' => $pedido_id));
	}

	/**
	* Procesamos la programación del pedido con el id seleccionado
	* @param int $pedido_id Identificador del pedido
	* @return Si la validación es correcta -> Redirección a /envio/$pedido_id con exito = true
	*		  Si la validación es errónea -> Vista pedidos.programar => pedido, errores
	*/
	public function programar($pedido_id) {
		//Redirigir a envíos y mostrar mensaje de confirmación
		$pedido = Pedido::find($pedido_id);
		$datos = array(
			'fecha' => Input::get('envio_fecha'),
			'hora' => Input::get('hora'),
			'avisar' => (Input::get('avisarp'))? true : false,
			'telefono' => Input::get('telefono')
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
			return View::make('pedidos.programar', array('pedido' => $pedido, 'errores' => $errores->all()));
		}
		else { // los datos son válidos
			$pedido->FechaEntrega = $datos['fecha']." ".$datos['hora'];
			$pedido->HoraEntrega = $datos['fecha']." ".$datos['hora'];
			$pedido->telefonoAviso = $datos['telefono'];
			//Guardamos el pedido
			$pedido->save();
			if($datos['avisar']) {
				//Avisamos al cliente
				$telefono = $datos['telefono'];
				/*
					AVISAR AL CLIENTE
				*/
			}
			return Redirect::to(URL::to('envio/'.$pedido->IdDocumento))
								->with('exito', true);
		}
	}
}