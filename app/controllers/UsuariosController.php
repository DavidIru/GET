<?php 
class UsuariosController extends BaseController {
    public function mostrarTodos() {
		$usuarios = Usuario::select('id', 'nombre', 'rol_id', 'usuario')->orderBy('nombre', 'asc')->get();
		
		return View::make('usuarios.listado', array('usuarios' => $usuarios));
    }

	public function usuario($usuario_id) {
    	$usuario = Usuario::find($usuario_id);
    	$roles = Rol::all();

    	return View::make('usuarios.formulario', array('usuario' => $usuario, 'roles' => $roles));
    }

    public function perfil() {
    	$roles = Rol::all();

    	return View::make('usuarios.formulario-individual', array('roles' => $roles));
    }

	public function editar($usuario_id) {
		$usuario = Usuario::find($usuario_id);
    	$roles = Rol::all();

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
		 
		if($validacion->fails()) {
		    $errores = $validacion->messages();
		    return View::make('usuarios.formulario', array('usuario' => $usuario, 'roles' => $roles, 'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else {
		    if($mensaje['numero'] == "mensaje0") {
				//Cambiamos el usuario
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
        	Hash::make('pass_ant') => array('exists:Usuarios,password,id,'.Auth::user()->id),
    		'pass' => array('required', 'min:4', 'max:64', 'same:pass2')
    	);

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) {
		    $errores = $validacion->messages();
		    return View::make('usuarios.formulario-individual', array('roles' => $roles, 'errores' => $errores->all(), 'mensaje' => $mensaje));
		}
		else {
			//Cambiamos la contraseña
			Auth::user()->password = Hash::make($datos['pass']);
			//Guardamos el usuario
			Auth::user()->save();
			$mensaje['error'] = false;
			return View::make('usuarios.formulario-individual', array('roles' => $roles, 'mensaje' => $mensaje));
		}
	}

	public function eliminar($usuario_id) {
		$enviado = Input::get('borrar');

		if($enviado == "borrar") {
			$usuario = Usuario::find($usuario_id);
			$usuario->delete();

			$usuarios = Usuario::select('id', 'nombre', 'rol_id', 'usuario')->orderBy('nombre', 'desc')->get();

			return View::make('usuarios.listado', array('usuarios' => $usuarios, 'exito' => 'Se ha eliminado el usuario con éxito'));
		}
		else {
			return Redirect::to('usuarios');
		}
	}

	public function formularioAdd() {
		$roles = Rol::all();

		return View::make('usuarios.formulario-add', array('roles' => $roles));
	}

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
		 
		if($validacion->fails()) {
		    //$errores = $validacion->messages();
		    //return View::make('usuarios.formulario', array('usuario' => $usuario, 'roles' => $roles, 'errores' => $errores->all(), 'mensaje' => $mensaje));
			return Redirect::to('usuario/add')
			->withErrors($validacion)
			->withInput();
		}
		else {
			Usuario::create(array(
	            'rol_id'  => $datos['rol_id'],
	            'nombre' => $datos['nombre'],
	            'usuario' => $datos['usuario'],
	            'password' => Hash::make($datos['pass'])
	        ));

			$usuarios = Usuario::all();
	        return View::make('usuarios.listado', array('usuarios' => $usuarios, 'exito' => 'Se ha creado el usuario con éxito'));
		}
	}
}