@extends('layouts.master')

@section('titulo')
	GET - Comentario {{ $comentario->id}}
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
			<a href="{{ URL::to('encuestas/resultados')}}" title="Volver a Encuestas - Resultados">Encuestas - Resultados</a>
			<span>></span>
			<p>Comentario {{ $comentario->id }}</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="comentario">
		<div id="comentariodatos">
			<h2>Datos del comentario</h2>
			<h4>
				@if ($leido == 1)
					Este comentario fue leído el {{ date("d/m/Y", strtotime($comentario->updated_at)) }} a las {{ date("H:i", strtotime($comentario->updated_at))."h" }}
				@else
					Este comentario aun no había sido leído
				@endif
			</h4>
			<h3><strong>Comentario:</strong> {{ $comentario->comentario }}</h3>
		</div>
	</section>
@stop