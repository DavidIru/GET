<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="/css/redactor.css" />
	<script src="/js/jquery.js"></script>
	<script src="/js/redactor.js"></script>

</head>
<body>
	<?php
		$temp = "";
		$sustituciones = array(
			'nombre' => '$cliente->nombre',
			'email' => '$cliente->email'
		);

		$campo = "nombre";
		if(array_key_exists($campo, $sustituciones)) {
			$temp = "{{$sustituciones[$campo]}}";
		}
		else echo "mal";

		echo $temp;
	?>
	<script>
		$(document).ready(function() {
			$('#contenido').redactor({
				toolbarFixedBox: true
			});
		});
	</script>
</body>
</html>