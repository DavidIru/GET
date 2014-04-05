<?php 
class PedidosController extends BaseController {

    public function inicial() {
        $pedidos = Pedido::orderBy('IdDocumento', 'desc')->take(100)->get();

        return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => false));
    }

    public function editar($pedido_id) {
    	$pedido = Pedido::find($pedido_id);

    	return $pedido->NumeroDocumento;
    }

    public function mostrarTodos() {
        $pedidos = Pedido::orderBy('IdDocumento', 'desc')->get();

        return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => true));
    }
}
?> 