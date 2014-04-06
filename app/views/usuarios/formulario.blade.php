@extends('layouts.master')

@section('titulo')
	GET - Usuario {{ $usuario->nombre }} ({{ $usuario->usuario }})
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
			<a href="{{ URL::to('/')}}">Inicio</a>
			<span>></span>
			<a href="{{ URL::to('usuarios')}}">Gestionar usuarios</a>
			<span>></span>
			<p>Usuario {{ $usuario->nombre }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="usuario">
		<div id="usuariodatos">
			<h2>Datos del usuario</h2>
			<h3><strong>Nombre completo:</strong> {{ $usuario->nombre }}</h3>
			<h3><strong>Nombre de usuario:</strong> {{ $usuario->usuario }}</h3>
			<h3><strong>Rol en la aplicación:</strong> {{ $usuario->rol->rol }}</h3>
			<h4><strong>Creación:</strong> {{ date("d/m/Y h:i", strtotime($usuario->created_at)) }}h</h4>
			<h4><strong>Último cambio:</strong> {{ ($usuario->updated_at == $usuario->created_at)? "No ha habido cambios" : date("d/m/Y h:i", strtotime($usuario->updated_at))."h" }}</h4>
		</div>
		<div id="usuariocambios1">
			<h2>Cambiar nombre del usuario</h2>
			{{ Form::open(array('url' => '/')) }}
				{{ Form::token() }}
				{{ Form::label('usuario', 'Nuevo nombre de usuario') }}
				{{ Form::text('usuario', Input::old('usuario'), array('required' => 'required', 'placeholder' => 'Nuevo nombre de usuario')); }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="usuariocambios2">
			<h2>Cambiar rol del usuario</h2>
			{{ Form::open(array('url' => '/')) }}
				{{ Form::token() }}
				{{ Form::label('rol', 'Nuevo rol de usuario') }}
				SELECT
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="usuariocambios3">
			<h2>Cambiar contraseña del usuario</h2>
			{{ Form::open(array('url' => '/')) }}
				{{ Form::token() }}
				{{ Form::label('pass', 'Nueva contraseña') }}
				{{ Form::password('pass', array('required' => 'required', 'placeholder' => 'Nueva contraseña')) }}
				{{ Form::label('pass2', 'Repetir contraseña') }}
				{{ Form::password('pass2', array('required' => 'required', 'placeholder' => 'Repetir contraseña')) }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	<script>
		$(document).ready(function() {
			
		});
	</script>
@stop