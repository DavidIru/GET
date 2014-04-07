@extends('layouts.master')

@section('titulo')
	GET - Añadir usuario
@stop

@section('estilos')
	{{ HTML::style('css/principal.css') }}
	<!--[if lt IE 8]><!-->
	{{ HTML::style('css/ie7.css') }}
	<!--<![endif]-->
@stop

@section('breadcrumb')
	<div id="breadcrumbs">
		<h3>
			<a href="{{ URL::to('/')}}" title="Volver al inicio">Inicio</a>
			<span>></span>
			<a href="{{ URL::to('usuarios')}}" title="Volver a Gestionar usuarios">Gestionar usuarios</a>
			<span>></span>
			<p>Añadir usuario</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="usuario">
		<div id="usuarioadd">
			<h2>Creación de nuevo usuario</h2>
			<p>Introduzca los datos para la creación del usuario. La longitud mínima del nombre de usuario es de 5 caracteres. La contraseña debe tener como mínimo 4 caracteres. Para entrar en la aplicación se usarán el nombre de usuario y la contraseña. Todos los campos son obligatorios.</p>
			@if (isset($errors) && !empty($errors->all()))
				<div id="mensaje" class="error">
				@foreach ($errors->all() as $error)
					<p>{{ $error }}</p>
				@endforeach
				</div>
			@endif
			{{ Form::open() }}
				<div>
					{{ Form::label('nombre', 'Nombre completo') }}
					{{ Form::text('nombre', Input::old('nombre'), array('required' => 'required', 'placeholder' => 'Nombre de usuario')) }}
					{{ Form::label('usuario', 'Nombre de usuario') }}
					{{ Form::text('usuario', Input::old('usuario'), array('required' => 'required', 'placeholder' => 'Nombre de usuario')) }}
					{{ Form::label('rol', 'Rol en la aplicación') }}
					<select name="rol">
					@foreach ($roles as $rol)
						<option value="{{ $rol->id }}"{{ ($rol->id == Input::old('rol'))? 'selected="selected"' : '' }}>{{ $rol->rol }}</option>
					@endforeach
					</select>
				</div>
				{{ Form::label('pass', 'Contraseña') }}
				{{ Form::password('pass', array('required' => 'required', 'id' => 'pass', 'placeholder' => 'Nueva contraseña')) }}
				{{ Form::password('pass2', array('required' => 'required', 'placeholder' => 'Repetir contraseña')) }}
				<h3>
					<strong>Seguridad de la contraseña:</strong> <span id="nivel">0%</span>
					<div id="barra">
						<div id="relleno"></div>	
					</div>
				</h3>
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('js/seguridad.password.js') }}
	<script>
		$(document).ready(function() {
			$('#pass').on('keyup', function() {
				var nivel = seguridad_clave($(this).val());
				var relleno = $('#relleno');
				var texto = $('#nivel');
				if(nivel <= 25) {
					// Rojo
					relleno.attr('class', 'rojo');
				}
				else if(nivel <= 50) {
					// Naranja
					relleno.attr('class', 'naranja');
				}
				else if (nivel <= 75) {
					// Amarillo
					relleno.attr('class', 'amarillo');
				}
				else {
					// Verde
					relleno.attr('class', 'verde');
				}
				texto.html(nivel + "%");
				relleno.css('width', nivel + '%');
			});
	
		});
	</script>
@stop