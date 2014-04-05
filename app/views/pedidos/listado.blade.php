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
	
	<table id="pedidos">
		<thead>
			<tr>
				<td>Núm. Doc</td>
				<td>Cliente</td>
				<td>Teléfono</td>
				<td data-dynatable-no-sort></td>
			</tr>
		</thead>
		<tbody>
	@foreach ($pedidos as $pedido)
		<tr>
			<td>{{ $pedido->NumeroDocumento }}</td>
			<td>{{ (strlen($pedido->CLNombre)>20) ? substr($pedido->CLNombre, 0, 20)."..." : $pedido->CLNombre }}</td>
			<td>{{ substr($pedido->CLTelefono, 0, 9) }}</td>
			<td>
				<a href="#">E</a>
			</td>
		</tr>
	@endforeach
		</tbody>
	</table>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.dynatable.js') }}
	<script>
		$(document).ready(function() {
			
			$('#pedidos').dynatable({
				features: {
					recordCount: false
				},
				dataset: {
					perPageDefault: 20
				}
			});
		});
	</script>
@stop