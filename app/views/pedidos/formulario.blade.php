@extends('layouts.master')

@section('titulo')
	GET - Pedido {{ $pedido->NumeroDocumento }}
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
			<a href="{{ URL::to('pedidos')}}" title="Volver a Pedidos">Pedidos</a>
			<span>></span>
			<p>Pedido {{ $pedido->NumeroDocumento }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="pedido">
		<div id="pedidodatos">
			@if ($exito)
				<div id="mensaje" class="exito">
					<p class="exito-formulario">Se ha cancelado el envío con éxito</p>
				</div>
			@endif
			<h2>
				Datos del pedido
				<span>{{ date("d/m/Y", strtotime($pedido->FechaDocumento)) }}</span>
			</h2>
			<h3>{{ $pedido->NumeroDocumento }}</h3>
			<p><strong>Situación:</strong> {{ (strlen($pedido->Situacion) == 0)? "Preparado para envío" : $pedido->Situacion }}</p>
			<h4{{ (strlen($pedido->Situacion) != 0)? ' style="width: auto;"' : '' }}>
				<span>Cliente:</span>{{ $pedido->CLNombre }}
			</h4>
			@if(strlen($pedido->Situacion) == 0)
				<div id="cuando">
					<h4>Entrega programada</h4>
					@if($pedido->Situacion == "Entregado")
						<p class="unico">El pedido ya ha sido entregado</p>
					@else
						@if($pedido->FechaEntrega == NULL)
							<p>No hay fecha de entrega</p>
						@else
							<p>Fecha: {{ date("d/m/Y", strtotime($pedido->FechaEntrega)) }}</p>
						@endif
						@if($pedido->HoraEntrega == NULL)
							<p>No hay hora de entrega</p>
						@else
							<p>Hora: {{ date("H:i", strtotime($pedido->HoraEntrega)) }}h</p>
						@endif
						<a href="{{ URL::to('pedido/'.$pedido->IdDocumento.'/programar')}}">{{ ($pedido->FechaEntrega == NULL && $pedido->HoraEntrega == NULL)? "Programar envío" : "Editar envío" }}</a>
					@endif
				</div>
			@endif
		</div>
		<div id="pedidoenvio">
			<h2>Datos del envío</h2>
			<h3><strong>Cliente:</strong> {{ $pedido->CLNombreEnvio }}</h3>
			
			<h3><strong>Dirección:</strong> {{ $pedido->CLDireccionEnvio }}</h3>
			<h4><strong>Ciudad:</strong> {{ $pedido->CLCiudadEnvio }}</h4>
			<h4><strong>Provincia:</strong> {{ (strlen($pedido->CLProviniciaEnvio) == 0)? '---': $pedido->CLProviniciaEnvio }}</h4>
			<h4><strong>C.P:</strong> {{ (strlen($pedido->CLCodPostalEnvio) == 0)? '---': $pedido->CLCodPostalEnvio }}</h4>
			<h3><strong>Teléfono:</strong> {{ $pedido->CLTelefonoEnvio }}</h3>
			<div id="cuadro">
				<div id="mapa"></div>
			</div>
		</div>
		<div id="pedidodetalle">
			<h2>Detalles del pedido</h2>
			<h3><strong>Importe a cuenta:</strong> {{ $pedido->ImporteAcuenta }} €</h3>
			<h3><strong>Forma de pago:</strong> {{ $pedido->DescripcionFormaPagoDocumento }}</h3>
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
			var url = "{{ 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode(stripslashes($pedido->CLDireccionEnvio." ".$pedido->CLCiudadEnvio." ".$pedido->CLProvinciaEnvio)).'&sensor=false' }}";
			$.getJSON(url, function(data) {
				console.log(data);
			});

			$('#mapa').gmap({'disableDefaultUI':true, 'scrollwheel': false}).bind('init', function(evt, map) { 
				$.getJSON(url, function(data) {
					if(data.status == 'OK') {
						$.each( data.results, function(i, m) {
							$('#mapa').gmap('addMarker', { 'position':   m.geometry.location.lat+ ',' + m.geometry.location.lng, 'bounds': true }).click(function() {
								$('#mapa').gmap('openInfoWindow', { 'content': "{{ $pedido->CLDireccionEnvio }}" }, this);
							});
						});

						$('#mapa').gmap('option', 'zoom', 15);
					}
					else {
						var url2 = "{{ 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode(stripslashes($pedido->CLCiudadEnvio." ".$pedido->CLProvinciaEnvio)).'&sensor=false' }}";

						$.getJSON(url2, function(data) {
							if(data.status == 'OK') {
								$.each( data.results, function(i, m) {
									$('#mapa').gmap('addMarker', { 'position':   m.geometry.location.lat+ ',' + m.geometry.location.lng, 'bounds': true }).click(function() {
										$('#mapa').gmap('openInfoWindow', { 'content': "{{ $pedido->CLCiudadEnvio }}" }, this);
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