@extends('layouts.master')

@section('titulo')
	GET - Añadir pregunta
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
			<p>Añadir pregunta</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="usuario">
		<div id="usuarioadd">
			<h2>Creación de nueva pregunta</h2>
			<p>Introduzca los datos para la creación de la nueva pregunta. La longitud máxima es de 200 caracteres.</p>
			@if (isset($errors) && $errors->all())
				<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
					@foreach ($errors->all() as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@endif
			{{ Form::open() }}
				<div>
					{{ Form::label('texto', 'Pregunta') }}
					{{ Form::text('texto', Input::old('texto'), array('required' => 'required', 'placeholder' => 'Pregunta')) }}
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
				</div>
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.placeholder.js') }}
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
		});
	</script>
@stop