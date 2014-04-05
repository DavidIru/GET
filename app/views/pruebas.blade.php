
	{{ HTML::style('css/principal.css') }}
	<!--[if lt IE 8]><!-->
	{{ HTML::style('css/ie7.css') }}
	<!--<![endif]-->
	

<table id="pedidos">
	<thead>
		<tr>
			<td data-dynatable-no-sort></td>
			<td>ID</td>
			<td>NOMBRE</td>
			<td>ROL_ID</td>
		</tr>
	</thead>
	@foreach ($pedidos as $pedido)
	<tbody>
		<tr>
			<td>
			@if($pedido->Situacion == 'Pendiente Recibir Material')
				<span class="icon-alarm amarillo"></span>
			@else
				<span class="icon-truck verde"></span>
			@endif
			</td>
			<td>{{ $pedido->NumeroDocumento }}</td>
			<td>{{ substr($pedido->CLNombre, 0, 20) }}</td>
			<td>{{ $pedido->CLCiudad }}</td>
		</tr>
	</tbody>
	@endforeach
</table>

{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js') }}
{{ HTML::script('js/jquery.dynatable.js') }}
	<script>
		$(document).ready(function() {
			
			$('#pedidos').dynatable({
				features: {
					recordCount: false
				}
			});
		});
	</script>