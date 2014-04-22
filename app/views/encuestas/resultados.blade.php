@extends('layouts.master')

@section('titulo')
	GET - Gestión de Encuestas
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
			<p>Encuestas - Resultados</p>
		</h3>
	</div>
@stop

@section('contenido')
	<h2>Nota media mensual</h2>
	<div id="chart"></div>
	<h2>Comentarios de los clientes</h2>
	<div>
		<table id="tabla">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th style="display: none">clase</th>
					<th>Comentario</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($comentarios as $comentario)
				<tr>
					<td style="display: none">{{ $comentario->id }}</td>
					<td style="display: none">{{ ($comentario->leido == 0)? "no-leido" : "leido" }}</td>
					<td>{{ (strlen($comentario->comentario)>50) ? substr($comentario->comentario, 0, 50)."..." : $comentario->comentario }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	<h2>Preguntas con menor valoración media</h2>
	<div>
		<table id="tablamedias">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th style="display: none">clase</th>
					<th>Pregunta</th>
					<th>Nota Media</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($preguntas as $pregunta)
				<tr>
					<td style="display: none">{{ $pregunta->id }}</td>
					<td style="display: none">leido</td>
					<td>{{ (strlen($pregunta->texto)>30) ? substr($pregunta->texto, 0, 30)."..." : $pregunta->texto }}</td>
					<td>{{ number_format($pregunta->media, 2) }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
@stop

@section('scripts')
	{{ HTML::script('js/charts/highstock.js') }}
	{{ HTML::script('js/jquery.dynatable.js') }}
	<script>
		$(document).ready(function() {
			$('#tabla').dynatable({
				features: {
					recordCount: false
				},
				dataset: {
					perPageDefault: 10
				}
			});

			$('#tablamedias').dynatable({
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

			$('#tabla tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('encuestas/comentario/" + $(this).data("id") + "') }}");
			});

			$('#tablamedias tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('encuestas/pregunta/" + $(this).data("id") + "') }}");
			});

			//Generamos el gráfico
			$.getJSON('/obtener_resultados', function(data) {
				Highcharts.setOptions({
					lang: {
						months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
						weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
						shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
						loading: "Cargando"
					},
					global: {
						timezoneOffset: -2 * 60
					}
				});

				$('#chart').highcharts('StockChart', {
					chart: {
						alignTicks: false
					},
					colors: [
						'#2b4b8c'
					],
					series: [{
						type: 'column',
						name: 'Nota media',
						data: data,
						dataGrouping: {
							approximation: "average",
							enabled: true,
							forced: true,
							units: [
								['month',[1]]
							]
						},
						tooltip: {
							valueDecimals: 2
						}
					}],
					credits: {
						/*
						text: 'webKreativos.com',
						href: 'http://www.webkreativos.com'
						*/
						enabled: false
					},
					rangeSelector: {
						enabled: true,
						selected: 0,
						inputEnabled: false,
						buttonSpacing: 10,
						buttons: [{
							type: 'month',
							count: 3,
							text: '3m'
						}, {
							type: 'month',
							count: 6,
							text: '6m'
						}, {
							type: 'year',
							count: 1,
							text: '1y'
						}, {
							type: 'all',
							text: 'Todo'
						}]
					},
					yAxis : {
						plotBands : [{
							from : 0,
							to : 4.5,
							color : 'rgba(255,161,161,.8)'
						}, {
							from : 4.5,
							to : 8,
							color : 'rgba(255,243,161,.8)'
						}, {
							from : 8,
							to : 10,
							color : 'rgba(170,243,117,.6)'
						}]
					}
				});
			});
		});
	</script>
@stop