@extends('layouts.master')

@section('titulo')
	GET - Envío {{ $envio->NumeroDocumento }}
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
			<a href="{{ URL::to('/')}}" title="Volver al inicio">Inicio</a>
			<span>></span>
			<a href="{{ URL::to('envios')}}" title="Volver a Envíos">Envíos</a>
			<span>></span>
			<p>Envío {{ $envio->NumeroDocumento }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="envio">
		<div id="enviodatos">
			@if ($exito)
				<div id="mensaje" class="exito">
					<p class="exito-formulario">Se ha programado el envío con éxito</p>
				</div>
			@endif
			<h2>
				Datos del pedido
				<span>{{ date("d/m/Y", strtotime($envio->FechaDocumento)) }}</span>
			</h2>
			<h3>{{ $envio->NumeroDocumento }}</h3>
			<p><strong>Situación:</strong> {{ (strlen($envio->Situacion) == 0)? "Preparado para envío" : $envio->Situacion }}</p>
			<h4>
				<span>Cliente:</span>{{ $envio->CLNombre }}
			</h4>
			@if(strlen($envio->Situacion) == 0)
				<div id="cuando">
					<h4>Entrega programada</h4>
					<p>Fecha: {{ date("d/m/Y", strtotime($envio->FechaEntrega)) }}</p>
					<p>Hora: {{ date("H:i", strtotime($envio->HoraEntrega)) }}h</p>
					<a href="{{ URL::to('envio/'.$envio->IdDocumento.'/programar')}}">Editar envío</a>
				</div>
			@endif
			<a href="{{ URL::to('envio/'.$envio->IdDocumento.'/entregado')}}" id="entregar" onclick="return window.confirm('¿Está seguro de que desea marcar el envío {{ $envio->NumeroDocumento}} como entregado? Se avisará al cliente mediante SMS y se generará la encuesta.')">Marcar como entregado</a>
		</div>
		<div id="datosenvio">
			<h2>Datos del envío</h2>
			<h3><strong>Cliente:</strong> {{ $envio->CLNombreEnvio }}</h3>
			
			<h3><strong>Dirección:</strong> {{ $envio->CLDireccionEnvio }}</h3>
			<h4><strong>Ciudad:</strong> {{ $envio->CLCiudadEnvio }}</h4>
			<h4><strong>Provincia:</strong> {{ (strlen($envio->CLProviniciaEnvio) == 0)? '---': $envio->CLProviniciaEnvio }}</h4>
			<h4><strong>C.P:</strong> {{ (strlen($envio->CLCodPostalEnvio) == 0)? '---': $envio->CLCodPostalEnvio }}</h4>
			<h3><strong>Teléfono:</strong> {{ $envio->CLTelefonoEnvio }}</h3>
			<h3><strong>Teléfono de aviso:</strong> {{ (is_null($envio->telefonoAviso))? substr($envio->CLTelefonoEnvio, 0, 9) : $envio->telefonoAviso }}</h3>
			<div id="cuadro">
				<div id="mapa"></div>
			</div>
		</div>
		<div id="enviodetalle">
			<h2>Detalles del envío</h2>
			<h3><strong>Importe a cuenta:</strong> {{ $envio->ImporteAcuenta }} €</h3>
			<h3><strong>Forma de pago:</strong> {{ $envio->DescripcionFormaPagoDocumento }}</h3>
			<table id="tabladetalle">
				<thead>
					<tr>
						<th>Producto</th>
						<th>Cantidad</th>
						<th>Precio</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($productos as $producto)
					<tr>
						<td>{{ $producto->ArticuloDescripcion}}</td>
						<td>{{ $producto->Cantidad }}</td>
						<td>{{ $producto->Precio }}€</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('//maps.google.com/maps/api/js?sensor=true') }}
	{{ HTML::script('js/jquery.ui.map.full.min.js') }}
	{{ HTML::script('js/jquery.dynatable.js') }}
	{{ HTML::script('js/jquery.placeholder.js') }}
	<script>
		$(document).ready(function($) {
			$('input, textarea').placeholder();
			var url = "{{ 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode(stripslashes($envio->CLDireccionEnvio." ".$envio->CLCiudadEnvio." ".$envio->CLProvinciaEnvio)).'&sensor=false' }}";
			$.getJSON(url, function(data) {
				console.log(data);
			});

			$('#mapa').gmap({'disableDefaultUI':true, 'scrollwheel': false}).bind('init', function(evt, map) { 
				$.getJSON(url, function(data) {
					if(data.status == 'OK') {
						$.each( data.results, function(i, m) {
							$('#mapa').gmap('addMarker', { 'position':   m.geometry.location.lat+ ',' + m.geometry.location.lng, 'bounds': true }).click(function() {
								$('#mapa').gmap('openInfoWindow', { 'content': "{{ $envio->CLDireccionEnvio }}" }, this);
							});
						});

						$('#mapa').gmap('option', 'zoom', 15);
					}
					else {
						var url2 = "{{ 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode(stripslashes($envio->CLCiudadEnvio." ".$envio->CLProvinciaEnvio)).'&sensor=false' }}";

						$.getJSON(url2, function(data) {
							if(data.status == 'OK') {
								$.each( data.results, function(i, m) {
									$('#mapa').gmap('addMarker', { 'position':   m.geometry.location.lat+ ',' + m.geometry.location.lng, 'bounds': true }).click(function() {
										$('#mapa').gmap('openInfoWindow', { 'content': "{{ $envio->CLCiudadEnvio }}" }, this);
									});
								});
							}

							$('#mapa').gmap('option', 'zoom', 15);
						});

						console.log($('#mapa').gmap('option', 'zoom'));
					}
				});
			});

			$('#tabladetalle').dynatable({
				features: {
					recordCount: false,
					search: false,
					perPageSelect: false,
					paginate: false
				},
				dataset: {
					perPageDefault: 10
				}
			});
		});
	</script>
@stop