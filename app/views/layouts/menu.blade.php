<nav id="menu">
	<h2>Acciones principales</h2>
	<ul>
		<li><a href="{{ URL::to('/') }}" title="Ir a inicio"><span class="icon-home naranja"></span>Inicio</a></li>
		@if ($rol_id == 1 || $rol_id == 2)
			<li><a href="{{ URL::to('pedidos') }}" title="Gestionar pedidos"><span class="icon-cart gris"></span>Pedidos</a></li>
		@endif
		<li>
			<a href="{{ URL::to('envios') }}" title="Gestionar envíos"><span class="icon-truck verde"></span>Envíos
			@if ($pedidos_programados != 0)
					<div class="circulo">{{ $pedidos_programados }}</div>
				@endif
			</a>
		</li>
		@if ($rol_id == 1 || $rol_id == 2)
		<li><a href="{{ URL::to('promociones') }}" title="Gestionar promociones"><span class="icon-tags rosa"></span>Promociones</a></li>
		<li id="menu-encuestas">
			<a id="btn-encuestas">
				<span class="icon-copy azul"></span>Encuestas
				@if ($comentarios_pendientes != 0)
					<div class="circulo">{{ $comentarios_pendientes }}</div>
				@endif
			</a>
			<ul>
				<li>
					<a href="{{ URL::to('encuestas/resultados') }}" title="Analizar resultados">
						<span class="icon-chart azul"></span>Analizar resultados
						@if ($comentarios_pendientes != 0)
							<div class="circulo">{{ $comentarios_pendientes }}</div>
						@endif
					</a>
				</li>
				<li><a href="{{ URL::to('encuestas/preguntas') }}" title="Listado de preguntas"><span class="icon-comments azul"></span>Listado de preguntas</a></li>
			</ul>
		</li>
		@endif
		<!-- <li>
			<a href="#" title="Gestionar notificaciones">
				<span class="icon-bell amarillo"></span>Notificaciones
				@if ($notificaciones != 0)
					<div class="circulo">{{ $notificaciones }}</div>
				@endif
			</a>
		</li> -->
	</ul>
	@if ($rol_id == 1)
	<h2>Acciones de administración</h2>
	<ul>
		<li><a href="{{ URL::to('usuarios') }}" title="Gestionar usuarios"><span class="icon-users morado"></span>Gestionar usuarios</a></li>
		<li><a href="{{ URL::to('mensajes') }}" title="Gestionar mensajes"><span class="icon-envelope azul-claro"></span>Mensajes por defecto</a></li>
	</ul>
	@endif
	<h2>Usuario</h2>
	<ul>
		<li><a href="{{ URL::to('perfil') }}" title="Ver perfil"><span class="icon-user"></span>{{ Auth::user()->nombre }}</a></li>
		<li><a href="{{ URL::to('logout') }}" title="Cerrar sesión"><span class="icon-switch rojo"></span>Cerrar sesión</a></li>
	</ul>	
</nav>