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
	<table id="pedidos">
		<thead>
			<tr>
				<td data-dynatable-no-sort></td>
				<td>Núm. Doc</td>
				<td>Cliente</td>
				<td>Teléfono</td>
			</tr>
		</thead>
		<tbody>
	@foreach ($pedidos as $pedido)
		<tr>
			<td data-dynatable-amarillo>
			@if($pedido->Situacion == 'Pendiente Recibir Material')
				<span class="icon-alarm amarillo"></span>
			@else
				<span class="icon-truck verde"></span>
			@endif
			</td>
			<td style="font-weight: 900">{{ $pedido->NumeroDocumento }}</td>
			<td>{{ (strlen($pedido->CLNombre)>20) ? substr($pedido->CLNombre, 0, 20)."..." : $pedido->CLNombre }}</td>
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
			
			$('#pedidos').dynatable({
				features: {
					recordCount: false
				}
			});
		});
	</script>
@stop