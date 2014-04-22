@extends('layouts.master')

@section('titulo')
	GET - Pantalla de inicio
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
			<p>Inicio</p>
		</h3>
	</div>
@stop

@section('contenido')
	<h2>Pedidos pendientes</h2>
	<table id="tabla">
		<thead>
			<tr>
				<th style="display: none">id</th>
				<th style="display: none">clase</th>
				<th>Núm. Doc</th>
				<th>Cliente</th>
				<th>Teléfono</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($pedidos as $pedido)
			<tr>
				</td>
				<td style="display: none">{{ $pedido->IdDocumento }}</td>
				<td style="display: none">{{ ($pedido->Situacion == 'Pendiente Recibir Material')? "situacion-pendiente" : "situacion-null" }}</td>
				<td>{{ $pedido->NumeroDocumento }}</td>
				<td>{{ (strlen($pedido->CLNombre)>24) ? substr($pedido->CLNombre, 0, 24)."..." : $pedido->CLNombre }}</td>
				<td>{{ explode(' ', $pedido->CLTelefono)[0] }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<h2>Resumen encuestas</h2>
	<article id="comentarios-nuevos">
		<table id="tablacomentarios">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th style="display: none">clase</th>
					<th>Comentarios sin leer</th>
				</tr>
			</thead>
			<tbody>
			@foreach($comentarios as $comentario)
				<tr>
					<td style="display: none">{{ $comentario->id }}</td>
					<td style="display: none">leido</td>
					<td>{{ (strlen($comentario->comentario)>50) ? substr($comentario->comentario, 0, 50)."..." : $comentario->comentario }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</article>
	<article id="media-total">
		@if($media < 5)
			<h4 class="rojo-fuerte sombreado">
		@elseif($media < 8)
			<h4 class="amarillo-fuerte sombreado">
		@else
			<h4 class="verde sombreado">
		@endif
			{{ number_format($media, 1) }}</h4>
		<h2>Satisfacción media del servicio total</h2>
	</article>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.dynatable.js') }}
	<script>
		$(document).ready(function() {
			
			$('#tabla').dynatable({
				features: {
					recordCount: false,
				}
			});

			$('#tablacomentarios').dynatable({
				features: {
					recordCount: false,
					search: false,
					perPageSelect: false,
					sort: false
				},
				dataset: {
					perPageDefault: 3
				}
			});

			$('#tabla tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('pedido/" + $(this).data("id") + "') }}");
			});

			$('#tablacomentarios tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('encuestas/comentario/" + $(this).data("id") + "') }}");
			});
		});

	</script>
@stop