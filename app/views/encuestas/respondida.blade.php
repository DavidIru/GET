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
	<div id="mensaje" class="exito">
		<h4>¡Gracias por sus respuestas!</h4>
		<p>La información ha sido almacenada en la base de datos. Agradecemos la ayuda para poder mejorar el servicio día a día.</p>
	</div>
@stop