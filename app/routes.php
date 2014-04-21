<?php
// Mostramos el formulario de login
Route::get('login', 'AuthController@verLogin');

Route::get('encuesta/{numero}', 'EncuestasController@verEncuesta')
	->where('numero', '[0-9a-z]+');

Route::group(array('before' => 'csrf'), function()
{
	// Validamos los datos de inicio de sesión
	Route::post('login', 'AuthController@postLogin');
});
// Las rutas siguientes solo serán accesibles si el usuario está logueado
Route::group(array('before' => 'auth'), function()
{
	// Mostramos la pantalla de inicio
	Route::get('/', 'HomeController@inicial');

	Route::get('perfil', 'UsuariosController@perfil');

	// Cerramos la sesión
	Route::get('pruebas', 'HomeController@pruebas');
	Route::get('logout', 'AuthController@logout');

	Route::get('envios', 'EnviosController@listado');

	Route::group(array('before' => 'rol_admin'), function()
	{
		// Mostramos la pantalla de usuarios
		Route::get('usuarios', 'UsuariosController@listado');
		Route::get('usuario/{id}', 'UsuariosController@usuario')
			->where('id', '[0-9]+');
		//Route::get('usuario/{id}/eliminar', 'UsuariosController@eliminar')->where('id', '[0-9]+');
		Route::get('usuario/add', 'UsuariosController@formularioAdd');

		Route::get('mensajes', 'MensajesController@listado');
		Route::get('mensaje/{id}', 'MensajesController@mensaje')
			->where('id', '[0-9]+');
	});

	Route::group(array('before' => 'rol_vendedor'), function()
	{
		// Mostramos la pantalla de pedidos (100 últimos)
		Route::get('pedidos', 'PedidosController@listado');
		// Mostramos la pantalla de pedidos (todos)
		Route::get('_pedidos', 'PedidosController@mostrarTodos');
		// Editamos un pedido concreto
		Route::get('pedido/{id}', 'PedidosController@detalles')
			->where('id', '[0-9]+');
		Route::get('pedido/{id}/programar', 'PedidosController@verProgramar')
			->where('id', '[0-9]+');

		Route::get('envio/{id}', 'EnviosController@detalles')
			->where('id', '[0-9]+');
		Route::get('envio/{id}/programar', 'EnviosController@verProgramar')
			->where('id', '[0-9]+');
		Route::get('envio/{id}/entregado', 'EnviosController@entregado')
			->where('id', '[0-9]+');
		
		Route::get('encuestas/resultados', 'EncuestasController@resultados');	
		Route::get('encuestas/comentario/{id}', 'EncuestasController@verComentario')
		->where('id', '[0-9]+');
		Route::get('encuestas/preguntas', 'EncuestasController@listadoPreguntas');
		Route::get('encuestas/pregunta/{id}', 'EncuestasController@pregunta')
			->where('id', '[0-9]+');
		Route::get('encuestas/pregunta/add', 'EncuestasController@formularioAdd');

		Route::get('promociones', 'PromocionesController@listado');
		Route::get('promociones/cliente/{id}', 'PromocionesController@cliente')
			->where('id', '[0-9]+');
		Route::get('promociones/cliente/add', 'PromocionesController@formularioAdd');
		Route::get('promociones/enviar', 'PromocionesController@formularioEnviar');
	});

	Route::group(array('before' => 'csrf'), function()
	{
		Route::post('encuesta/{numero}', 'EncuestasController@procesarEncuesta')
			->where('numero', '[0-9a-z]+');

		Route::group(array('before' => 'rol_admin'), function()
		{
			Route::post('usuario/{id}', 'UsuariosController@editar')
				->where('id', '[0-9]+');
			Route::post('usuario/{id}/eliminar', 'UsuariosController@eliminar')
				->where('id', '[0-9]+');
			Route::post('usuario/add', 'UsuariosController@add');

			Route::post('mensaje/{id}', 'MensajesController@editar')
				->where('id', '[0-9]+');
		});

		Route::group(array('before' => 'rol_vendedor'), function()
		{
			Route::post('encuestas/preguntas', 'EncuestasController@listadoPreguntasFiltrado');
			Route::post('encuestas/pregunta/{id}', 'EncuestasController@editar')
				->where('id', '[0-9]+');
			Route::post('encuestas/pregunta/{id}/eliminar', 'EncuestasController@eliminar')
				->where('id', '[0-9]+');
			Route::post('encuestas/pregunta/add', 'EncuestasController@add');

			Route::post('promociones/cliente/add', 'PromocionesController@add');
			Route::post('promociones/cliente/{id}', 'PromocionesController@editar')
				->where('id', '[0-9]+');
			Route::post('promociones/cliente/{id}/eliminar', 'PromocionesController@eliminar')
				->where('id', '[0-9]+');
			Route::post('promociones/enviar', 'PromocionesController@enviar');

			Route::post('envio/{id}/programar', 'EnviosController@programar')
				->where('id', '[0-9]+');
			Route::post('envio/{id}/cancelar', 'EnviosController@cancelar')
				->where('id', '[0-9]+');

			Route::post('pedido/{id}/programar', 'PedidosController@programar')
				->where('id', '[0-9]+');
		});
		
		Route::post('perfil', 'UsuariosController@editarPerfil');
	});

	//Jsons
	Route::get('obtener_familias/{id}', 'EncuestasController@obtenerFamilias')
		->where('id', '[0-9]+');
	Route::get('obtener_subfamilias/{id}', 'EncuestasController@obtenerSubfamilias')
		->where('id', '[0-9]+');
	Route::get('obtener_resultados', 'EncuestasController@obtenerResultados');
});


Route::filter('rol_admin', function()
{
	if(Auth::user()->rol_id != 1) {
		return Redirect::to("/");
	}
});

Route::filter('rol_vendedor', function()
{
	if(Auth::user()->rol_id != 1 && Auth::user()->rol_id != 2) {
		return Redirect::to("/");
	}
});
?>