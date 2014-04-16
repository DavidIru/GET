<?php 
class PromocionesController extends BaseController {

    public function listado() {
        $clientes = ClientesPromocion::select('id', 'nombre', 'telefono', 'email')->orderBy('id', 'desc')->get();

        return View::make('promociones.listado', array('clientes' => $clientes));
    }

    public function formularioAdd() {
		return View::make('promociones.formulario-add');
	}

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

		$mensajes = array(
			'telefono.required_if' => 'El campo :attribute no puede estar en blanco si el campo :other lo está.',
			'email.required_if' => 'El campo :attribute no puede estar en blanco si el campo :other lo está.',
		);

		$validacion = Validator::make($datos, $validacion, $mensajes);
		 
		if($validacion->fails()) {
			return Redirect::to('promociones/cliente/add')
			->withErrors($validacion)
			->withInput();
		}
		else {
			ClientesPromocion::create(array(
				'nombre'  => $datos['nombre'],
				'telefono' => ($datos['telefono'] == "")? null : $datos['telefono'],
				'email' => ($datos['email'] == "")? null : $datos['email']
			));

			$clientes = ClientesPromocion::select('id', 'nombre', 'telefono', 'email')->orderBy('id', 'desc')->get();

        	return View::make('promociones.listado', array('clientes' => $clientes, 'exito' => 'Se ha inscrito el cliente con éxito'));
		}
	}

	public function cliente($cliente_id) {
		$cliente = ClientesPromocion::find($cliente_id);

        return View::make('promociones.formulario', array('cliente' => $cliente));
	}

	public function editar($cliente_id) {
		$cliente = ClientesPromocion::find($cliente_id);

		$mensaje = array('numero' => Input::get('mensaje'), 'error' => true);
		//echo $mensaje;
		if($mensaje['numero'] == "mensaje0") {
			//Procesamos el usuario
			$datos = array(
	            'nombre' => Input::get('nombre')
	        );

	        $validacion = array(
        		'nombre' => array('required', 'max:100')
        	);

        	$mensajes = array();
		}
		elseif($mensaje['numero'] == "mensaje1") {
			//Procesamos el usuario
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
		else {
			return Redirect::to("promociones/cliente/".$cliente_id);
		}

		$validacion = Validator::make($datos, $validacion, $mensajes);
		 
		if($validacion->fails()) {
		    $errores = $validacion->messages();
		    return View::make('promociones.formulario', array('cliente' => $cliente, 'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else {
		    if($mensaje['numero'] == "mensaje0") {
				//Cambiamos el nombre
				$cliente->nombre = $datos['nombre'];
			}
			elseif($mensaje['numero'] == "mensaje1") {
				//Cambiamos el teléfono y el email
				$cliente->telefono = $datos['telefono'];
				$cliente->email = $datos['email'];
			}
			//Guardamos el cliente
			$cliente->save();
			$mensaje['error'] = false;
			return View::make('promociones.formulario', array('cliente' => $cliente, 'mensaje' => $mensaje));
		}
	}

	public function eliminar($cliente_id) {
		$enviado = Input::get('borrar');

		if($enviado == "borrar") {
			$cliente = ClientesPromocion::find($cliente_id);
			$cliente->delete();

			$clientes = ClientesPromocion::select('id', 'nombre', 'telefono', 'email')->orderBy('id', 'desc')->get();

        	return View::make('promociones.listado', array('clientes' => $clientes, 'exito' => 'Se ha eliminado el cliente con éxito'));
		}
		else {
			return Redirect::to('promociones');
		}
	}

	public function formularioEnviar() {
		return View::make('promociones.formulario-enviar');
	}

	public function enviar() {
		$mensaje = array('tipo' => Input::get('tipo'), 'error' => true);
		//echo $mensaje;
		if($mensaje['tipo'] == "sms") {
			//Procesamos el usuario
			$datos = array(
	            'textsms' => Input::get('textsms')
	        );

	        $validacion = array(
        		'textsms' => array('required', 'max:160')
        	);
		}
		elseif($mensaje['tipo'] == "email") {
			//Procesamos el usuario
			$datos = array(
	            'asunto' => Input::get('asunto'),
	            'textmail' => Input::get('textmail')
	        );

	        $validacion = array(
        		'asunto' => array('required'),
        		'textmail' => array('required')
        	);
		}
		else {
			return Redirect::to("promociones/enviar/");
		}

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) {
		    $errores = $validacion->messages();
		    return View::make('promociones.formulario-enviar', array('errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else {
		    if($mensaje['tipo'] == "sms") {
				//Enviamos los SMS
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
			$mensaje['error'] = false;
			//return View::make('promociones.formulario-enviar', array('mensaje' => $mensaje));
		}
	}
}