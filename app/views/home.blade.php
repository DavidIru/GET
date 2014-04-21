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
	<article id="media-semanal">
		<h4>9.9</h4>
		<h2>Satisfacción media del servicio semanal</h2>
	</article>
	<article id="media-total">
		<h4>9.6</h4>
		<h2>Satisfacción media del servicio total</h2>
	</article>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.dynatable.js') }}
	<script>
		$(document).ready(function() {
			
			$('#tabla').dynatable({
				features: {
					recordCount: false
				}
			});
		});

		$('#tabla tbody').on('click', "tr", function() {
			$(location).attr('href',"{{ URL::to('pedido/" + $(this).data("id") + "') }}");
		});
	</script>
@stop