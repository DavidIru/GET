<?php 
class PedidosController extends BaseController {

    public function listado() {
        $pedidos = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'CLTelefono')
                            ->orderBy('IdDocumento', 'desc')
                            ->take(100)
                            ->get();

        return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => false));
    }

    public function detalles($pedido_id) {
    	$pedido = Pedido::select('IdDocumento', 'NumeroDocumento', 'FechaDocumento', 'CLNombre', 'CLNombreEnvio', 
                            'CLDireccionEnvio', 'CLCiudadEnvio', 'CLProviniciaEnvio', 'CLCodPostalEnvio', 
                            'CLTelefonoEnvio', 'Situacion', 'ImporteAcuenta', 'DescripcionFormaPagoDocumento', 
                            'FechaEntrega', 'HoraEntrega')
                            ->find($pedido_id);
        $productos = PedidosDetalle::select('ArticuloDescripcion', 'Cantidad', 'Precio')
                            ->where('NumeroDocumento', $pedido->NumeroDocumento)
                            ->get();
        $exito = Session::get('exito', false);
    	return View::make('pedidos.formulario', array('pedido' => $pedido, 'productos' => $productos, 'exito' => $exito));
    }

    public function mostrarTodos() {
        $pedidos = Pedido::orderBy('IdDocumento', 'desc')->get();

        return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => true));
    }

    public function verProgramar($pedido_id) {
        $pedido_id = Pedido::select('IdDocumento', 'NumeroDocumento', 'FechaEntrega', 'HoraEntrega')
                            ->whereNull('Situacion')
                            ->where('IdDocumento', $pedido_id)
                            ->firstOrFail();
        return View::make('pedidos.programar', array('pedido' => $pedido_id));
    }

    public function programar($pedido_id) {
        //Redirigir a envíos y mostrar mensaje de confirmación
        $pedido = Pedido::find($pedido_id);
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
            return View::make('pedidos.programar', array('pedido' => $pedido, 'errores' => $errores->all()));
        }
        else {
            $pedido->FechaEntrega = $datos['fecha']." ".$datos['hora'];
            $pedido->HoraEntrega = $datos['fecha']." ".$datos['hora'];
            //Guardamos el envío
            $pedido->save();
            if($datos['avisar']) {
                //Avisamos al cliente
                echo "avisar";
            }
            return Redirect::to(URL::to('envio/'.$pedido->IdDocumento))
                                ->with('exito', true);
        }
    }
}