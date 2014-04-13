<?php 
class PedidosController extends BaseController {

    public function listado() {
        $pedidos = Pedido::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'CLTelefono')->orderBy('IdDocumento', 'desc')->take(100)->get();

        return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => false));
    }

    public function detalles($pedido_id) {
    	$pedido = Pedido::select('NumeroDocumento', 'FechaDocumento', 'CLNombre', 'CLNombreEnvio', 'CLDireccionEnvio', 'CLCiudadEnvio', 'CLProviniciaEnvio', 'CLCodPostalEnvio', 'CLTelefonoEnvio', 'Situacion', 'ImporteAcuenta', 'DescripcionFormaPagoDocumento')->find($pedido_id);
        $productos = PedidosDetalle::select('ArticuloDescripcion', 'Cantidad', 'Precio')->where('NumeroDocumento', $pedido->NumeroDocumento)->get();

    	return View::make('pedidos.formulario', array('pedido' => $pedido, 'productos' => $productos));
    }

    public function mostrarTodos() {
        $pedidos = Pedido::orderBy('IdDocumento', 'desc')->get();

        return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => true));
    }
}