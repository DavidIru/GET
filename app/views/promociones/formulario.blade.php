@extends('layouts.master')

@section('titulo')
	GET - Cliente {{ $cliente->nombre }}
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
			<a href="{{ URL::to('promociones')}}" title="Volver a Gestionar promociones">Gestionar promociones</a>
			<span>></span>
			<p>Cliente {{ $cliente->nombre }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="cliente">
		<div id="clientedatos">
			<h2>Datos del cliente</h2>
			<h3><strong>Nombre:</strong> {{ $cliente->nombre }}</h3>
			<h3><strong>Teléfono:</strong> {{ ($cliente->telefono == null)? "No proporcionado" : $cliente->telefono }}</h3>
			<h3><strong>E-mail:</strong> {{ ($cliente->email == null)? "No proporcionado" : $cliente->email }}</h3>
			<h4><strong>Creación:</strong> {{ date("d/m/Y H:i", strtotime($cliente->created_at)) }}h</h4>
			<h4><strong>Último cambio:</strong> {{ ($cliente->updated_at == $cliente->created_at)? "No ha habido cambios" : date("d/m/Y H:i", strtotime($cliente->updated_at))."h" }}</h4>
		</div>
		<div id="clientecambios0">
			<h2>Cambiar nombre</h2>
			<p>Este es el nombre con el que se le personalizarán las promociones.</p>
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
				{{ Form::text('nombre', Input::old('nombre'), array('required' => 'required', 'placeholder' => 'Nuevo nombre')); }}
				{{ Form::hidden('mensaje', 'mensaje0') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="clientecambios1">
			<h2>Cambiar teléfono y e-mail</h2>
			<p>Estos son los medios (teléfono y correo electrónico) a los que llegarán las promociones. Al menos uno de ellos es obligatorio.</p>
			@if (isset($mensaje) && $mensaje['numero'] == "mensaje1")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>El teléfono y el e-mail han sido actualizados.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				{{ Form::label('telefono', 'Teléfono') }}
				{{ Form::text('telefono', $cliente->telefono, array('placeholder' => 'Nuevo teléfono')); }}
				{{ Form::label('email', 'E-mail') }}
				{{ Form::text('email', $cliente->email, array('placeholder' => 'Nuevo e-mail')); }}
				{{ Form::hidden('mensaje', 'mensaje1') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="clienteeliminar">
			<h2><span class="icon-times-circle rojo"></span>Eliminar cliente</h2>
			<p>Para eliminar al cliente pulse el botón. Esta acción es irreversible y dejará de recibir las promociones.</p>
			{{ Form::open(array('url' => '/promociones/cliente/'.$cliente->id.'/eliminar')) }}
				{{ Form::hidden('borrar', 'borrar') }}
				{{ Form::submit('Eliminar cliente', array('onclick' => "return window.confirm('¿Está seguro de que desea eliminar el cliente ".$cliente->nombre."?')")) }}
			{{ Form::close() }}
		</div>
	</section>
@stop