@extends('layouts.master')

@section('titulo')
	GET - Gestión de Envíos
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
			<p>Envíos</p>
		</h3>
	</div>
@stop

@section('contenido')
	<h2>Listado de envíos programados</h2>
	
	@if ($exito)
		<div id="mensaje" class="exito">
			<p class="exito-formulario">Se ha marcado como entregado con éxito</p>
		</div>
	@endif
	<table id="tabla">
		<thead>
			<tr>
				<th style="display: none">id</th>
				<th>Entrega</th>
				<th>Núm. Doc</th>
				<th>Cliente</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($envios as $envio)
			<tr>
				<td style="display: none">{{ $envio->IdDocumento }}</td>
				<td>{{ date("d/m", strtotime($envio->FechaEntrega)) }} - {{ date("H:i", strtotime($envio->HoraEntrega)) }}h</td>
				<td>{{ $envio->NumeroDocumento }}</td>
				<td>{{ (strlen($envio->CLNombre)>16) ? substr($envio->CLNombre, 0, 16)."..." : $envio->CLNombre }}</td>
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
				$(location).attr('href',"{{ URL::to('envio/" + $(this).data("id") + "') }}");
			});
		});
	</script>
@stop