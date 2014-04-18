@extends('layouts.master')

@section('titulo')
	GET - Inscribir cliente
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
			<p>Inscribir cliente</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="cliente">
		<div id="clienteadd">
			<h2>Inscripción de nuevo cliente</h2>
			<p>Introduzca los datos para la inscripción de un nuevo cliente en la lista de promociones.</p>
			@if (isset($errors) && !empty($errors->all()))
				<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
					@foreach ($errors->all() as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@endif
			{{ Form::open() }}				
				{{ Form::label('nombre', 'Nombre del cliente') }}
				{{ Form::text('nombre', Input::old('nombre'), array('required' => 'required', 'placeholder' => 'Nombre del cliente')) }}
				{{ Form::label('telefono', 'Teléfono del cliente') }}
				{{ Form::text('telefono', Input::old('telefono'), array('placeholder' => 'Teléfono del cliente')) }}
				{{ Form::label('email', 'E-mail del cliente') }}
				{{ Form::text('email', Input::old('email'), array('placeholder' => 'E-mail del cliente')) }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.placeholder.js') }}
	<script>
		$(document).ready(function($) {
			$('input, textarea').placeholder();
		});
	</script>
@stop