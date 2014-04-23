@extends('layouts.master')

@section('titulo')
	GET - Programar envío
@stop

@section('estilos')
	{{ HTML::style('css/principal.css') }}
	<!--[if lt IE 8]><!-->
	{{ HTML::style('css/ie7.css') }}
	<!--<![endif]-->
	{{ HTML::style('js/pickadate/themes/default.css', array('id' => 'theme_base')) }}
	{{ HTML::style('js/pickadate/themes/default.date.css', array('id' => 'theme_date')) }}
	{{ HTML::style('js/pickadate/themes/default.time.css', array('id' => 'theme_time')) }}
@stop

@section('breadcrumb')
	<div id="breadcrumbs">
		<h3>
			<a href="{{ URL::to('/')}}" title="Volver al inicio">Inicio</a>
			<span>></span>
			<a href="{{ URL::to('envios')}}" title="Volver a Envíos">Envíos</a>
			<span>></span>
			<a href="{{ URL::to('envio/'.$envio->IdDocumento.'')}}" title="Volver a envío {{ $envio->NumeroDocumento }}">Envío {{ $envio->NumeroDocumento }}</a>
			<span>></span>
			<p>Programar</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="envio">
		<div id="envioprogramar">
			<h2>Programación de envío</h2>
			<p>Introduzca la fecha y la hora para el envío. Puede seleccionar si avisar o no al cliente mediante SMS.</p>
			@if (isset($errores) && $error == 1)
				<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
					@foreach ($errores as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@endif
			{{ Form::open() }}
				<div class="form">
					{{ Form::label('fecha', 'Fecha del envío') }}
					{{ Form::text('fecha', date('d/m/Y', strtotime($envio->FechaEntrega)), array('required' => 'required', 'placeholder' => 'Fecha del envío')) }}
				</div>
				<div class="form">
					{{ Form::label('hora', 'Hora del envío') }}
					{{ Form::text('hora', date('H:i', strtotime($envio->HoraEntrega)), array('required' => 'required', 'placeholder' => 'Hora del envío')) }}
				</div>
				<div id="check">
					<input type="checkbox" name="avisarp" id="avisarp" value="1">{{ Form::label('avisarp', 'Avisar al cliente') }}
					<div>
						{{ Form::label('telefonop', 'Teléfono') }}
						{{ Form::text('telefonop', (is_null($envio->telefonoAviso))? explode(' ', $envio->CLTelefonoEnvio)[0] : $envio->telefonoAviso, array('placeholder' => 'Teléfono')) }}
					</div>
				</div>
				{{ Form::submit('Programar') }}
			{{ Form::close() }}
		</div>
		<div id="enviocancelar">
			<h2><span class="icon-times-circle rojo"></span>Cancelar envío</h2>
			<p>Para cancelar el envío pulse el siguiente botón. El pedido volverá a la lista de pendientes.</p>
			@if (isset($errores) && $error == 2)
				<div id="mensaje" class="error">
					<h4>Revise lo siguiente:</h4>
					@foreach ($errores as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@endif
			{{ Form::open(array('url' => '/envio/'.$envio->IdDocumento.'/cancelar')) }}
				{{ Form::hidden('borrar', 'borrar') }}
				<div id="check2">
					<input type="checkbox" name="avisarc" id="avisarc" value="1">{{ Form::label('avisarc', 'Avisar al cliente') }}
					<div>
						{{ Form::label('telefonoc', 'Teléfono') }}
						{{ Form::text('telefonoc', (is_null($envio->telefonoAviso))? explode(' ', $envio->CLTelefonoEnvio)[0] : $envio->telefonoAviso, array('placeholder' => 'Teléfono')) }}
					</div>
				</div>
				{{ Form::submit('Cancelar envío', array('onclick' => "return window.confirm('¿Está seguro de que desea cancelar el envío ".$envio->NumeroDocumento."?')")) }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('js/pickadate/picker.js') }}
	{{ HTML::script('js/pickadate/picker.date.js') }}
	{{ HTML::script('js/pickadate/picker.time.js') }}
	{{ HTML::script('js/pickadate/legacy.js') }}
	{{ HTML::script('js/jquery.placeholder.js') }}
	<script>
		$(document).ready(function($) {
			$('input, textarea').placeholder();
			$('input[name=fecha]').pickadate({
				format: 'dd/mm/yyyy',
			    formatSubmit: 'yyyy-mm-dd',
			    hiddenPrefix: 'envio_',
			    hiddenSuffix: '',
			    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
			    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
				weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
				today: 'Hoy',
				clear: 'Cancelar',
				firstDay: 1,
				min: new Date()
			});

		    $('input[name=hora]').pickatime({
	    		format: 'HH:i',
	    		formatSubmit: 'HH:i',
	    		interval: 15
			});

		    $('#avisarp').on('change', function() {
				if($(this).is(':checked')) {
					$('#check').animate({ "height": "5em"}, 200, "linear");
				}
				else {
					$('#check').animate({ "height": "1.3em"}, 200, "linear");
				}
			});

			$('#avisarc').on('change', function() {
				if($(this).is(':checked')) {
					$('#check2').animate({ "height": "5em"}, 200, "linear");
				}
				else {
					$('#check2').animate({ "height": "1.3em"}, 200, "linear");
				}
			});
		});
	</script>
@stop