@extends('layouts.master')

@section('titulo')
	GET - Enviar promoción
@stop

@section('estilos')
	{{ HTML::style('css/principal.css') }}
	<!--[if lt IE 8]><!-->
	{{ HTML::style('css/ie7.css') }}
	<!--<![endif]-->
	{{ HTML::style('css/redactor.css') }}
@stop

@section('breadcrumb')
	<div id="breadcrumbs">
		<h3>
			<a href="{{ URL::to('/')}}" title="Volver al inicio">Inicio</a>
			<span>></span>
			<a href="{{ URL::to('promociones')}}" title="Volver a Gestionar promociones">Gestionar promociones</a>
			<span>></span>
			<p>Enviar promoción</p>
		</h3>
	</div>
@stop

@section('contenido')
	<section id="promocion">
		<div id="promosms">
			<h2>Enviar promoción por sms</h2>
			<p>Introduzca el texto a enviar como promoción por SMS</p>
			@if (isset($mensaje) && $mensaje['tipo'] == "sms")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
						<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>La promoción se ha enviado correctamente.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				{{ Form::textarea('textsms', Input::old('textsms'), array('required' => 'required', 'id' => 'textsms')); }}
				{{ Form::hidden('tipo', 'sms') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
		<div id="promomail">
			<h2>Enviar promoción por e-mail</h2>
			<p>Componga la promoción a enviar por e-mail.</p>
			@if (isset($mensaje) && $mensaje['tipo'] == "email")
				@if ($mensaje['error'])
					<div id="mensaje" class="error">
						<h4>Revise lo siguiente:</h4>
						@foreach ($errores as $error)
							<p>{{ $error }}</p>
						@endforeach
				@else
					<div id="mensaje" class="exito">
						<p>La promoción se ha enviado correctamente.</p>
				@endif
					</div>
			@endif
			{{ Form::open() }}
				{{ Form::label('asunto', 'Asunto del e-mail') }}
				{{ Form::text('asunto', Input::old('asunto'), array('required' => 'required', 'placeholder' => 'Asunto del e-mail')); }}
				{{ Form::label('textmail', 'Contenido del e-mail') }}
				<div id="botones">
					<a href="#" data-texto="#nombre#">Nombre del cliente</a>
					<a href="#" data-texto="#email#">E-mail del cliente</a>
				</div>
				{{ Form::textarea('textmail', Input::old('textmail'), array('required' => 'required', 'id' => 'textmail')); }}
				{{ Form::hidden('tipo', 'email') }}
				{{ Form::submit('Enviar') }}
			{{ Form::close() }}
		</div>
	</section>
@stop

@section('scripts')
	{{ HTML::script('js/redactor/redactor.js') }}
	{{ HTML::script('js/redactor/langs/es.js') }}
	{{ HTML::script('js/redactor/plugins/fontcolor.js') }}
	{{ HTML::script('js/redactor/plugins/fontsize.js') }}
	{{ HTML::script('js/redactor/plugins/fontfamily.js') }}
	{{ HTML::script('js/jquery.maxlength.min.js') }}
	<script>
		$(document).ready(function() {
			$('#textmail').redactor({
				boldTag: 'strong',
				italicTag: 'em',
				buttons: ['bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist',
							'outdent', 'indent', 'image', 'video', 'file', 'table', 'link', 'alignment', 
							'horizontalrule'],
				//clipboardUploadUrl: '/scripts/upload.php',
				convertVideoLinks: true,
				//imageUpload: '/scripts/image_upload.php',
				lang: 'es',
				observeLinks: true,
				plugins: ['fontcolor', 'fontsize', 'fontfamily']
			});

			$('#textsms').maxlength({   
				events: [], 
				maxCharacters: 160,
				status: true,  
				statusClass: "restantes",
				statusText: "caracteres restantes", 
				notificationClass: "notificacion",
				showAlert: false, 
				alertText: "Ha escrito demasiados caracteres.",
				slider: false  
			});

			$('#botones a').on('click', function(e) {
				e.preventDefault();
				var texto = $('.redactor_.redactor_editor');
				texto.html(texto.html().substr(0, texto.html().length -4) + $(this).data("texto") + '</p>');
			});
		});
	</script>
@stop