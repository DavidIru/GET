<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<!-- <meta name="apple-mobile-web-app-capable" content="yes"> -->
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<title>@yield('titulo', 'GET')</title>
	@yield('estilos')
</head>
<body>
	<header>
		<figure>
			{{ HTML::image('img/logo.png', "eIruzubieta.com", array('id' => 'logo', 'title' => 'eIruzubieta.com')) }}
		</figure>
		<span class="icon-menu"></span>
	</header>
	<div id="contenedor">
		@include('layouts.menu', array('rol_id' => Auth::user()->rol_id, 'notificaciones' => Auth::user()->notificaciones))
		
		<div id="contenido">
			@yield('breadcrumb')
			@yield('acciones')
			@yield('contenido')
			<footer>
				@yield('footer')
			</footer>
		</div>
	</div>
	{{-- HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js') --}}
	{{ HTML::script('js/jquery.js') }}
	@yield('scripts')
	<script>
		$(document).ready(function() {
			var desplegado = false;
			var encuestas = false;
			$('header span').on('click', function() {
				if(desplegado) {
					$("#contenido").animate({ "margin-left": "0"}, 500, "linear");
					$("#contenido").css("overflow", "auto");
					//$("nav").css("z-index", "1");
					desplegado = false;
				} else {
					$("#contenido").animate({ "margin-left": "240px"}, 500, "linear");
					$("#contenido").css("overflow", "hidden");
					//$("nav").animate({"z-index": "3"});
					desplegado = true;
				}
			});

			$('#btn-encuestas').on('click', function() {
				if(encuestas) {
					$('#menu-encuestas').animate({ "height": "2.25em"}, 500, "linear");
					encuestas = false;
				} else {
					$('#menu-encuestas').animate({ "height": "7.5em"}, 500, "linear");
					encuestas = true;
				}
			});
		});
	</script>
</body>
</html>