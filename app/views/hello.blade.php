<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
</head>
<body>
	<h1>Bienvenido {{ Auth::user()->nombre }}</h1>
	<a href="/logout">Cerrar sesión</a>
</body>
</html>
