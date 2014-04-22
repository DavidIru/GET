@extends('layouts.master')

@section('titulo')
	GET - Pregunta {{ $pregunta->id}}
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
			<a href="{{ URL::to('encuestas/preguntas')}}" title="Volver a Encuestas - Preguntas">Encuestas - Preguntas</a>
			<span>></span>
			<p>Pregunta {{ $pregunta->id }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="pregunta">
		<div id="preguntadatos">
			<h2>Datos de la pregunta</h2>
			<h3><strong>Pregunta:</strong> {{ $pregunta->texto }}</h3>
			<h3><strong>Se hace en productos pertenecientes a:</strong>
			@if (!is_null($agrupacionACT))
				{{ $agrupacionACT }}
				@if (!is_null($familiaACT))
					<span>></span>{{ $familiaACT }}
					@if (!is_null($subfamiliaACT))
						<span>></span>{{ $subfamiliaACT }}
					@endif
				@endif
			@else
				Todos los productos
			@endif
			</h3>
			<h3><strong>Nota media de la pregunta:</strong> {{ number_format($pregunta->media,2) }} / 10</h3>
			<h4><strong>Creación:</strong> {{ date("d/m/Y H:i", strtotime($pregunta->created_at)) }}h</h4>
			<h4><strong>Último cambio:</strong> {{ ($pregunta->updated_at == $pregunta->created_at)? "No ha habido cambios" : date("d/m/Y H:i", strtotime($pregunta->updated_at))."h" }}</h4>
		</div>
		<div id="preguntagrafico">
			<h2>Nota media mensual de la pregunta</h2>
			<div id="chart"></div>
		</div>
		{{--
		<div id="preguntacambios0">
			<h2>Cambiar pregunta</h2>
			<p>Esta es la pregunta que se realizará al cliente una vez recibido su pedido.</p>
			@if (isset($mensaje) && $mensaje['numero'] == "mensaje0")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
						<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>La pregunta ha sido actualizado.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				{{ Form::text('texto', Input::old('texto'), array('required' => 'required', 'placeholder' => 'Nueva pregunta')); }}
				{{ Form::hidden('mensaje', 'mensaje0') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		--}}
		<div id="preguntacambios1">
			<h2>Cambiar pertenencia</h2>
			<p>Esto indicará con que productos se realizará la pregunta.</p>
			@if (isset($mensaje) && $mensaje['numero'] == "mensaje1")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>La pertenencia ha sido actualizada.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				<label for="agrupacion">Familia agrupación</label>
				<select id="agrupacion" name="agrupacion">
					<option value="0"{{ (is_null($pregunta->agrupacion_id))? 'selected="selected"' : '' }}>Todas</option>
				@foreach ($agrupaciones as $agrupacion)
					<option value="{{ $agrupacion->IdAgrupacion }}"{{ ($agrupacion->IdAgrupacion == $pregunta->agrupacion_id)? 'selected="selected"' : '' }}>{{ $agrupacion->AgrupacionFamilia }}</option>
				@endforeach
				</select>
				<div id="familias"{{ (is_null($pregunta->agrupacion_id))? ' class="oculto"' : '' }}>
					<label for="familia">Familia</label>
					<select id="familia" name="familia">
						<option value="0"{{ (is_null($pregunta->familia_id))? 'selected="selected"' : '' }}>Todas</option>
						@if (!is_null($pregunta->agrupacion_id))
							@foreach ($familias as $familia)
								<option value="{{ $familia->IdFamilia }}"{{ ($familia->IdFamilia == $pregunta->familia_id)? 'selected="selected"' : '' }}>{{ $familia->Familia }}</option>
							@endforeach
						@endif
					</select>
				</div>
				<div id="subfamilias"{{ (is_null($pregunta->familia_id))? ' class="oculto"' : '' }}>
					<label for="subfamilia">Subfamilia</label>
					<select id="subfamilia" name="subfamilia">
						<option value="0"{{ (is_null($pregunta->subfamilia_id))? 'selected="selected"' : '' }}>Todas</option>
						@if (!is_null($pregunta->familia_id))
							@foreach ($subfamilias as $subfamilia)
								<option value="{{ $subfamilia->IdSubfamilia }}"{{ ($subfamilia->IdSubfamilia == $pregunta->subfamilia_id)? 'selected="selected"' : '' }}>{{ $subfamilia->Subfamilia }}</option>
							@endforeach
						@endif
					</select>
				</div>
				{{ Form::hidden('mensaje', 'mensaje1') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="preguntaeliminar">
			<h2><span class="icon-times-circle rojo"></span>Eliminar pregunta</h2>
			<p>Para eliminar la pregunta pulse el botón. Esta acción es irreversible. Pero se guardarán las estadísticas de esta pregunta.</p>
			{{ Form::open(array('url' => '/encuestas/pregunta/'.$pregunta->id.'/eliminar')) }}
				{{ Form::hidden('borrar', 'borrar') }}
				{{ Form::submit('Eliminar pregunta', array('onclick' => "return window.confirm('¿Está seguro de que desea eliminar la pregunta?')")) }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.placeholder.js') }}
	{{ HTML::script('js/charts/highstock.js') }}
	<script>
		$(document).ready(function($) {
			$('input, textarea').placeholder();
			var familias = $('#familias');
			var subfamilias = $('#subfamilias');
			$('#agrupacion').change(function(e) {
				e.preventDefault();
				var agrupacion = $(this).val();
				console.log(agrupacion);
				if(agrupacion != 0) {
					$.getJSON('/obtener_familias/' + agrupacion, function(response) {
						var familia = $('#familia');
						familia.empty();
						var option = $('<option/>', {'value': 0, 'text': 'Todas'});
						familia.append(option);
						$.each(response, function(k, v) {
							var option = $('<option/>', {'value': v.IdFamilia, 'text': v.Familia});
							familia.append(option);
						});
					});

					familias.attr('class', '');
				}
				else {
					var familia = $('#familia');
					familia.empty();
					var option = $('<option/>', {'value': 0, 'text': 'Todas'});
					familia.append(option);
					familias.attr('class', 'oculto');
				}

				subfamilias.attr('class', 'oculto');
			});

			$('#familia').change(function(e) {
				e.preventDefault();
				var familia = $(this).val();
				console.log(familia);
				if(familia != 0) {
					$.getJSON('/obtener_subfamilias/' + familia, function(response) {
						console.log(response);
						var subfamilia = $('#subfamilia');
						subfamilia.empty();
						var option = $('<option/>', {'value': 0, 'text': 'Todas'});
						subfamilia.append(option);
						$.each(response, function(k, v) {
							var option = $('<option/>', {'value': v.IdSubfamilia, 'text': v.Subfamilia});
							subfamilia.append(option);
						});
					});

					subfamilias.attr('class', '');
				}
				else {
					subfamilias.attr('class', 'oculto');
				}
			});

			//Generamos el gráfico
			$.getJSON('/obtener_resultados/{{ $pregunta->id }}', function(data) {
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