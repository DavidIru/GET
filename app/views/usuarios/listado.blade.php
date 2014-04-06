@extends('layouts.master')

@section('titulo')
	GET - Gestión de Usuarios
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
			<p>Gestionar usuarios</p>
		</h3>
	</div>
@stop

@section('contenido')
	<h2>Listado de usuarios</h2>
	<table id="tabla">
		<thead>
			<tr>
				<th style="display: none">id</th>
				<th>Nombre</th>
				<th>Rol</th>
				<th>Usuario</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($usuarios as $usuario)
			<tr>
				<td>{{ $usuario->id }}</td>
				<td>{{ $usuario->nombre }}</td>
				<td>{{ $usuario->rol->rol }}</td>
				<td>{{ $usuario->usuario }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
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
				$(location).attr('href',"{{ URL::to('usuario/" + $(this).data("id") + "') }}");
			});
		});
	</script>
@stop