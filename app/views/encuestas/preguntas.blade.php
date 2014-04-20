@extends('layouts.encuesta')

@section('titulo')
	GET - Encuesta
@stop

@section('estilos')
	{{ HTML::style('css/encuesta.css') }}
	<!--[if lt IE 8]><!-->
	{{ HTML::style('css/ie7.css') }}
	<!--<![endif]-->
@stop

@section('contenido')
	<h2>Encuesta del pedido {{ $encuesta->pedido->NumeroDocumento }}<span>entregado el día {{ date('d/m/Y', strtotime($encuesta->pedido->FechaEntrega)) }}</span></h2>
	<h3>Los resultados de la encuesta son completamente anónimos por lo que agradecemos la máxima sinceridad para mejorar nuestro servicio. Las preguntas se responden de 0 a 10 puntos y todas son obligatorias. Al final de la encuesta encontrará un campo de texto opcional para poder escribirnos los comentarios que quiera.</h3>
	@if (isset($errors) && !empty($errors->all()))
		<div id="mensaje" class="error">
			<h4>Revise lo siguiente:</h4>
			<p>Debe responder a todas las preguntas</p>
		</div>
	@endif
	{{ Form::open() }}
		@foreach ($encuesta->preguntas as $preguntaEnv)
			<h4>{{ $preguntaEnv->pregunta->texto }}</h4>
			<h5>
				@for ($i = 0; $i < 11; $i++)
					<div>
						<p>{{ Form::radio($preguntaEnv->pregunta->id, $i, (Input::old($preguntaEnv->pregunta->id) != "" && Input::old($preguntaEnv->pregunta->id) == $i)? true : false) }}</p>
						<p class="valor">{{ $i }}</p>
					</div>
				@endfor
			</h5>
		@endforeach
		<h4>¿Tiene alguna sugerencia?</h4>
		{{ Form::textarea('comentario', '', array('placeholder' => 'Escriba aquí sus sugerencias')) }}
		{{ Form::submit('Enviar') }}
	{{ Form::close() }}
@stop

@section('scripts')
	{{ HTML::script('js/jquery.placeholder.js') }}
	<script>
		$(document).ready(function($) {
			$('input, textarea').placeholder();
		});
	</script>
@stop