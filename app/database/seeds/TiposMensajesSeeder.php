<?php

class TiposMensajesSeeder extends Seeder {
 
    public function run()
    {
    	TiposMensaje::create(array(
            'nombre' => 'SMS Pedido Asignado'
        ));

        TiposMensaje::create(array(
            'nombre' => 'SMS Pedido Entregado'
        ));
    }
 
}
?>