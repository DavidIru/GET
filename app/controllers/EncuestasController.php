<?php 
class EncuestasController extends BaseController {
	public function listadoPreguntas() {
		$preguntas = PreguntaEncuesta::where('activa', 1)->get();
		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();

		return View::make('encuestas.listado', array('preguntas' => $preguntas, 'agrupaciones' => $agrupaciones));
	}

	public function listadoPreguntasFiltrado() {
		$agrupacion = (Input::get('agrupacion') == 0)? null : Input::get('agrupacion');
		$familia = (Input::get('familia') == 0)? null : Input::get('familia');
		$subfamilia = (Input::get('subfamilia') == 0)? null : Input::get('subfamilia');
		$filtro = true;

		if($agrupacion == null) {
			$preguntas = PreguntaEncuesta::where('activa', 1)->get();
			$filtro = false;
		}
		else {
			if($familia == null) {
				$preguntas = PreguntaEncuesta::where('agrupacion_id', $agrupacion)->where('activa', 1)->get();
			}
			else {
				if($subfamilia == null) {
					$preguntas = PreguntaEncuesta::where('agrupacion_id', $agrupacion)->where('familia_id', $familia)
							->where('activa', 1)->get();
				}
				else {
					$preguntas = PreguntaEncuesta::where('agrupacion_id', $agrupacion)->where('familia_id', $familia)
							->where('subfamilia_id', $subfamilia)->where('activa', 1)->get();
				}
			}
		}
		
		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();

		return View::make('encuestas.listado', array('preguntas' => $preguntas, 'agrupaciones' => $agrupaciones, 'filtro' => $filtro));
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
			$agrupacion = $pregunta->agrupacion->AgrupacionFamilia;
			$familias = Familia::where('IdAgrupacion', $pregunta->agrupacion_id)
							->orderBy('Familia', 'asc')->get();
			if(!is_null($pregunta->familia_id)) {
				$familia = $pregunta->familia->Familia;
				$subfamilias = Subfamilia::where('IdFamilia', $pregunta->familia_id)
							->orderBy('Subfamilia', 'asc')->get();
				if(!is_null($pregunta->subfamilia_id)) {
					$subfamilia = $pregunta->subfamilia->Subfamilia;
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
			$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();

			return View::make('encuestas.listado', array('preguntas' => $preguntas, 'agrupaciones' => $agrupaciones, 'exito' => 'Se ha eliminado la pregunta con éxito'));
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
				$agrupacion = $pregunta->agrupacion->AgrupacionFamilia;
				$familias = Familia::where('IdAgrupacion', $pregunta->agrupacion_id)
								->orderBy('Familia', 'asc')->get();
				if(!is_null($pregunta->familia_id)) {
					$familia = $pregunta->familia->Familia;
					$subfamilias = Subfamilia::where('IdFamilia', $pregunta->familia_id)
								->orderBy('Subfamilia', 'asc')->get();
					if(!is_null($pregunta->subfamilia_id)) {
						$subfamilia = $pregunta->subfamilia->Subfamilia;
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
			$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();

			return View::make('encuestas.listado', array('preguntas' => $preguntas, 'agrupaciones' => $agrupaciones, 'exito' => 'Se ha creado la pregunta con éxito'));
		}
	}

	public function resultados() {
		echo "a";
	}

	public function verEncuesta($numero) {
		$encuesta = Encuesta::where('url', 'encuesta/'.$numero)
						->where('respondida', 0)
						->firstOrFail();

		return View::make('encuestas.preguntas', array('encuesta' => $encuesta));
	}

	public function procesarEncuesta($numero) {
		$encuesta = Encuesta::where('url', 'encuesta/'.$numero)->firstOrFail();
		$preguntas = $encuesta->preguntas;

		$datos = array();
		$validacion = array();
		foreach($preguntas as $preguntaEnv) {
			$datos[$preguntaEnv->pregunta->id] =Input::get($preguntaEnv->pregunta->id);
			$validacion[$preguntaEnv->pregunta->id] = array('required', 'in:0,1,2,3,4,5,6,7,8,9,10');
		}
		$datos['comentario'] = Input::get('comentario');
		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) {
			return Redirect::to('encuesta/'.$numero)
			->withErrors($validacion)
			->withInput();
		}
		else {
			//Guardamos los datos
			foreach($preguntas as $preguntaEnv) {
				$preguntaEnv->resultado = $datos[$preguntaEnv->pregunta->id];
				$preguntaEnv->save();
			}
			if(strlen($datos['comentario']) > 0) {
				Comentario::create(array(
					'encuesta_id'  => $encuesta->id,
					'comentario' => $datos['comentario']
				));
			}

			$encuesta->respondida = 1;
			$encuesta->save();

			return View::make('encuestas.respondida', array('encuesta' => $encuesta));
		}
	}
}