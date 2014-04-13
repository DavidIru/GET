<?php 
class EnviosController extends BaseController {

    public function listado() {
        $envios = Envio::select('IdDocumento', 'NumeroDocumento', 'CLNombre', 'CLTelefono')->orderBy('IdDocumento', 'desc')->take(100)->get();

        return View::make('pedidos.listado', array('pedidos' => $pedidos, 'todos' => false));
    }
}