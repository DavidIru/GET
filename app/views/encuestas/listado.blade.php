@extends('layouts.master')

@section('titulo')
	GET - Gestión de Encuestas
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
			<a href="{{ URL::to('/') }}" title="Volver a inicio">Inicio</a>
			<span>></span>
			<p>Gestionar encuestas</p>
		</h3>
	</div>
@stop

@section('contenido')
	<h2>Listado de preguntas</h2>
	<a href="{{ URL::to('encuestas/pregunta/add') }}" class="boton-listado">
		<span class="icon-plus-circle verde-oscuro"></span>Añadir pregunta
	</a>
	<a href="#" class="boton-listado">
		<span class="icon-search naranja"></span>Filtrar preguntas
	</a>
	@if(isset($exito))
		<div id="mensaje" class="exito"><p>{{ $exito }}</p></div>
	@endif
	<div>
		<table id="tabla">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th>Pregunta</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($preguntas as $pregunta)
				<tr>
					<td>{{ $pregunta->id }}</td>
					<td>{{ $pregunta->texto }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.dynatable.js') }}
	<script>
		$(document).ready(function() {
			
			$('#tabla').dynatable({
				features: {
					recordCount: false
				},
				dataset: {
					perPageDefault: 20
				}
			});

			$('#tabla tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('encuestas/pregunta/" + $(this).data("id") + "') }}");
			});
		});
	</script>
@stop