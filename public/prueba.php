<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		#map_canvas {
			width: 100%;
height: 400px;
		}
	</style>
</head>
<body>
	<div id="map_canvas"></div>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.map.full.min.js"></script>
	<script type="text/javascript">
            $(function() {
            	//$('#map_canvas').gmap({ 'center': '42.4571027,-2.4645784' });
            	/*
				$('#map_canvas').gmap().bind('init', function() {
						var self = this;
						$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address=Alfonso+VI+9,+logro%C3%B1o&sensor=false', function(data) {
							//console.log(data.results[0].geometry.location.lat);
							self.addMarker({ 'position': new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng), 'bounds':true } ).click(function() {
									self.openInfoWindow({ 'content': 'Dirección de la entrega' }, this)});
						});
				});
				*/
				
				$('#map_canvas').gmap().bind('init', function(evt, map) { 
					$.getJSON( 'http://maps.googleapis.com/maps/api/geocode/json?address=Alfonso+VI+9,+logro%C3%B1o&sensor=false', function(data) { 
						$.each( data.results, function(i, m) {
							$('#map_canvas').gmap('addMarker', { 'position':   m.geometry.location.lat+ ',' + m.geometry.location.lng, 'bounds': true }).click(function() {
								$('#map_canvas').gmap('openInfoWindow', { 'content': 'Dirección de entrega' }, this);
							});
						});
						$('#map_canvas').gmap('option', 'zoom', 15);
					});
				});
				

				//$('#map_canvas').gmap('option', 'zoom', 10);
				/*
				$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address=Alfonso+VI+9,+logro%C3%B1o&sensor=false', function (result) {
				    console.log(result);
				});
*/
				
			});
        </script>

        <?php
        	echo urlencode(stripslashes('ñoño de la muerte, logroño'));
        ?>

</body>
</html>