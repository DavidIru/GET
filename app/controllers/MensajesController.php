<?php
/*
|--------------------------------------------------------------------------
| Controlador de la página principal
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con la página principal
|
*/
class MensajesController extends BaseController {
	/**
	* Muestra la página con los mensajes predeterminados
	* @return Vista mensajes.listado => mensajes
	*/
	public function listado() {
		// Obtenemos todos los mensajes
		$mensajes = Mensaje::all();

		return View::make('mensajes.listado', array('mensajes' => $mensajes));
	}

	/**
	* Muestra la información del mensaje con el id seleccionado
	* @param int $mensaje_id Identificador del mensaje
	* @return Vista mensajes.formulario => mensaje
	*/
	public function mensaje($mensaje_id) {
		// Obtenemos el mensaje con el id $mensaje_id
		$mensaje = Mensaje::find($mensaje_id);

		return View::make('mensajes.formulario', array('mensaje' => $mensaje));
	}

	/**
	* Procesa la edición del mensaje con el id seleccionado
	* @param int $mensaje_id Identificador del mensaje
	* @return Si los datos son válidos -> Vista mensajes.formulario => mensaje, bien
	*		  Si no son válidos -> Vista mensajes.formulario => mensaje, errores
	*/
	public function editar($mensaje_id) {
		$mensaje = Mensaje::find($mensaje_id);

		$datos = array(
			'texto' => Input::get('texto')
		);

		$validacion = array(
			'texto' => array('required', 'max:140')
		);

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) { // Los datos no son válidos
			$errores = $validacion->messages();
			return View::make('mensajes.formulario', array('mensaje' => $mensaje, 'errores' => $errores->all()));
		}
		else { // Los datos son válidos
			$mensaje->texto = $datos['texto'];
			//Guardamos el mensaje
			$mensaje->save();
			return View::make('mensajes.formulario', array('mensaje' => $mensaje, 'bien' => true));
		}
	}
}