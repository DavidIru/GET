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
}