<?php 

class PLS_Map_Lifestyle_Polygon extends PLS_Map {

	function lifestyle_polygon($listings = array(), $map_args = array(), $marker_args = array()) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		
     	wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false');
		wp_register_script('text-overlay', trailingslashit( PLS_JS_URL ) . 'libs/google-maps/text-overlay.js' );
		wp_enqueue_script('text-overlay');

		ob_start();
		?>
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				<?php echo $map_js_var; ?>.polygons = [];
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
						
						<?php if ($search_on_load): ?>
							search_places();
						<?php endif; ?>
						
						function search_places () {
							pls_show_loading_overlay();
							var location = get_location();
							console.log(location);
							if (location && location.address) {
								pls_geocode(location.address, <?php echo self::$map_js_var ?>, search_callback, function () {
									search_callback(new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>));
								});		
							} else {
								search_callback(new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>));
							};
						}
						
						function search_callback (new_point) {
							request = {};
							request.action = 'lifestyle_polygon';
							request.location = {};
							request.location = '' + new_point.lat() + ',' + new_point.lng();
							request.radius = $('.location_select select.radius').val();
							request.types = get_form_items();
							$.post(info.ajaxurl, request, function(data, textStatus, xhr) {
								var results = data.places;
								if (results) {
									for (var i = 0; i < results.length; i++) {						           
										pls_create_marker({latlng: new google.maps.LatLng(results[i].geometry.location.lat, results[i].geometry.location.lng), content:results[i].name, icon: 'https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.3|0|FF8429|13|b' }, <?php echo self::$map_js_var ?>)
					          		}
								};
					          	if (data.polygon) {
				        			pls_create_polygon(data.polygon,{strokeColor: '#55b429',strokeOpacity: 1.0,strokeWeight: 3, fillColor: 'c0ecac'}, <?php echo self::$map_js_var ?>);
				        		};
				        		if (data.listings) {
				        			for (var i = data.listings.length - 1; i >= 0; i--) {
				        				pls_create_listing_marker(data.listings[i], <?php echo self::$map_js_var ?>);
				        			};
				        		};
				        		pls_hide_loading_overlay();
							}, 'json');
						}

						function get_form_items() {
							var response = [];
							var form_values = [];
					      	$.each($('#lifestyle_form_wrapper form').serializeArray(), function(i, field) {
								form_values.push(field.name);
							});
							if (form_values.length > 0) {
								response = [];
								for (key in form_values) {
									response.push(form_values[key]);
								};
							};
							return response.join('|');
						}
				        
						function get_location () {
							var response = false;
							var location_type = $('#lifestyle_form_wrapper select[name="location"]').val();
							var location_value = $('.location_select_wrapper select.' + location_type).val();
							if (location_value != 'Any') {
								response = {};
								response.address = location_value;
							} 
							return response
						}
					      
					      $('#lifestyle_form_wrapper form, .location_select_wrapper, .location_select').live('change', function(event) {
					      	event.preventDefault();
					      	pls_clear_markers(<?php echo self::$map_js_var ?>);
					      	pls_clear_polygons(<?php echo self::$map_js_var ?>);
					      	search_places();
					      });

					      $('#lifestyle_form_wrapper select.location').live('change', function(event) {
					      	event.preventDefault();
					      	update_lifestyle_location_selects();
					      });
							
						  update_lifestyle_location_selects();
					      function update_lifestyle_location_selects () {
					      	var location_type = $('#lifestyle_form_wrapper select[name="location"]').val();
					      	$('.location_select_wrapper').hide();
					      	$('.location_select_wrapper select.' + location_type).parent().show().find('.chzn-container').css('width', '150px');
					      }
					});
				});	  
			</script>
			<?php echo self::get_lifestyle_controls($map_args); ?>
		<?php
		return ob_get_clean();
	}

}