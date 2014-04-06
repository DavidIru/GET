<?php 
class AlbaranesController extends BaseController {
 
    /**
     * Mustra la lista con todos los usuarios
     */
    public function mostrarAlbaranes()
    {
        $albaranes = Albaran::all();
        $albaran = Albaran::find('6');
        /*
        
        $albaranes = Albaran::where('IdDocumento', '=', '6')->take(1)->get();

        echo $albaranes[0]->NumeroDocumento;

        $albaranes[0]->NumeroDocumento = "A-10-0001";

        $albaranes[0]->save();

        DB::table('albaranes')
            ->where('IdDocumento', '6')
            ->update(array('NumeroDocumento' => $albaranes[0]->NumeroDocumento));
        */
        // Con el método all() le estamos pidiendo al modelo de Usuario
        // que busque todos los registros contenidos en esa tabla y los devuelva en un Array
        
        return View::make('albaranes.lista', array('albaranes' => $albaranes));
        
        // El método make de la clase View indica cual vista vamos a mostrar al usuario 
        //y también pasa como parámetro los datos que queramos pasar a la vista. 
        // En este caso le estamos pasando un array con todos los usuarios
    }
 
}