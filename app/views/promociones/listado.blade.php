@extends('layouts.master')

@section('titulo')
	GET - Gestión de Promociones
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
			<p>Gestionar promociones</p>
		</h3>
	</div>
@stop

@section('acciones')
	<div id="acciones">
		<a href="{{ URL::to('promociones/cliente/add') }}" title="Inscribir cliente">
		<span class="icon-plus-circle verde-oscuro"></span>Inscribir cliente
		</a>
		<p>|</p>
		<a href="{{ URL::to('promociones/enviar') }}" title="Enviar promoción">
			<span class="icon-paperplane blanco"></span>Enviar promoción
		</a>
	</div>
@stop

@section('contenido')
	<h2>Listado de clientes inscritos</h2>
	@if(isset($exito))
		<div id="mensaje" class="exito"><p>{{ $exito }}</p></div>
	@endif
	<div>
		<table id="tabla">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th>Nombre</th>
					<th>Teléfono</th>
					<th>E-mail</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($clientes as $cliente)
				<tr>
					<td style="display: none">{{ $cliente->id }}</td>
					<td>{{ $cliente->nombre }}</td>
					<td>{{ $cliente->telefono }}</td>
					<td>{{ $cliente->email }}</td>
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
			var ver_filtro = false;
			$('#tabla').dynatable({
				features: {
					recordCount: false
				},
				dataset: {
					perPageDefault: 20
				}
			});

			$('#tabla tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('promociones/cliente/" + $(this).data("id") + "') }}");
			});
		});
	</script>
@stop