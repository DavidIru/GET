<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<!-- <meta name="apple-mobile-web-app-capable" content="yes"> -->
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<title>@yield('titulo', 'GET')</title>
	@yield('estilos')
	<!--[if lt IE 9]>
		<script type="text/javascript">
		   document.createElement("nav");
		   document.createElement("header");
		   document.createElement("footer");
		   document.createElement("section");
		   document.createElement("article");
		</script>
	<![endif]-->
</head>
<body>
	<header>
		<figure>
			{{ HTML::image('img/logo.png', "eIruzubieta.com", array('id' => 'logo', 'title' => 'eIruzubieta.com')) }}
		</figure>
	</header>
	<div id="contenedor">		
		<div id="contenido">
			@yield('contenido')
			<footer>
				@yield('footer')
			</footer>
		</div>
	</div>
	{{-- HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js') --}}
	{{ HTML::script('js/jquery.js') }}
	@yield('scripts')
</body>
</html>