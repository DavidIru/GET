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
			<p>Gestionar encuestas</p>
		</h3>
	</div>
@stop

@section('contenido')
	<h2>Listado de preguntas{{ (isset($filtro) && $filtro)? " filtrado" : '' }}</h2>
	<a href="{{ URL::to('encuestas/pregunta/add') }}" class="boton-listado">
		<span class="icon-plus-circle verde-oscuro"></span>Añadir pregunta
	</a>
	<a href="#" class="boton-listado" id="boton-filtrar">
		<span class="icon-search naranja"></span>Filtrar preguntas
	</a>
	<div id="filtro">
		{{ Form::open() }}
			{{ Form::label('agrupacion', 'Familia agrupación') }}
			<select id="agrupacion" name="agrupacion">
			<option value="0" selected="selected">Todas</option>
			@foreach ($agrupaciones as $agrupacion)
				<option value="{{ $agrupacion->IdAgrupacion }}">{{ $agrupacion->AgrupacionFamilia }}</option>
			@endforeach
			</select>
			<div id="familias" class="oculto">
				{{ Form::label('familia', 'Familia') }}
				<select id="familia" name="familia">
				<option value="0" selected="selected">Todas</option>
				</select>
			</div>
			<div id="subfamilias" class="oculto">
				{{ Form::label('subfamilia', 'Subfamilia') }}
				<select id="subfamilia" name="subfamilia">
				<option value="0" selected="selected">Todas</option>
				</select>
			</div>
			{{ Form::submit('Filtrar') }}
		{{ Form::close() }}
	</div>
	@if(isset($exito))
		<div id="mensaje" class="exito"><p>{{ $exito }}</p></div>
	@endif
	<div>
		<table id="tabla">
			<thead>
				<tr>
					<th style="display: none">id</th>
					<th>Pregunta</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($preguntas as $pregunta)
				<tr>
					<td>{{ $pregunta->id }}</td>
					<td>{{ $pregunta->texto }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.dynatable.js') }}
	<script>
		$(document).ready(function() {
			var ver_filtro = false;
			$('#tabla').dynatable({
				features: {
					recordCount: false
				},
				dataset: {
					perPageDefault: 20
				}
			});

			$('#boton-filtrar').on('click', function() {
				if(!ver_filtro) {
					$('#filtro').fadeIn(500);
					ver_filtro = true;
				} else {
					$('#filtro').fadeOut(500);
					ver_filtro = false;
				}
			});

			$('#tabla tbody').on('click', "tr", function() {
				$(location).attr('href',"{{ URL::to('encuestas/pregunta/" + $(this).data("id") + "') }}");
			});
			
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
		});
	</script>
@stop