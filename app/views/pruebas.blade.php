<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<title>PRUEBAS</title>
	{{ HTML::style('css/principal.css') }}
	<!--[if lt IE 8]><!-->
	{{ HTML::style('css/ie7.css') }}
	<!--<![endif]-->
</head>
<body>
	<div id="container" style="width:100%; height:400px;"></div>

	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js') }}
	{{ HTML::script('js/charts/highstock.js') }}
	<script>
		$(document).ready(function($) {
			

		    $.getJSON('/obtener_resultados', function(data) {
		    	Highcharts.setOptions({
					lang: {
						months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
						weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
						shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
						loading: "Cargando"
					},
					global: {
			            timezoneOffset: -2 * 60
			        }
				});

		        $('#container').highcharts('StockChart', {
				    chart: {
				        alignTicks: true
				    },

				    series: [{
				        type: 'column',
				        name: 'Nota media',
				        data: data,
				        dataGrouping: {
							units: [[
								'month',
								[1]
							]]
				        },
				        tooltip: {
							valueDecimals: 2
						}
				    }],

				    credits: {
				    	/*
		                text: 'webKreativos.com',
		        		href: 'http://www.webkreativos.com'
		        		*/
		        		enabled: false
		            },
		            
		            rangeSelector: {
		            	enabled: false,
		            },

		            yAxis : {
						plotBands : [{
							from : 0,
							to : 4.5,
							color : 'rgba(255,161,161,.8)'
						}, {
							from : 4.5,
							to : 8,
							color : 'rgba(255,243,161,.8)'
						}, {
							from : 8,
							to : 10,
							color : 'rgba(170,243,117,.6)'
						}]
					},

				});
		    });
		});
	</script>
	
</body>
</html>