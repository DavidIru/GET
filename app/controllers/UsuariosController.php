<?php 
class UsuariosController extends BaseController {
    public function mostrarTodos() {
		$usuarios = Usuario::select('id', 'nombre', 'rol_id', 'usuario')->orderBy('nombre', 'desc')->get();
		
		return View::make('usuarios.listado', array('usuarios' => $usuarios));
    }

    public function usuario($usuario_id) {
    	$usuario = Usuario::find($usuario_id);

    	return View::make('usuarios.formulario', array('usuario' => $usuario));
    }
}