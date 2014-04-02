<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<title>Login en la aplicación</title>
	{{ HTML::style('css/login.css') }}
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js') }}
</head>
<body>
	<figure>
		{{ HTML::image('img/logo.jpg', "eIruzubieta.com", array('id' => 'logo', 'title' => 'eIruzubieta.com')) }}
	</figure>
	<form action="" id="login">
		<label for="usuario" class="icon-user"></label>
		<input type="text" id="usuario" placeholder="Nombre de usuario" required="required">
		<label for="pass" class="icon-key"></label>
		<input type="password" id="pass" placeholder="Contraseña" required="required">
		<input type="submit" id="enviar">
	</form>
</body>
</html>