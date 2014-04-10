<?php 
class EncuestasController extends BaseController {
	public function listadoPreguntas() {
		$preguntas = PreguntaEncuesta::where('activa', 1)->get();
		
		return View::make('encuestas.listado', array('preguntas' => $preguntas));
	}

	public function pregunta($pregunta_id) {
		$pregunta = PreguntaEncuesta::find($pregunta_id);

		$agrupacion = null;
		$familia = null;
		$subfamilia = null;
		$agrupaciones = null;
		$familias = null;
		$subfamilias = null;

		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();
		if(!is_null($pregunta->agrupacion_id)) {
			$agrupacion = FamiliasAgrupacion::find($pregunta->agrupacion_id)->AgrupacionFamilia;
			$familias = Familia::where('IdAgrupacion', $pregunta->agrupacion_id)
							->orderBy('Familia', 'asc')->get();
			if(!is_null($pregunta->familia_id)) {
				$familia = Familia::find($pregunta->familia_id)->Familia;
				$subfamilias = Subfamilia::where('IdFamilia', $pregunta->familia_id)
							->orderBy('Subfamilia', 'asc')->get();
				if(!is_null($pregunta->subfamilia_id)) {
					$subfamilia = Subfamilia::find($pregunta->subfamilia_id)->Subfamilia;
				}
			}
		}

		return View::make('encuestas.formulario', array('pregunta' => $pregunta, 'agrupacionACT' => $agrupacion, 'familiaACT' => $familia, 
			'subfamiliaACT' => $subfamilia, 'agrupaciones' => $agrupaciones, 'familias' => $familias, 'subfamilias' => $subfamilias));
	}

	public function obtenerFamilias($agrupacion_id) {
		$familias = Familia::where('IdAgrupacion', $agrupacion_id)->orderBy('Familia', 'asc')->get();	   

		return Response::json($familias);
	}

	public function obtenerSubfamilias($familia_id) {
		$subfamilias = Subfamilia::where('IdFamilia', $familia_id)->orderBy('Subfamilia', 'asc')->get();	   

		return Response::json($subfamilias);
	}

	public function eliminar($pregunta_id) {
		$enviado = Input::get('borrar');

		if($enviado == "borrar") {
			$pregunta = PreguntaEncuesta::find($pregunta_id);
			$pregunta->activa = 0;
			$pregunta->save();

			$preguntas = PreguntaEncuesta::where('activa', 1)->get();
		
			return View::make('encuestas.listado', array('preguntas' => $preguntas, 'exito' => 'Se ha eliminado el usuario con éxito'));
		}
		else {
			return Redirect::to('encuestas');
		}
	}

	public function editar($pregunta_id) {
		$pregunta = PreguntaEncuesta::find($pregunta_id);

		$agrupacion = null;
		$familia = null;
		$subfamilia = null;
		$agrupaciones = null;
		$familias = null;
		$subfamilias = null;

		$mensaje = array('numero' => Input::get('mensaje'), 'error' => true);
		//echo $mensaje;
		if($mensaje['numero'] == "mensaje0") {
			//Procesamos la pregunta
			$datos = array(
				'texto' => Input::get('texto')
			);

			$validacion = array(
				'texto' => array('required', 'max:200')
			);
		}
		elseif($mensaje['numero'] == "mensaje1") {
			//Procesamos la pertenencia
			$datos = array(
				'agrupacion_id' => Input::get('agrupacion'),
				'familia_id' => Input::get('familia'),
				'subfamilia_id' => Input::get('subfamilia')
			);

			$validacion = array();
		}
		else {
			return Redirect::to("encuestas/pregunta/".$pregunta_id);
		}

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) {
			$errores = $validacion->messages();
			return View::make('encuestas.formulario', array('pregunta' => $pregunta, 'agrupacionACT' => $agrupacion, 'familiaACT' => $familia, 
			'subfamiliaACT' => $subfamilia, 'agrupaciones' => $agrupaciones, 'familias' => $familias, 'subfamilias' => $subfamilias, 
			'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else {
			if($mensaje['numero'] == "mensaje0") {
				//Cambiamos la pregunta
				$pregunta->texto = $datos['texto'];
			}
			elseif($mensaje['numero'] == "mensaje1") {
				//Cambiamos la pertenencia
				$pregunta->agrupacion_id = ($datos['agrupacion_id'] == 0)? null : $datos['agrupacion_id'];
				$pregunta->familia_id = ($datos['familia_id'] == 0)? null : $datos['familia_id'];
				$pregunta->subfamilia_id = ($datos['subfamilia_id'] == 0)? null : $datos['subfamilia_id'];
			}
			//Guardamos el usuario
			$pregunta->save();

			$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();
			if(!is_null($pregunta->agrupacion_id)) {
				$agrupacion = FamiliasAgrupacion::find($pregunta->agrupacion_id)->AgrupacionFamilia;
				$familias = Familia::where('IdAgrupacion', $pregunta->agrupacion_id)
								->orderBy('Familia', 'asc')->get();
				if(!is_null($pregunta->familia_id)) {
					$familia = Familia::find($pregunta->familia_id)->Familia;
					$subfamilias = Subfamilia::where('IdFamilia', $pregunta->familia_id)
								->orderBy('Subfamilia', 'asc')->get();
					if(!is_null($pregunta->subfamilia_id)) {
						$subfamilia = Subfamilia::find($pregunta->subfamilia_id)->Subfamilia;
					}
				}
			}

			$mensaje['error'] = false;
			return View::make('encuestas.formulario', array('pregunta' => $pregunta, 'agrupacionACT' => $agrupacion, 'familiaACT' => $familia, 
			'subfamiliaACT' => $subfamilia, 'agrupaciones' => $agrupaciones, 'familias' => $familias, 'subfamilias' => $subfamilias, 
			'mensaje' => $mensaje));
		}
	}

	public function formularioAdd() {
		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();
		
		return View::make('encuestas.formulario-add', array('agrupaciones' => $agrupaciones));
	}

	public function add() {
		$datos = array(
			'texto' => Input::get('texto'),
			'agrupacion_id' => Input::get('agrupacion'),
			'familia_id' => Input::get('familia'),
			'subfamilia_id' => Input::get('subfamilia')
		);

		$validacion = array(
			'texto' => array('required', 'max:200')
		);
		
		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) {
			return Redirect::to('encuestas/pregunta/add')
			->withErrors($validacion)
			->withInput();
		}
		else {
			PreguntaEncuesta::create(array(
				'texto'  => $datos['texto'],
				'agrupacion_id' => ($datos['agrupacion_id'] == 0)? null : $datos['agrupacion_id'],
				'familia_id' => ($datos['familia_id'] == 0)? null : $datos['familia_id'],
				'subfamilia_id' => ($datos['subfamilia_id'] == 0)? null : $datos['subfamilia_id'],
				'activa' => 1
			));

			$preguntas = PreguntaEncuesta::where('activa', 1)->get();
		
			return View::make('encuestas.listado', array('preguntas' => $preguntas, 'exito' => 'Se ha creado la pregunta con éxito'));
		}
	}
}