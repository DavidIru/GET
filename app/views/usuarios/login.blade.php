<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<title>GET - Login en la aplicación</title>
	{{ HTML::style('css/login.css') }}
</head>
<body>
	<figure>
		{{ HTML::image('img/logo2.png', "eIruzubieta.com", array('id' => 'logo', 'title' => 'eIruzubieta.com')) }}
	</figure>
	<div class="loader">
	    <div class="bar"></div>
	    <div class="bar"></div>
	    <div class="bar"></div>
	</div>
	{{-- Preguntamos si hay algún mensaje de error y si hay lo mostramos  --}}
    @if(Session::has('mensaje_error'))
        <div id="mensaje-error"><h2>{{ Session::get('titulo_error') }}</h2><p>{{ Session::get('mensaje_error') }}</p></div>
    @endif
    @if(Session::has('mensaje_exito'))
        <div id="mensaje-exito"><h2>{{ Session::get('titulo_exito') }}</h2><p>{{ Session::get('mensaje_exito') }}</p></div>
    @endif
	{{ Form::open(array('url' => '/login')) }}
	{{ Form::token() }}
		<div>
			<label for="usuario" class="icono icon-user"></label>
			{{ Form::text('usuario', Input::old('usuario'), array('required' => 'required', 'placeholder' => 'Nombre de usuario')); }}
		</div>
		<div>
			<label for="pass" class="icono icon-key"></label>
			{{ Form::password('pass', array('required' => 'required', 'placeholder' => 'Contraseña')); }}
		</div>
		<div>
			{{ Form::checkbox('recordarme', true, null, array('id' => 'recordarme')) }}
			<label for="recordarme" class="recordar">No cerrar sesión</label>
		</div>
		<div>
			<input type="submit" id="enviar">
		</div>
	{{ Form::close() }}
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js') }}
	<script>
		$(document).ready(function() {
			$('.loader').fadeOut(400);
			$('figure').animate({"margin-top": "2em"}, 1500);
			$('form').fadeIn(1500);
		});
	</script>
</body>
</html>