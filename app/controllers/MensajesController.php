<?php 
class MensajesController extends BaseController {

	public function listado() {
		$mensajes = Mensaje::all();

		return View::make('mensajes.listado', array('mensajes' => $mensajes));
	}

	public function mensaje($mensaje_id) {
    	$mensaje = Mensaje::find($mensaje_id);

    	return View::make('mensajes.formulario', array('mensaje' => $mensaje));
    }

	public function editar($mensaje_id) {
		$mensaje = Mensaje::find($mensaje_id);

		$datos = array(
            'texto' => Input::get('texto')
        );

        $validacion = array(
    		'texto' => array('required', 'max:140')
    	);

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) {
		    $errores = $validacion->messages();
		    return View::make('mensajes.formulario', array('mensaje' => $mensaje, 'errores' => $errores->all()));
		}
		else {
			$mensaje->texto = $datos['texto'];
			//Guardamos el mensaje
			$mensaje->save();
			return View::make('mensajes.formulario', array('mensaje' => $mensaje, 'bien' => true));
		}
	}
}