<?php
/*
|--------------------------------------------------------------------------
| Controlador de los pedidos
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con los pedidos
|
*/
class PromocionesController extends BaseController {
	/**
	* Muestra la página con el listado de clientes inscritos en las promociones
	* @return Vista promociones.listado => clientes
	*/
	public function listado() {
		// Obtenemos el listado de clientes inscritos en promociones
		$clientes = ClientesPromocion::select('id', 'nombre', 'telefono', 'email')
							->orderBy('id', 'desc')
							->get();

		return View::make('promociones.listado', array('clientes' => $clientes));
	}

	/**
	* Muestra el formulario para añadir nuevos clientes a las promociones
	* @return Vista promociones.formulario-add
	*/
	public function formularioAdd() {
		return View::make('promociones.formulario-add');
	}

	/**
	* Procesa el alta de un cliente en las promociones
	* @return Si la validación es correcta -> Vista promociones.listado => clientes, exito
	*		  Si la validación es errónea -> Redirección a /promociones/cliente/add con errores y datos de input
	*/
	public function add() {
		$datos = array(
			'nombre' => Input::get('nombre'),
			'telefono' => Input::get('telefono'),
			'email' => Input::get('email')
		);

		$validacion = array(
			'nombre' => array('required', 'max:100'),
			'telefono' => array('required_if:email,', 'numeric'),
			'email' => array('required_if:telefono,', 'email')
		);

		// Mensajes de error personalizados para las comprobaciones require_if de teléfono y de email
		$mensajes = array(
			'telefono.required_if' => 'El campo :attribute no puede estar en blanco si el campo :other lo está.',
			'email.required_if' => 'El campo :attribute no puede estar en blanco si el campo :other lo está.',
		);

		$validacion = Validator::make($datos, $validacion, $mensajes);
		 
		if($validacion->fails()) { // Los datos no son válidos
			return Redirect::to('promociones/cliente/add')
						->withErrors($validacion)
						->withInput();
		}
		else { // Los datos son válidos
			// Añadimos al cliente a la lista de promociones
			ClientesPromocion::create(array(
				'nombre'  => $datos['nombre'],
				'telefono' => ($datos['telefono'] == "")? null : $datos['telefono'],
				'email' => ($datos['email'] == "")? null : $datos['email']
			));

			// Obtenemos el nuevo listado de clientes
			$clientes = ClientesPromocion::select('id', 'nombre', 'telefono', 'email')->orderBy('id', 'desc')->get();

			return View::make('promociones.listado', array('clientes' => $clientes, 'exito' => 'Se ha inscrito el cliente con éxito'));
		}
	}

	/**
	* Muestra los datos del cliente con el id seleccionado
	* @param int $cliente_id Identificador del cliente
	* @return Vista promociones.formulario => cliente
	*/
	public function cliente($cliente_id) {
		// Obtenemos el cliente
		$cliente = ClientesPromocion::find($cliente_id);

		return View::make('promociones.formulario', array('cliente' => $cliente));
	}

	/**
	* Procesa la edición del cliente con el id seleccionado
	* @param int $cliente_id Identificador del cliente
	* @return Si el formulario es incorrecto -> Redirección a /promociones/cliente/$cliente_id
	*		  Sino -> Si los datos son válidos -> Vista promociones.formulario => cliente, mensaje
	*				  Si no son válidos -> Vista promociones.formulario => cliente, errores, mensaje
	*/
	public function editar($cliente_id) {
		// Obtenemos el cliente con el id $cliente_id
		$cliente = ClientesPromocion::find($cliente_id);

		$mensaje = array('numero' => Input::get('mensaje'), 'error' => true);

		if($mensaje['numero'] == "mensaje0") {
			// Procesamos el nombre
			$datos = array(
				'nombre' => Input::get('nombre')
			);

			$validacion = array(
				'nombre' => array('required', 'max:100')
			);

			$mensajes = array();
		}
		elseif($mensaje['numero'] == "mensaje1") {
			// Procesamos el teléfono y el email
			$datos = array(
				'telefono' => Input::get('telefono'),
				'email' => Input::get('email')
			);

			$validacion = array(
				'telefono' => array('required_if:email,', 'numeric'),
				'email' => array('required_if:telefono,', 'email')
			);

			$mensajes = array(
				'telefono.required_if' => 'El campo :attribute no puede estar en blanco si el campo :other lo está.',
				'email.required_if' => 'El campo :attribute no puede estar en blanco si el campo :other lo está.',
			);
		}
		else { // El formulario no es válido
			return Redirect::to("promociones/cliente/".$cliente_id);
		}

		$validacion = Validator::make($datos, $validacion, $mensajes);
		 
		if($validacion->fails()) { // Los datos no son válidos
			$errores = $validacion->messages();
			return View::make('promociones.formulario', array('cliente' => $cliente, 'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else { // Los datos son válidos
			if($mensaje['numero'] == "mensaje0") {
				// Cambiamos el nombre
				$cliente->nombre = $datos['nombre'];
			}
			elseif($mensaje['numero'] == "mensaje1") {
				// Cambiamos el teléfono y el email
				$cliente->telefono = $datos['telefono'];
				$cliente->email = $datos['email'];
			}
			// Guardamos el cliente
			$cliente->save();
			// Indicamos que no hay errores
			$mensaje['error'] = false;
			return View::make('promociones.formulario', array('cliente' => $cliente, 'mensaje' => $mensaje));
		}
	}

	/**
	* Procesa la eliminación del cliente de las promociones
	* @param int $cliente_id Identificador del cliente
	* @return Si el formulario es correcto -> Vista promociones.listado => clientes, exito
	*		  Si el formulario no es correcto -> Redirección a /promociones
	*/
	public function eliminar($cliente_id) {
		$enviado = Input::get('borrar');

		if($enviado == "borrar") { // El formulario es correcto
			// Obtenemos el cliente con el id $cliente_id
			$cliente = ClientesPromocion::find($cliente_id);
			// Borramos el cliente
			$cliente->delete();
			// Obtenemos el nuevo listado de clientes
			$clientes = ClientesPromocion::select('id', 'nombre', 'telefono', 'email')->orderBy('id', 'desc')->get();

			return View::make('promociones.listado', array('clientes' => $clientes, 'exito' => 'Se ha eliminado el cliente con éxito'));
		}
		else { // El formulario no es correcto
			return Redirect::to('promociones');
		}
	}

	/**
	* Muestra lel formulario para enviar promociones por sms y por email
	* @return Vista promociones.formulario-enviar
	*/
	public function formularioEnviar() {
		return View::make('promociones.formulario-enviar');
	}

	/**
	* Procesa el envío de la promoción
	* @return Si el formulario es incorrecto -> Redirección a /promociones/enviar
	*		  Sino -> Si los datos son válidos -> Vista promociones.formulario-enviar => mensaje
	*				  Si no son válidos -> Vista promociones.formulario-enviar => errores, mensaje
	*/
	public function enviar() {
		$mensaje = array('tipo' => Input::get('tipo'), 'error' => true);

		if($mensaje['tipo'] == "sms") {
			// Procesamos el envío de SMS
			$datos = array(
				'textsms' => Input::get('textsms')
			);

			$validacion = array(
				'textsms' => array('required', 'max:160')
			);
		}
		elseif($mensaje['tipo'] == "email") {
			// Procesamos el envío de email
			$datos = array(
				'asunto' => Input::get('asunto'),
				'textmail' => Input::get('textmail')
			);

			$validacion = array(
				'asunto' => array('required'),
				'textmail' => array('required')
			);
		}
		else { // Formulario incorrecto
			return Redirect::to("promociones/enviar/");
		}

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) { // Los datos no son válidos
			$errores = $validacion->messages();
			return View::make('promociones.formulario-enviar', array('errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else { // Los datos son válidos
			if($mensaje['tipo'] == "sms") {
				//Enviamos los SMS
				/*
					ENVIAR SMS
				*/
			}
			elseif($mensaje['tipo'] == "email") {
				//Enviamos los emails
				$clientes = ClientesPromocion::select('nombre', 'email')->whereNotNull('email')->get();
				foreach ($clientes as $cliente) {
					//Enviamos el email
					$sustituciones = array("#nombre#", "#email#");
					$reemplazo = array($cliente->nombre, $cliente->email);
					
					$textofinal = str_replace($sustituciones, $reemplazo, $datos['textmail']);

					/*
					$cabeceras = 'MIME-Version: 1.0' . "\r\n";
					$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					@mail($email, $asunto, $mensaje, $cabeceras);
					// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
					

					// Cabeceras adicionales
					$cabeceras .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
					$cabeceras .= 'From: Recordatorio <cumples@example.com>' . "\r\n";
					$cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
					$cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
					*/
				}
			}

			// Indicamos que no hay errores
			$mensaje['error'] = false;
			
			return View::make('promociones.formulario-enviar', array('mensaje' => $mensaje));
		}
	}
}