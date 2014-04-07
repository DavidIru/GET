@extends('layouts.master')

@section('titulo')
	GET - Perfil de {{ Auth::user()->nombre }} ({{ Auth::user()->usuario }})
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
			<p>Perfil de {{ Auth::user()->nombre }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="usuario">
		<div id="usuariodatos">
			<h2>Datos del usuario</h2>
			<h3><strong>Nombre completo:</strong> {{ Auth::user()->nombre }}</h3>
			<h3><strong>Nombre de usuario:</strong> {{ Auth::user()->usuario }}</h3>
			<h3><strong>Rol en la aplicación:</strong> {{ Auth::user()->rol->rol }}</h3>
			<h4><strong>Creación:</strong> {{ date("d/m/Y H:i", strtotime(Auth::user()->created_at)) }}h</h4>
			<h4><strong>Último cambio:</strong> {{ (Auth::user()->updated_at == Auth::user()->created_at)? "No ha habido cambios" : date("d/m/Y H:i", strtotime(Auth::user()->updated_at))."h" }}</h4>
		</div>
		<div id="usuariocambios3">
			<h2>Cambiar contraseña del usuario</h2>
			<p>Esta es la contraseña que utilizará para acceder a la aplicación web. La longitud mínima es de cuatro caracteres. Para mejorar el nivel de seguridad de la contraseña mezcle números, letras (mayúsculas y minúsuculas) y símbolos.</p>
			@if (isset($mensaje))
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
					@foreach ($errores as $error)
						<p>{{ $error }}</p>
					@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>La contraseña del usuario ha sido actualizada.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				{{ Form::label('pass_ant', 'Contraseña antigua') }}
				{{ Form::password('pass_ant', array('required' => 'required', 'placeholder' => 'Contraseña antigua', 'id' => 'pass_ant')) }}
				{{ Form::label('pass', 'Contraseña nueva') }}
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