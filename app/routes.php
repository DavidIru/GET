<?php
// Mostramos el formulario de login
Route::get('login', 'AuthController@verLogin');

// Validamos los datos de inicio de sesión
Route::post('login', 'AuthController@postLogin');

// Las rutas siguientes solo serán accesibles si el usuario está logueado
Route::group(array('before' => 'auth'), function()
{
    // Mostramos la pantalla de inicio
    Route::get('/', 'HomeController@inicial');
    // Mostramos la pantalla de pedidos (100 últimos)
    Route::get('pedidos', 'PedidosController@inicial');
    // Mostramos la pantalla de pedidos (todos)
    Route::get('_pedidos', 'PedidosController@mostrarTodos');
    // Editamos un pedido concreto
    Route::get('pedido/{id}', 'PedidosController@editar')
    ->where('id', '[0-9]+');
    /*
    	Route::get('/', function()
	    {
	        return View::make('home');
	    });
	*/
    // Cerramos la sesión
    Route::get('pruebas', 'HomeController@pruebas');
    Route::get('logout', 'AuthController@logout');
});
/*
Route::get('albaranes', array('uses' => 'AlbaranesController@mostrarAlbaranes'));

Route::get('/', function()
{
	return View::make('usuarios.login');
});
*/
?>