
	{{ HTML::style('css/principal.css') }}
	<!--[if lt IE 8]><!-->
	{{ HTML::style('css/ie7.css') }}
	<!--<![endif]-->
	
	{{ Form::open() }}
		<label for="agrupacion">Familia agrupación</label>
		<select id="agrupacion" name="agrupacion">
			<option value="0"{{ (is_null($pregunta->agrupacion_id))? 'selected="selected"' : '' }}>Todas</option>
		@foreach ($agrupaciones as $agrupacion)
			<option value="{{ $agrupacion->IdAgrupacion }}"{{ ($agrupacion->IdAgrupacion == $pregunta->agrupacion_id)? 'selected="selected"' : '' }}>{{ $agrupacion->AgrupacionFamilia }}</option>
		@endforeach
		</select>
		<div id="familias">
			<label for="familia">Familia</label>
			<select id="familia" name="familia"{{ (is_null($pregunta->agrupacion_id))? 'class="oculto"' : '' }}>
				<option value="0"{{ (is_null($pregunta->familia_id))? 'selected="selected"' : '' }}>Todas</option>
			@foreach ($familias as $familia)
				<option value="{{ $familia->IdFamilia }}"{{ ($familia->IdAgrupacion == $pregunta->familia_id)? 'selected="selected"' : '' }}>{{ $familia->Familia }}</option>
			@endforeach
			</select>
		</div>
		<div id="subfamilias">
			<label for="familia">Subfamilia</label>
			<select id="subfamilia" name="subfamilia"{{ (is_null($pregunta->familia_id))? 'class="oculto"' : '' }}>
				<option value="0"{{ (is_null($pregunta->agrupacion_id))? 'selected="selected"' : '' }}>Todas</option>
			@foreach ($agrupaciones as $agrupacion)
				<option value="{{ $agrupacion->IdAgrupacion }}"{{ ($agrupacion->IdAgrupacion == $pregunta->agrupacion_id)? 'selected="selected"' : '' }}>{{ $agrupacion->AgrupacionFamilia }}</option>
			@endforeach
			</select>
		</div>
		{{ Form::hidden('mensaje', 'mensaje1') }}
		{{ Form::submit('Enviar') }}
	{{ Form::close() }}

	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js') }}

	<script>
		$(document).ready(function($) {
			var familias = $('#familias');
			var subfamilias = $('#subfamilias');
			$('#agrupacion').change(function(e) {
				e.preventDefault();
				var agrupacion = $(this).val();
				if(agrupacion != 0) {
					$.getJSON('obtener_familias/' + agrupacion, function(response) {
						var familia = $('#familia');
						familia.empty();
						var option = $('<option/>', {'value': 0, 'text': 'Todas'});
						familia.append(option);
						$.each(response, function(k, v) {
							var option = $('<option/>', {'value': v.IdFamilia, 'text': v.Familia});
							familia.append(option);
						});
					});

					familias.css('display', 'inline-block');
				}
				else {
					familias.css('display', 'none');
				}

				subfamilias.css('display', 'none');
			});

			$('#familia').change(function(e) {
				e.preventDefault();
				var familia = $(this).val();
				console.log(familia);
				if(familia != 0) {
					$.getJSON('obtener_subfamilias/' + familia, function(response) {
						console.log(response);
						var subfamilia = $('#subfamilia');
						subfamilia.empty();
						var option = $('<option/>', {'value': 0, 'text': 'Todas'});
						subfamilia.append(option);
						$.each(response, function(k, v) {
							var option = $('<option/>', {'value': v.IdSubfamilia, 'text': v.Subfamilia});
							subfamilia.append(option);
						});
					});

					subfamilias.css('display', 'inline-block');
				}
				else {
					subfamilias.css('display', 'none');
				}
			});
		});
	</script>