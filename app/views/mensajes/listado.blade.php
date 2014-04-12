@extends('layouts.master')

@section('titulo')
	GET - Gestión de Mensajes
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
			<p>Gestionar mensajes</p>
		</h3>
	</div>
@stop

@section('contenido')
	<h2>Listado de mensajes</h2>
	<div>
		<table id="tabla">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th>Asignado a</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($mensajes as $mensaje)
				<tr>
					<td>{{ $mensaje->id }}</td>
					<td>{{ $mensaje->tipo->nombre }}</td>
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
					recordCount: false,
					paginate: false,
					search: true,
					perPageSelect: false
				},
				dataset: {
					perPageDefault: 10
				}
			});

			$('#tabla tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('mensaje/" + $(this).data("id") + "') }}");
			});
		});
	</script>
@stop