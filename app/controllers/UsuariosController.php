<?php
/*
|--------------------------------------------------------------------------
| Controlador de los usuarios
|--------------------------------------------------------------------------
| Controlador con las funciones relacionadas con los usuarios
|
*/
class UsuariosController extends BaseController {
	/**
	* Muestra la página con el listado de usuarios y su rol dentro de la aplicación
	* @return Vista usuarios.listado => usuarios
	*/
	public function listado() {
		// Obtenemos el listado de usuarios
		$usuarios = Usuario::select('id', 'nombre', 'rol_id', 'usuario')->orderBy('nombre', 'asc')->get();
		
		return View::make('usuarios.listado', array('usuarios' => $usuarios));
	}

	/**
	* Muestra la página con la información del usuario con el id seleccionado
	* @param int $usuario_id Identificador del usuario
	* @return Vista usuarios.formulario => usuario, roles
	*/
	public function usuario($usuario_id) {
		// Obtenemos el usuario con el id $usuario_id
		$usuario = Usuario::find($usuario_id);
		// Obtenemos los roles disponibles
		$roles = Rol::all();

		return View::make('usuarios.formulario', array('usuario' => $usuario, 'roles' => $roles));
	}

	/**
	* Muestra la página con los datos del usuario actual
	* @return Vista usuarios.formulario-individual => roles
	*/
	public function perfil() {
		$roles = Rol::all();

		return View::make('usuarios.formulario-individual', array('roles' => $roles));
	}

	/**
	* Procesa la edición del usuario con el id seleccionado
	* @param int $usuario_id Identificador del usuario
	* @return Si la validación es correcta -> Vista usuarios.formulario => usuario, roles, mensaje
	*		  Si la validación es errónea -> Vista usuarios.formulario => usuarios, roles, errores, mensaje
	*/
	public function editar($usuario_id) {
		$usuario = Usuario::find($usuario_id);
		$roles = Rol::all();

		$mensaje = array('numero' => Input::get('mensaje'), 'error' => true);
		//echo $mensaje;
		if($mensaje['numero'] == "mensaje0") {
			//Procesamos el nombre
			$datos = array(
				'nombre' => Input::get('nombre')
			);

			$validacion = array(
				'nombre' => array('required', 'max:100')
			);
		}
		elseif($mensaje['numero'] == "mensaje1") {
			//Procesamos el usuario
			$datos = array(
				'usuario' => Input::get('usuario')
			);

			$validacion = array(
				'usuario' => array('required', 'min:5', 'max:50', 'unique:Usuarios,usuario', 'alpha')
			);
		}
		elseif($mensaje['numero'] == "mensaje2") {
			//Procesamos el rol
			$datos = array(
				'rol_id' => Input::get('rol')
			);

			$validacion = array(
				'rol_id' => array('required', 'exists:Roles,id')
			);
		}
		elseif($mensaje['numero'] == "mensaje3") {
			//Procesamos la contraseña
			$datos = array(
				'pass' => Input::get('pass'),
				'pass2' => Input::get('pass2')
			);

			$validacion = array(
				'pass' => array('required', 'min:4', 'max:64', 'same:pass2')
			);
		}
		else {
			return Redirect::to("usuario/".$usuario_id);
		}

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) { // Los datos no son válidos
			$errores = $validacion->messages();
			return View::make('usuarios.formulario', array('usuario' => $usuario, 'roles' => $roles, 'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else { // Los datos son válidos
			if($mensaje['numero'] == "mensaje0") {
				//Cambiamos el nombre
				$usuario->nombre = $datos['nombre'];
			}
			elseif($mensaje['numero'] == "mensaje1") {
				//Cambiamos el usuario
				$usuario->usuario = $datos['usuario'];
			}
			elseif($mensaje['numero'] == "mensaje2") {
				//Cambiamos el rol
				$usuario->rol_id = $datos['rol_id'];
			}
			elseif($mensaje['numero'] == "mensaje3") {
				//Cambiamos la contraseña
				$usuario->password = Hash::make($datos['pass']);
			}
			//Guardamos el usuario
			$usuario->save();
			$mensaje['error'] = false;
			return View::make('usuarios.formulario', array('usuario' => $usuario, 'roles' => $roles, 'mensaje' => $mensaje));
		}
	}

	/**
	* Procesa la edición del usuario autenticado
	* @return Si la validación es correcta -> Vista usuarios.formulario-individual => roles, mensaje
	*		  Si la validación es errónea -> Vista usuarios.formulario-individual => roles, errores, mensaje
	*/
	public function editarPerfil() {
		$roles = Rol::all();
		$mensaje['error'] = true;
		
		//Procesamos la contraseña
		$datos = array(
			'pass_ant' => Input::get('pass_ant'),
			'pass' => Input::get('pass'),
			'pass2' => Input::get('pass2')
		);

		$validacion = array(
			'pass_ant' => array('required'),
			Hash::make('pass_ant') => array('exists:Usuarios,password,id,'.Auth::user()->id),
			'pass' => array('required', 'min:4', 'max:64', 'same:pass2')
		);

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) { // Los datos no son válidos
			$errores = $validacion->messages();
			return View::make('usuarios.formulario-individual', array('roles' => $roles, 'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else { // Los datos son válidos
			//Cambiamos la contraseña
			Auth::user()->password = Hash::make($datos['pass']);
			//Guardamos el usuario
			Auth::user()->save();
			$mensaje['error'] = false;
			return View::make('usuarios.formulario-individual', array('roles' => $roles, 'mensaje' => $mensaje));
		}
	}

	/**
	* Procesa la eliminación del usuario con el id seleccionado
	* @param int $usuario_id Identificador del usuario
	* @return Si el formulario es correcto -> Vista usuarios.listado => usuarios, exito
	*		  Si el formulario no es correcto -> Redirección a /usuarios
	*/
	public function eliminar($usuario_id) {
		$enviado = Input::get('borrar');

		if($enviado == "borrar") { // El formulario es correcto
			$usuario = Usuario::find($usuario_id);
			$usuario->delete();

			$usuarios = Usuario::select('id', 'nombre', 'rol_id', 'usuario')->orderBy('nombre', 'desc')->get();

			return View::make('usuarios.listado', array('usuarios' => $usuarios, 'exito' => 'Se ha eliminado el usuario con éxito'));
		}
		else { // El formulario no es correcto
			return Redirect::to('usuarios');
		}
	}

	/**
	* Muestra la página con el formulario para añadir usuarios
	* @return Vista usuarios.formulario-add => roles
	*/
	public function formularioAdd() {
		// Obtenemos los roles
		$roles = Rol::all();

		return View::make('usuarios.formulario-add', array('roles' => $roles));
	}

	/**
	* Procesamos el añadido de un nuevo usuario
	* @return Si la validación es correcta -> Vista usuarios.listado => usuarios, exito
	*		  Si la validación es errónea -> Redirección a /usuario/add con errores y datos de input
	*/
	public function add() {
		$datos = array(
			'nombre' => Input::get('nombre'),
			'usuario' => Input::get('usuario'),
			'rol_id' => Input::get('rol'),
			'pass' => Input::get('pass'),
			'pass2' => Input::get('pass2')
		);

		$validacion = array(
			'nombre' => array('required', 'max:100'),
			'usuario' => array('required', 'min:5', 'max:50', 'unique:Usuarios,usuario', 'alpha'),
			'rol_id' => array('required', 'exists:Roles,id'),
			'pass' => array('required', 'min:4', 'max:64', 'same:pass2')
		);
		
		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) { // Los datos no son válidos
			return Redirect::to('usuario/add')
						->withErrors($validacion)
						->withInput();
		}
		else { // Los datos son válidos
			// Creamos el usuario en la base de datos
			Usuario::create(array(
				'rol_id'  => $datos['rol_id'],
				'nombre' => $datos['nombre'],
				'usuario' => $datos['usuario'],
				'password' => Hash::make($datos['pass'])
			));
			// Obtenemos todos los usuarios
			$usuarios = Usuario::all();
			return View::make('usuarios.listado', array('usuarios' => $usuarios, 'exito' => 'Se ha creado el usuario con éxito'));
		}
	}
}