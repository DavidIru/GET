<?php
/*
|--------------------------------------------------------------------------
| Controlador de las encuestas
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con las encuestas
|
*/
class EncuestasController extends BaseController {
	/**
	* Muestra la página con las preguntas de las encuestas
	* @return Vista encuestas.listado => preguntas, agrupaciones
	*/
	public function listadoPreguntas() {
		// Obtenemos el listado de preguntas activas
		$preguntas = PreguntaEncuesta::select(DB::raw('Preguntas.id as id, Preguntas.texto as texto, avg(PreguntasEnvio.resultado) as media'))
							->join('PreguntasEnvio', 'Preguntas.id', '=', 'PreguntasEnvio.pregunta_id')
							->where('Preguntas.activa', 1)
							->groupBy('PreguntasEnvio.pregunta_id')
							->get();
		// Obtenemos el listado de agrupaciones
		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();
		// Mostramos la vista encuestas/listado.blade.php con el listado de preguntas y las agrupaciones (para filtrar)
		return View::make('encuestas.listado', array('preguntas' => $preguntas, 'agrupaciones' => $agrupaciones));
	}

	/**
	* Muestra la página con las preguntas filtradas de las encuestas
	* @return Vista encuestas.listado => preguntas, agrupaciones, filtro
	*/
	public function listadoPreguntasFiltrado() {
		// Obtenemos la agrupación, familia y subfamilia para filtrar
		$agrupacion = (Input::get('agrupacion') == 0)? null : Input::get('agrupacion');
		$familia = (Input::get('familia') == 0)? null : Input::get('familia');
		$subfamilia = (Input::get('subfamilia') == 0)? null : Input::get('subfamilia');
		$filtro = true;

		if($agrupacion == null) { // No se aplica filtro
			$preguntas = PreguntaEncuesta::select(DB::raw('Preguntas.id as id, Preguntas.texto as texto, avg(PreguntasEnvio.resultado) as media'))
							->join('PreguntasEnvio', 'Preguntas.id', '=', 'PreguntasEnvio.pregunta_id')
							->where('Preguntas.activa', 1)
							->groupBy('PreguntasEnvio.pregunta_id')
							->get();
			$filtro = false;
		}
		else {
			if($familia == null) { // Se aplica filtro por agrupación
				$preguntas = PreguntaEncuesta::select(DB::raw('Preguntas.id as id, Preguntas.texto as texto, avg(PreguntasEnvio.resultado) as media'))
								->join('PreguntasEnvio', 'Preguntas.id', '=', 'PreguntasEnvio.pregunta_id')
								->where('Preguntas.agrupacion_id', $agrupacion)
								->where('Preguntas.activa', 1)
								->groupBy('PreguntasEnvio.pregunta_id')
								->get();
			}
			else {
				if($subfamilia == null) { // Se aplica filtro por agrupación y familia
					$preguntas = PreguntaEncuesta::select(DB::raw('Preguntas.id as id, Preguntas.texto as texto, avg(PreguntasEnvio.resultado) as media'))
									->join('PreguntasEnvio', 'Preguntas.id', '=', 'PreguntasEnvio.pregunta_id')
									->where('Preguntas.agrupacion_id', $agrupacion)
									->where('Preguntas.familia_id', $familia)
									->where('Preguntas.activa', 1)
									->groupBy('PreguntasEnvio.pregunta_id')
									->get();
				}
				else { // Se aplica filtro por agrupación, familia y subfamilia
					$preguntas = PreguntaEncuesta::select(DB::raw('Preguntas.id as id, Preguntas.texto as texto, avg(PreguntasEnvio.resultado) as media'))
									->join('PreguntasEnvio', 'Preguntas.id', '=', 'PreguntasEnvio.pregunta_id')
									->where('Preguntas.agrupacion_id', $agrupacion)
									->where('Preguntas.familia_id', $familia)
									->where('Preguntas.subfamilia_id', $subfamilia)
									->where('Preguntas.activa', 1)
									->groupBy('PreguntasEnvio.pregunta_id')
									->get();
				}
			}
		}
		// Obtenemos el listado de agrupaciones
		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();
		// Mostramos la vista encuestas/listado.blade.php con el listado de preguntas, las agrupaciones (para filtrar) y el indicador de filtro activo
		return View::make('encuestas.listado', array('preguntas' => $preguntas, 'agrupaciones' => $agrupaciones, 'filtro' => $filtro));
	}

	/**
	* Muestra la página con los datos de la pregunta con el id seleccionado
	* @param int $pregunta_id Identificador de la pregunta
	* @return Vista encuestas.formulario => pregunta, agrupacionACT, familiaACT, subfamiliaACT
	*										agrupaciones, familias y subfamilias
	*/
	public function pregunta($pregunta_id) {
		// Obtenemos la pregunta con el id $pregunta_id y su nota media
		$pregunta = PreguntaEncuesta::select(DB::raw('Preguntas.*, avg(PreguntasEnvio.resultado) as media'))
							->join('PreguntasEnvio', 'Preguntas.id', '=', 'PreguntasEnvio.pregunta_id')
							->where('Preguntas.activa', 1)
							->where('Preguntas.id', $pregunta_id)
							->groupBy('PreguntasEnvio.pregunta_id')
							->firstOrFail();

		$agrupacion = null;
		$familia = null;
		$subfamilia = null;
		$agrupaciones = null;
		$familias = null;
		$subfamilias = null;

		// Obtenemos la agrupación, familia y subfamilia actual y el listado según los datos anteriores
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
		// Mostramos la vista encuestas.formulario con los datos de la pregunta, los valores de agrupación, familia y subfamilia actuales,
		// y los listados de agrupaciones, familias y subfamilias
		return View::make('encuestas.formulario', array('pregunta' => $pregunta, 'agrupacionACT' => $agrupacion, 'familiaACT' => $familia, 
			'subfamiliaACT' => $subfamilia, 'agrupaciones' => $agrupaciones, 'familias' => $familias, 'subfamilias' => $subfamilias));
	}

	/**
	* Obtiene un json con el listado de familias
	* @param int $agrupacion_id Identificador de la agrupación
	* @return Json con las familias
	*/
	public function obtenerFamilias($agrupacion_id) {
		// Obtenemos el listado de familias perteneciente a la agrupación $agrupacion_id
		$familias = Familia::where('IdAgrupacion', $agrupacion_id)->orderBy('Familia', 'asc')->get();
		// Devolvemos el listado en formato json
		return Response::json($familias);
	}

	/**
	* Obtiene un json con el listado de subfamilias
	* @param int $familia_id Identificador de la familia
	* @return Json con las subfamilias
	*/
	public function obtenerSubfamilias($familia_id) {
		// Obtenemos el listado de subfamilias perteneciente a la familia $familia_id
		$subfamilias = Subfamilia::where('IdFamilia', $familia_id)->orderBy('Subfamilia', 'asc')->get();
		// Devolvemos el listado en formato json
		return Response::json($subfamilias);
	}

	/**
	* Procesa la eliminación de la pregunta con el id seleccionado. La marca como inactiva para que no se utilice
	* pero no se borrar para mantener los resultados de todo el tiempo.
	* @param int $pregunta_id Identificador de la pregunta
	* @return Si el formulario es correcto -> Vista encuestas.listado => preguntas, agrupaciones (para filtrar), exito
	*		  Si el formulario no es correcto -> Redirección a /encuestas/preguntas
	*/
	public function eliminar($pregunta_id) {
		$enviado = Input::get('borrar');

		if($enviado == "borrar") { // El formulario es válido
			// Obtenemos la pregunta $pregunta_id
			$pregunta = PreguntaEncuesta::find($pregunta_id);
			// Indicamos que no está activa
			$pregunta->activa = 0;
			// Guardamos los cambios
			$pregunta->save();

			$preguntas = PreguntaEncuesta::where('activa', 1)->get();
			$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();

			return View::make('encuestas.listado', array('preguntas' => $preguntas, 'agrupaciones' => $agrupaciones, 'exito' => 'Se ha eliminado la pregunta con éxito'));
		}
		else { // El formulario no es válido
			return Redirect::to('encuestas/preguntas');
		}
	}

	/**
	* Procesa la edición de la pregunta con el id seleccionado
	* @param int $pregunta_id Identificador de la pregunta
	* @return Si el formulario es incorrecto -> Redirección a /encuesta/pregunta/pregunta_id
	*		  Sino -> Si los datos son válidos -> Vista encuestas.formulario =>pregunta, agrupacionACT, familiaACT
	*							subfamiliaACT, agrupaciones, familias, subfamilias y mensaje exito
	*				  Si no son válidos -> Vista encuestas.formulario =>pregunta, agrupacionACT, familiaACT,
	*							subfamiliaACT, agrupaciones, familias, subfamilias y mensaje error
	*/
	public function editar($pregunta_id) {
		// Obtenemos la pregunta con el id
		$pregunta = PreguntaEncuesta::find($pregunta_id);

		$agrupacion = null;
		$familia = null;
		$subfamilia = null;
		$agrupaciones = null;
		$familias = null;
		$subfamilias = null;

		$mensaje = array('numero' => Input::get('mensaje'), 'error' => true);

		if($mensaje['numero'] == "mensaje0") { // Se edita el texto de la pregunta
			//Procesamos la pregunta
			$datos = array(
				'texto' => Input::get('texto')
			);

			$validacion = array(
				'texto' => array('required', 'max:200')
			);
		}
		elseif($mensaje['numero'] == "mensaje1") { // Se edita la pertenencia de la pregunta
			//Procesamos la pertenencia
			$datos = array(
				'agrupacion_id' => Input::get('agrupacion'),
				'familia_id' => Input::get('familia'),
				'subfamilia_id' => Input::get('subfamilia')
			);

			$validacion = array();
		}
		else { // El formulario no es válido
			return Redirect::to("encuestas/pregunta/".$pregunta_id);
		}

		// Comprobamos que los datos introducidos sean válidos
		$validacion = Validator::make($datos, $validacion);
		
		if($validacion->fails()) { // Los datos no son válidos
			$errores = $validacion->messages();
			return View::make('encuestas.formulario', array('pregunta' => $pregunta, 'agrupacionACT' => $agrupacion, 'familiaACT' => $familia, 
				'subfamiliaACT' => $subfamilia, 'agrupaciones' => $agrupaciones, 'familias' => $familias, 'subfamilias' => $subfamilias, 
				'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else { // Los datos son válidos
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

	/**
	* Muestra el formulario para añadir preguntas
	* @return Vista encuestas.formulario-add => agrupaciones
	*/
	public function formularioAdd() {
		// Obtenemos el listado de agrupaciones
		$agrupaciones = FamiliasAgrupacion::orderBy('AgrupacionFamilia', 'asc')->get();
		
		return View::make('encuestas.formulario-add', array('agrupaciones' => $agrupaciones));
	}

	/**
	* Procesa el añadido de una pregunta
	* @return Si la validación es correcta -> Vista encuestas.listado => preguntas, agrupaciones, exito
	*		  Si la validación falla -> Redirección a /encuestas/pregunta/add con errores y valores de input
	*/
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
		 
		if($validacion->fails()) { // Los datos no son válidos
			return Redirect::to('encuestas/pregunta/add')
						->withErrors($validacion)
						->withInput();
		}
		else { // Los datos son válidos
			PreguntaEncuesta::create(array(
				'texto' => $datos['texto'],
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

	/**
	* Muestra la página con los resultados y los comentarios de las encuestas
	* @return Vista encuestas.resultados => comentarios, preguntas
	*/
	public function resultados() {
		// Obtenemos el listado de comentarios
		$comentarios = Comentario::select('id', 'comentario', 'leido')
							->orderBy('leido', 'asc')
							->orderBy('created_at', 'desc')
							->get();
		// Obtenemos el listado de preguntas y su nota media
		$preguntas = PreguntaEncuesta::select(DB::raw('Preguntas.id as id, Preguntas.texto as texto, avg(PreguntasEnvio.resultado) as media'))
							->join('PreguntasEnvio', 'Preguntas.id', '=', 'PreguntasEnvio.pregunta_id')
							->groupBy('PreguntasEnvio.pregunta_id')
							->orderBy('media', 'asc')
							->take(10)
							->get();

		return View::make('encuestas.resultados', array('comentarios' => $comentarios, 'preguntas' => $preguntas));
	}

	/**
	* Muestra la encuesta con la url /encuesta/numero
	* @param string $numero Código único de la encuesta
	* @return Vista encuestas.preguntas => encuesta
	*/
	public function verEncuesta($numero) {
		// Obtenemos la encuesta con el código $numero
		$encuesta = Encuesta::where('url', 'encuesta/'.$numero)
						->where('respondida', 0)
						->firstOrFail();

		return View::make('encuestas.preguntas', array('encuesta' => $encuesta));
	}

	/**
	* Procesa las respuestas de la encuesta
	* @param string $numero Código único de la encuesta
	* @return Si la validación es correcta -> Vista encuestas.respondida => encuesta
	*		  Si la validación es errónea -> Redirección a /encuesta/$numero con errores y datos introducidos
	*/
	public function procesarEncuesta($numero) {
		// Obtenemos la encuesta con el código $numero
		$encuesta = Encuesta::where('url', 'encuesta/'.$numero)
						->where('respondida', 0)
						->firstOrFail();
		// Obtenemos las pregunta de la encuesta seleccionada
		$preguntas = $encuesta->preguntas;

		$datos = array();
		$validacion = array();
		// Obtenemos las respuestas a las preguntas formuladas
		foreach($preguntas as $preguntaEnv) {
			$datos[$preguntaEnv->pregunta->id] =Input::get($preguntaEnv->pregunta->id);
			$validacion[$preguntaEnv->pregunta->id] = array('required', 'in:0,1,2,3,4,5,6,7,8,9,10');
		}
		// Obtenemos el comentario introducido
		$datos['comentario'] = Input::get('comentario');
		// Validamos los datos
		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) { // Los datos no son válidos
			return Redirect::to('encuesta/'.$numero)
					->withErrors($validacion)
					->withInput();
		}
		else { // LOs datos son válidos
			//Guardamos los datos
			foreach($preguntas as $preguntaEnv) {
				$preguntaEnv->resultado = $datos[$preguntaEnv->pregunta->id];
				$preguntaEnv->save();
			}

			if(strlen($datos['comentario']) > 0) { // El comentario no está en blanco
				// Creamos un nuevo registro en la tabla de comentarios
				Comentario::create(array(
					'encuesta_id'  => $encuesta->id,
					'comentario' => $datos['comentario']
				));
			}
			// Indicamos que la encuesta ha sido respondida
			$encuesta->respondida = 1;
			// Guardamos la encuesta
			$encuesta->save();

			return View::make('encuestas.respondida', array('encuesta' => $encuesta));
		}
	}

	/**
	* Obtiene un json con la nota media mensual de las preguntas de las encuestas respondidas
	* @return Json con las notas medias por meses
	*/
	public function obtenerResultados() {
		// Obtenemos la media de los resultados de las preguntas de las encuestas respondidas por meses
		$resultados = PreguntasEnvio::select(DB::raw('PreguntasEnvio.updated_at as date, avg(resultado) as avg'))
							->join('Encuestas', 'Encuestas.id', '=', 'PreguntasEnvio.encuesta_id')
							->where('Encuestas.respondida', 1)
							->orderBy('PreguntasEnvio.updated_at', 'asc')
							->groupBy(DB::raw("YEAR(PreguntasEnvio.updated_at), MONTH(PreguntasEnvio.updated_at), DAY(PreguntasEnvio.updated_at)"))
							->get();
		$respuesta = array();

		foreach($resultados as $resultado) {
			// Convertimos el tiempo al formato correcto para el script de gráficos
			$tiempo = strtotime($resultado->date)*1000;
			array_push($respuesta, array($tiempo, (float)$resultado->avg));
		}

		return Response::json($respuesta);
	}

	/**
	* Obtiene un json con la nota media mensual de la pregunta seleccionada de las encuestas respondidas
	* @param int $pregunta_id Identificador de la pregunta
	* @return Json con la nota media de la pregunta por meses
	*/
	public function obtenerResultadosPregunta($pregunta_id) {
		$resultados = PreguntasEnvio::select(DB::raw('PreguntasEnvio.updated_at as date, avg(resultado) as avg'))
							->join('Encuestas', 'Encuestas.id', '=', 'PreguntasEnvio.encuesta_id')
							->where('Encuestas.respondida', 1)
							->where('PreguntasEnvio.pregunta_id', $pregunta_id)
							->orderBy('PreguntasEnvio.updated_at', 'asc')
							->groupBy(DB::raw("YEAR(PreguntasEnvio.updated_at), MONTH(PreguntasEnvio.updated_at), DAY(PreguntasEnvio.updated_at)"))
							->get();
		$respuesta = array();

		foreach($resultados as $resultado) {
			// Convertimos el tiempo al formato correcto para el script de gráficos
			$tiempo = strtotime($resultado->date)*1000;
			array_push($respuesta, array($tiempo, (float)$resultado->avg));
		}

		return Response::json($respuesta);
	}

	/**
	* Muestra el comentario con el id seleccionado
	* @param int $comentario_id Identificador del comentario
	* @return Vista encuestas.comentario => comentario, leido
	*/
	public function verComentario($comentario_id) {
		$comentario = Comentario::select('id', 'comentario', 'leido', 'updated_at')
							->find($comentario_id);
		
		if($comentario->leido == 0) {
			//Guardamos el comentario y en estado para enviarlo a la vista
			$leido = 0;
			$comentario->leido = 1;
			$comentario->save();
		}
		else
			$leido = 1;

		return View::make('encuestas.comentario', array('comentario' => $comentario, 'leido' => $leido));
	}
}