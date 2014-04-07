<?php
// Mostramos el formulario de login
Route::get('login', 'AuthController@verLogin');

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
    Route::get('pedido/{id}', 'PedidosController@detalles')
    ->where('id', '[0-9]+');

	Route::group(array('before' => 'rol'), function()
	{
	    // Mostramos la pantalla de usuarios
	    Route::get('usuarios', 'UsuariosController@mostrarTodos');
	    Route::get('usuario/{id}', 'UsuariosController@usuario')
	    ->where('id', '[0-9]+');
	    Route::get('usuario/{id}/eliminar', 'UsuariosController@eliminar')
	    ->where('id', '[0-9]+');
	    Route::get('usuario/add', 'UsuariosController@formularioAdd');
	});

    Route::get('perfil', 'UsuariosController@perfil');

    // Cerramos la sesión
    Route::get('pruebas', 'HomeController@pruebas');
    Route::get('logout', 'AuthController@logout');

    Route::group(array('before' => 'csrf'), function()
	{
		Route::group(array('before' => 'rol'), function()
		{
		    Route::post('usuario/{id}', 'UsuariosController@editar')
		    ->where('id', '[0-9]+');
		    Route::post('usuario/{id}/eliminar', 'UsuariosController@eliminar')
		    ->where('id', '[0-9]+');
		    Route::post('usuario/add', 'UsuariosController@add');
		});
		
		Route::post('perfil', 'UsuariosController@editarPerfil');
	});
});

Route::group(array('before' => 'csrf'), function()
{
    // Validamos los datos de inicio de sesión
    Route::post('login', 'AuthController@postLogin');
});

Route::filter('rol', function()
{
	if(Auth::user()->rol_id != 1) {
		return Redirect::to("/");
	}
});
/*
Route::get('albaranes', array('uses' => 'AlbaranesController@mostrarAlbaranes'));

Route::get('/', function()
{
	return View::make('usuarios.login');
});
*/
?>