<?php
// Mostramos el formulario de login
Route::get('login', 'AuthController@verLogin');
// Mostramos la encuesta del envío
Route::get('encuesta/{numero}', 'EncuestasController@verEncuesta')
	->where('numero', '[0-9a-z]+');

// Comprobamos que se ha enviado el CSRF
Route::group(array('before' => 'csrf'), function()
{
	// Validamos los datos de inicio de sesión
	Route::post('login', 'AuthController@postLogin');
});

// Comprobamos que el usuario está logueado
Route::group(array('before' => 'auth'), function()
{
	// Mostramos la pantalla de inicio
	Route::get('/', 'HomeController@inicial');
	// Mostramos el perfil del usuario logueado
	Route::get('perfil', 'UsuariosController@perfil');
	// Cerramos la sesión
	Route::get('logout', 'AuthController@logout');
	// Mostramos la sección de envíos
	Route::get('envios', 'EnviosController@listado');

	// Comprobamos que el usuario es administrador
	Route::group(array('before' => 'rol_admin'), function()
	{
		// Mostramos el listado de usuarios
		Route::get('usuarios', 'UsuariosController@listado');
		// Mostramos el usuario con el id seleccionado
		Route::get('usuario/{id}', 'UsuariosController@usuario')
			->where('id', '[0-9]+');
		// Mostramos el formulario para añadir usuarios
		Route::get('usuario/add', 'UsuariosController@formularioAdd');
		// Mostramos el listado de mensajes
		Route::get('mensajes', 'MensajesController@listado');
		// Mostramos el mensaje con el id seleccionado
		Route::get('mensaje/{id}', 'MensajesController@mensaje')
			->where('id', '[0-9]+');
	});

	// Comprobamos que el usuario es vendedor
	Route::group(array('before' => 'rol_vendedor'), function()
	{
		// Mostramos la pantalla de pedidos (100 últimos)
		Route::get('pedidos', 'PedidosController@listado');
		// Mostramos la pantalla de pedidos (todos)
		Route::get('_pedidos', 'PedidosController@mostrarTodos');
		// Mostramos el pedido con el id seleccionado
		Route::get('pedido/{id}', 'PedidosController@detalles')
			->where('id', '[0-9]+');
		// Programamos el pedido con el id seleccionado
		Route::get('pedido/{id}/programar', 'PedidosController@verProgramar')
			->where('id', '[0-9]+');
		// Mostramos el listado de envíos programados
		Route::get('envio/{id}', 'EnviosController@detalles')
			->where('id', '[0-9]+');
		// Reprogramamos el envío con el id seleccionado
		Route::get('envio/{id}/programar', 'EnviosController@verProgramar')
			->where('id', '[0-9]+');
		// Indicamos que el pedido con el id seleccionado ha sido entregado
		Route::get('envio/{id}/entregado', 'EnviosController@entregado')
			->where('id', '[0-9]+');
		// Mostramos los resultados de las encuestas
		Route::get('encuestas/resultados', 'EncuestasController@resultados');
		// Mostramos el comentario con el id seleccionado
		Route::get('encuestas/comentario/{id}', 'EncuestasController@verComentario')
		->where('id', '[0-9]+');
		// Mostramos el listado de preguntas de las encuestas
		Route::get('encuestas/preguntas', 'EncuestasController@listadoPreguntas');
		// Mostramos la pregunta con el id seleccionado
		Route::get('encuestas/pregunta/{id}', 'EncuestasController@pregunta')
			->where('id', '[0-9]+');
		// Mostramos el formulario para añadir preguntas
		Route::get('encuestas/pregunta/add', 'EncuestasController@formularioAdd');
		// Mostramos la sección de promociones
		Route::get('promociones', 'PromocionesController@listado');
		// Mostramos el cliente inscrito en las promociones con el id seleccionado
		Route::get('promociones/cliente/{id}', 'PromocionesController@cliente')
			->where('id', '[0-9]+');
		// Mostramos el formulario para añadir clientes a las promociones
		Route::get('promociones/cliente/add', 'PromocionesController@formularioAdd');
		// Mostramos la sección para enviar promociones
		Route::get('promociones/enviar', 'PromocionesController@formularioEnviar');
	});

	//Comprobamos que se ha enviado el CSRF
	Route::group(array('before' => 'csrf'), function()
	{
		// Procesamos la respuesta de la encuesta
		Route::post('encuesta/{numero}', 'EncuestasController@procesarEncuesta')
			->where('numero', '[0-9a-z]+');

		// Comprobamos que el usuario es administrador
		Route::group(array('before' => 'rol_admin'), function()
		{
			// Procesamos los cambios en el usuario con el id seleccionado
			Route::post('usuario/{id}', 'UsuariosController@editar')
				->where('id', '[0-9]+');
			// Procesamos la eliminación del usuario con el id seleccionado
			Route::post('usuario/{id}/eliminar', 'UsuariosController@eliminar')
				->where('id', '[0-9]+');
			// Procesamos el nuevo usuario
			Route::post('usuario/add', 'UsuariosController@add');
			// Procesamos los cambios en el mensaje con el id seleccionado
			Route::post('mensaje/{id}', 'MensajesController@editar')
				->where('id', '[0-9]+');
		});

		// Comprobamos que el usuario es vendedor
		Route::group(array('before' => 'rol_vendedor'), function()
		{
			// Procesamos el filtro en las preguntas de las encuestas
			Route::post('encuestas/preguntas', 'EncuestasController@listadoPreguntasFiltrado');
			// Procesamos los cambios en la pregunta con el id seleccionado
			Route::post('encuestas/pregunta/{id}', 'EncuestasController@editar')
				->where('id', '[0-9]+');
			// Procesamos la eliminación de la pregunta con el id seleccionado
			Route::post('encuestas/pregunta/{id}/eliminar', 'EncuestasController@eliminar')
				->where('id', '[0-9]+');
			// Procesamos la nueva pregunta
			Route::post('encuestas/pregunta/add', 'EncuestasController@add');
			// Procesamos el nuevo cliente
			Route::post('promociones/cliente/add', 'PromocionesController@add');
			// Procesamos los cambios en el cliente con el id seleccionado
			Route::post('promociones/cliente/{id}', 'PromocionesController@editar')
				->where('id', '[0-9]+');
			// Procesamos la eliminación del cliente con el id seleccionado
			Route::post('promociones/cliente/{id}/eliminar', 'PromocionesController@eliminar')
				->where('id', '[0-9]+');
			// Procesamos el envío de promociones
			Route::post('promociones/enviar', 'PromocionesController@enviar');
			// Procesamos la reprogramación del envío con el id seleccionado
			Route::post('envio/{id}/programar', 'EnviosController@programar')
				->where('id', '[0-9]+');
			// Procesamos la cancelación del envío con el id seleccionado
			Route::post('envio/{id}/cancelar', 'EnviosController@cancelar')
				->where('id', '[0-9]+');
			// Procesamos la programación del pedido con el id seleccionado
			Route::post('pedido/{id}/programar', 'PedidosController@programar')
				->where('id', '[0-9]+');
		});
		// Procesamos los cambios en el perfil del usuario logueado
		Route::post('perfil', 'UsuariosController@editarPerfil');
	});

	//JSONS
	// Obtenemos las familias de la agrupación con el id seleccionado en formato JSON
	Route::get('obtener_familias/{id}', 'EncuestasController@obtenerFamilias')
		->where('id', '[0-9]+');
	// Obtenemos las subfamilias de la familia con el id seleccionado en formato JSON
	Route::get('obtener_subfamilias/{id}', 'EncuestasController@obtenerSubfamilias')
		->where('id', '[0-9]+');
	// Obtenemos los resultados de las encuestas en formato JSON
	Route::get('obtener_resultados', 'EncuestasController@obtenerResultados');
	// Obtenemos los resultados de la pregunta con el id seleccionado en formato JSON
	Route::get('obtener_resultados/{id}', 'EncuestasController@obtenerResultadosPregunta')
		->where('id', '[0-9]+');
});