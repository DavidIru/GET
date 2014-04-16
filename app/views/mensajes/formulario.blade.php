@extends('layouts.master')

@section('titulo')
	GET - Mensaje {{ $mensaje->tipo->nombre }}
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
			<a href="{{ URL::to('mensajes')}}" title="Volver a Gestionar mensajes">Gestionar mensajes</a>
			<span>></span>
			<p>Mensaje {{ $mensaje->tipo->nombre }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="mensajeauto">
		<div id="mensajeautodatos">
			<h2>Datos del mensaje</h2>
			<h3><strong>Asignado a:</strong> {{ $mensaje->tipo->nombre }}</h3>
			<h3><strong>Texto:</strong> {{ $mensaje->texto }}</h3>
			<h4><strong>Creación:</strong> {{ date("d/m/Y H:i", strtotime($mensaje->created_at)) }}h</h4>
			<h4><strong>Último cambio:</strong> {{ ($mensaje->updated_at == $mensaje->created_at)? "No ha habido cambios" : date("d/m/Y H:i", strtotime($mensaje->updated_at))."h" }}</h4>
		</div>
		<div id="mensajeautocambios0">
			<h2>Cambiar texto</h2>
			<p>Este es el mensaje que se enviará a la asignación ({{ $mensaje->tipo->nombre }}). Para editarlo se pueden usar las siguientes claves que se reemplazarán por los datos indicados al enviar el mensaje. El formato es <strong>#clave#</strong>.</p>
			@if (isset($errores))
				<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
					@foreach ($errores as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@elseif (isset($bien))
				<div id="mensaje" class="exito">
					<p>El texto ha sido actualizado.</p>
				</div>
			@endif
			<div id="botones">
				<a href="#" data-texto="#nombre#">Nombre del cliente</a>
				<a href="#" data-texto="#numero#">Número del pedido</a>
				<a href="#" data-texto="#fecha#">Fecha del pedido</a>
				<a href="#" data-texto="#hora#">Hora del pedido</a>
				<a href="#" data-texto="#url#">URL del pedido</a>
				<a href="#" data-texto="#encuesta#">URL de la encuesta</a>
			</div>
			{{ Form::open() }}
				{{ Form::textarea('texto', $mensaje->texto, array('required' => 'required', 'placeholder' => 'Nuevo texto de mensaje', 'id' => 'texto')); }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.maxlength.min.js') }}
	<script>
		$(document).ready(function() {
			$('#botones a').on('click', function(e) {
				e.preventDefault();
				var texto = $('#texto');
				texto.val(texto.val() + $(this).data("texto"));
			});

			$('#texto').maxlength({   
				events: [], 
				maxCharacters: 140,
				status: true,  
				statusClass: "restantes",
				statusText: "caracteres restantes", 
				notificationClass: "notificacion",
				showAlert: false, 
				alertText: "Ha escrito demasiados caracteres.",
				slider: false  
			});
		});
	</script>
@stop