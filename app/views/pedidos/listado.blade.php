@extends('layouts.master')

@section('titulo')
	GET - Gestión de Pedidos
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
			<p>Pedidos</p>
		</h3>
	</div>
@stop

@section('contenido')
	@if($todos)
		<h2>Listado de pedidos completo <a href="{{ URL::to('pedidos')}}" title="Mostrar 100 últimos">(Mostrar 100 últimos)</a></h2>
	@else
		<h2>Listado de últimos 100 pedidos <a href="{{ URL::to('_pedidos')}}" title="Mostrar todos">(Mostrar todos)</a></h2>
	@endif
	
	<table id="tabla">
		<thead>
			<tr>
				<th style="display: none">id</th>
				<th>Núm. Doc</th>
				<th>Cliente</th>
				<th>Teléfono</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($pedidos as $pedido)
			<tr>
				<td style="display: none">{{ $pedido->IdDocumento }}</td>
				<td>{{ $pedido->NumeroDocumento }}</td>
				<td>{{ (strlen($pedido->CLNombre)>20) ? substr($pedido->CLNombre, 0, 20)."..." : $pedido->CLNombre }}</td>
				<td>{{ substr(str_replace(' ', '', $pedido->CLTelefono), 0, 9) }}</td>
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
				$(location).attr('href',"{{ URL::to('pedido/" + $(this).data("id") + "') }}");
			});
		});
	</script>
@stop