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
			<a href="{{ URL::to('/')}}" title="Volver al inicio">Inicio</a>
			<span>></span>
			<a href="{{ URL::to('usuarios')}}" title="Volver a Gestionar usuarios">Gestionar usuarios</a>
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
			<h4><strong>Creación:</strong> {{ date("d/m/Y H:i", strtotime($usuario->created_at)) }}h</h4>
			<h4><strong>Último cambio:</strong> {{ ($usuario->updated_at == $usuario->created_at)? "No ha habido cambios" : date("d/m/Y H:i", strtotime($usuario->updated_at))."h" }}</h4>
		</div>
		<div id="usuariocambios0">
			<h2>Cambiar nombre completo</h2>
			<p>Este es el nombre con el que se le verá en la aplicación.</p>
			@if (isset($mensaje) && $mensaje['numero'] == "mensaje0")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
						<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>El nombre ha sido actualizado.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				{{ Form::text('nombre', Input::old('nombre'), array('required' => 'required', 'placeholder' => 'Nuevo nombre completo')); }}
				{{ Form::hidden('mensaje', 'mensaje0') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="usuariocambios1">
			<h2>Cambiar nombre de usuario</h2>
			<p>Este es el nombre que utilizará para acceder a la aplicación web. La longitud mínima es de cinco caracteres.</p>
			@if (isset($mensaje) && $mensaje['numero'] == "mensaje1")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>El nombre del usuario ha sido actualizado.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				{{ Form::text('usuario', Input::old('usuario'), array('required' => 'required', 'placeholder' => 'Nuevo nombre del usuario')); }}
				{{ Form::hidden('mensaje', 'mensaje1') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="usuariocambios2">
			<h2>Cambiar rol del usuario</h2>
			<p>El rol del usuario indica el nivel de acceso que tendrá el mismo dentro de la aplicación. Los niveles están ordenados de mayor a menor.</p>
			@if (isset($mensaje) && $mensaje['numero'] == "mensaje2")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>El rol del usuario ha sido actualizado.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				<select name="rol">
				@foreach ($roles as $rol)
					<option value="{{ $rol->id }}"{{ ($rol->id == $usuario->rol_id)? 'selected="selected"' : '' }}>{{ $rol->rol }}</option>
				@endforeach
				</select>
				{{ Form::hidden('mensaje', 'mensaje2') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="usuariocambios3">
			<h2>Cambiar contraseña del usuario</h2>
			<p>Esta es la contraseña que utilizará para acceder a la aplicación web. La longitud mínima es de cuatro caracteres. Para mejorar el nivel de seguridad de la contraseña mezcle números, letras (mayúsculas y minúsuculas) y símbolos.</p>
			@if (isset($mensaje) && $mensaje['numero'] == "mensaje3")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
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
				{{ Form::password('pass', array('required' => 'required', 'id' => 'pass', 'placeholder' => 'Nueva contraseña')) }}
				{{ Form::password('pass2', array('required' => 'required', 'placeholder' => 'Repetir contraseña')) }}
				{{ Form::hidden('mensaje', 'mensaje3') }}
				<h3>
					<strong>Seguridad de la contraseña:</strong> <span id="nivel">0%</span>
					<div id="barra">
						<div id="relleno"></div>	
					</div>
				</h3>
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="usuarioeliminar">
			<h2><span class="icon-times-circle rojo"></span>Eliminar usuario</h2>
			<p>Para eliminar al usuario pulse el botón. Esta acción es irreversible.</p>
			{{ Form::open(array('url' => '/usuario/'.$usuario->id.'/eliminar')) }}
				{{ Form::hidden('borrar', 'borrar') }}
				{{ Form::submit('Eliminar usuario', array('onclick' => "return window.confirm('¿Está seguro de que desea eliminar el usuario ".$usuario->nombre."?')")) }}
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