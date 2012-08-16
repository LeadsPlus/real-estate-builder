<?php 

class PLS_Map_Listings extends PLS_Map {

	function listings($listings = array(), $map_args = array(), $marker_args = array()) {
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
					});

					<?php if ($ajax_form_class): ?>
					$('.<?php echo $ajax_form_class ?>').live('change', function () {
						update_listings();
					});

					function update_listings (){
						var request = {};
				      	$.each($('.<?php echo $ajax_form_class ?>').serializeArray(), function(i, field) {
							request[field.name] = field.value;
						});
						request.action = 'pls_listings_ajax';
						request.iDisplayLength = 50;
						request.iDisplayStart = 0;
						request.context = '<?php echo $ajax_form_class ?>';
						request.sEcho = 1;
						pls_show_loading_overlay();
						$.post(info.ajaxurl, request, function(ajax_response, textStatus, xhr) {
							console.log(ajax_response);
						  if (ajax_response && ajax_response['aaData'] && typeof <?php echo $map_js_var; ?> !== 'undefined') {
		                        pls_clear_markers(<?php echo $map_js_var; ?>);
		                        if (typeof window['google'] != 'undefined') {
		                          for (var listing in ajax_response['aaData']) {
		                              var listing_json = ajax_response['aaData'][listing][1];
		                              pls_create_listing_marker(listing_json, <?php echo $map_js_var; ?>);
		                          }
		                        }
		                    };
		                    pls_hide_loading_overlay();
						}, 'json');
						
						console.log(request);
					}
					<?php endif; ?>
				});	  
			</script>
			<?php echo self::get_lifestyle_controls($map_args); ?>
		<?php
		return ob_get_clean();
	}

}