<?php 
class EnviosController extends BaseController {

	public function listado() {
		$envios = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'FechaEntrega', 'HoraEntrega')
							->whereNull('Situacion')
							->whereRaw('(FechaEntrega is not NULL and HoraEntrega is not NULL)')
							->orderBy('FechaEntrega', 'asc')
							->orderBy('HoraEntrega', 'asc')->get();
		$exito = Session::get('exito', false);
		return View::make('envios.listado', array('envios' => $envios, 'exito' => $exito));
	}

	public function detalles($envio_id) {
		$envio = Pedido::select('IdDocumento', 'NumeroDocumento', 'FechaDocumento', 'CLNombre', 'CLNombreEnvio', 
							'CLDireccionEnvio', 'CLCiudadEnvio', 'CLProviniciaEnvio', 'CLCodPostalEnvio', 
							'CLTelefonoEnvio', 'Situacion', 'ImporteAcuenta', 'DescripcionFormaPagoDocumento', 
							'FechaEntrega', 'HoraEntrega')
							->whereNull('Situacion')
							->whereRaw('(FechaEntrega is not NULL and HoraEntrega is not NULL)')
							->where('IdDocumento', $envio_id)
							->firstOrFail();
		$productos = PedidosDetalle::select('ArticuloDescripcion', 'Cantidad', 'Precio')
							->where('NumeroDocumento', $envio->NumeroDocumento)
							->get();
		$exito = Session::get('exito', false);
		return View::make('envios.formulario', array('envio' => $envio, 'productos' => $productos, 'exito' => $exito));
	}

	public function verProgramar($envio_id) {
		$envio = Pedido::select('IdDocumento', 'NumeroDocumento', 'FechaEntrega', 'HoraEntrega')
							->whereNull('Situacion')
							->whereRaw('(FechaEntrega is not NULL and HoraEntrega is not NULL)')
							->where('IdDocumento', $envio_id)
							->firstOrFail();
		return View::make('envios.programar', array('envio' => $envio));
	}

	public function programar($envio_id) {
		//Redirigir a envíos y mostrar mensaje de confirmación
		$envio = Pedido::find($envio_id);
		$datos = array(
            'fecha' => Input::get('envio_fecha'),
            'hora' => Input::get('hora'),
            'avisar' => (Input::get('avisarp'))? true : false
        );

        $validacion = array(
    		'fecha' => array('required', 'date'),
    		'hora' => array('required', 'dateformat:H:i')
    	);

		$validacion = Validator::make($datos, $validacion);
		 
		if($validacion->fails()) {
		    $errores = $validacion->messages();
		    return View::make('envios.programar', array('envio' => $envio, 'errores' => $errores->all()));
		}
		else {
		    $envio->FechaEntrega = $datos['fecha']." ".$datos['hora'];
		    $envio->HoraEntrega = $datos['fecha']." ".$datos['hora'];
		    //Guardamos el envío
			$envio->save();
			if($datos['avisar']) {
				//Avisamos al cliente
				echo "avisar";
			}
			return Redirect::to(URL::to('envio/'.$envio->IdDocumento))
								->with('exito', true);
		}
	}

	public function cancelar($envio_id) {
		//Redirigir a pedidos y mostrar mensaje de cancelación
		$envio = Pedido::find($envio_id);

		$datos = array(
            'avisar' => (Input::get('avisarc'))? true : false
        );
        
		$envio->FechaEntrega = NULL;
	    $envio->HoraEntrega = NULL;
	    //Guardamos el envío
		$envio->save();
		if($datos['avisar']) {
			//Avisamos al cliente
			echo "avisar";
		}
		return Redirect::to(URL::to('pedido/'.$envio->IdDocumento))
							->with('exito', true);
	}

	public function entregado($envio_id) {
		//Redirigir a pedidos y mostrar mensaje de cancelación
		$envio = Pedido::whereNull('Situacion')
						->whereRaw('(FechaEntrega is not NULL and HoraEntrega is not NULL)')
						->where('IdDocumento', $envio_id)
						->firstOrFail();
        
		$envio->Situacion = "Entregado";
	    //Guardamos el envío
		$envio->save();

		//Generar encuesta
		
		return Redirect::to(URL::to('envios'))
							->with('exito', true);
	}
}