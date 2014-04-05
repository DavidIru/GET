<?php 
class PedidosController extends BaseController {

    public function inicial() {
        $pedidos = Pedido::all();

        return View::make('pedidos.listado', array('pedidos' => $pedidos));
    }
 
}
?> 