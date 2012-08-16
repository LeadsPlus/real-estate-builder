<?php 

class PLS_Map_Lifestyle extends PLS_Map {

	function lifestyle($listings = array(), $map_args = array(), $marker_args = array()) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false');
		ob_start();
		?>
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				var other_polygons = [];
				var other_text = [];
				var centers = [];
				jQuery(function($) { 
					google.maps.event.addDomListener(window, 'load', function() {
						var latlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
						var myOptions = { zoom: <?php echo $zoom; ?>, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
						<?php echo $map_js_var ?>.map = new google.maps.Map(document.getElementById("<?php echo $canvas_id ?>"), myOptions);
						<?php foreach (self::$markers as $marker): ?>
							<?php echo $marker; ?>
						<?php endforeach ?>	
						pls_center_map(<?php echo self::$map_js_var ?>);

						var coords = [];
						var request = {};
						// search_places();
						
						function search_places () {
							get_lifestyle_form(function (new_point, request) {
								request.location = new_point;
								var service = new google.maps.places.PlacesService(<?php echo self::$map_js_var ?>.map);
						        service.search(request, service_callback);	
							}, function (new_point, request) {
								var service = new google.maps.places.PlacesService(<?php echo self::$map_js_var ?>.map);
						        service.search(request, service_callback);	
							});
						}
				        
				        function service_callback(results, status) {
				        	var points = [];
					        if (status == google.maps.places.PlacesServiceStatus.OK) {
					        	for (var i = 0; i < results.length; i++) {						           
									points.push({lat: results[i].geometry.location.lat(), lng: results[i].geometry.location.lng()});
									pls_create_marker({latlng:results[i].geometry.location, content:results[i].name, icon: 'https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.3|0|FF8429|13|b' }, <?php echo self::$map_js_var ?>)
					          	}
					        }
					      }

					      function get_lifestyle_form (success_callback, failed_callback) {
					      	var response = {location: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>) , radius: 5000, types: ['']};
					      	var form_values = [];
					      	$.each($('#lifestyle_form_wrapper form').serializeArray(), function(i, field) {
								form_values.push(field.name);
							});
							if (form_values.length > 0) {
								response.types = [];
								for (key in form_values) {
									response.types.push(form_values[key]);
								};
							};
							failed_callback(null,response);
					      	return response;
					      }
					      
					      $('#lifestyle_form_wrapper form, .location_select_wrapper').live('change', function(event) {
					      	event.preventDefault();
					      	pls_clear_markers(<?php echo self::$map_js_var ?>);
					      	<?php foreach (self::$markers as $marker): ?>
								<?php echo $marker; ?>
							<?php endforeach ?>	
					      	search_places();
					      });
					});
				});	  
			</script>
			<?php echo self::get_lifestyle_controls($map_args); ?>
		<?php
		return ob_get_clean();
	}
}