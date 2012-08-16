<?php 

PLS_Map::init();
class PLS_Map {

	static $response;

	static $map_js_var;

	static $markers = array();

	function init() {
		add_action('wp_footer', array(__CLASS__, 'utilities'));
	}

	function get_lifestyle_controls ($map_args) {
		extract($map_args);
		ob_start();
		?>
		<div class="map_wrapper" style="position: relative">
				<div id="loading_overlay" class="loading_overlay" style="z-index: 50; display: none; position: absolute; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"><?php echo $loading_overlay ?></div>
				<div id="empty_overlay" class="empty_overlay" style="z-index: 50; display: none; position: absolute; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"><?php echo $empty_overlay ?></div>
				<div id="full_overlay" class="full_overlay" style="z-index: 50; display: none; position: absolute;"><?php echo $full_overlay ?></div>
				<div class="<?php echo $class ?>" id="<?php echo $canvas_id ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"></div>
				<section class="lifestyle_form_wrapper" id="lifestyle_form_wrapper">
					<?php if ($show_lifestyle_controls): ?>
						<div class="location_wrapper">
							<?php echo implode(self::get_area_selectors($map_args), '') ?>
						</div>
						<div class="clear"></div>
					<?php endif ?>			
					<div class="checkbox_wrapper">
						<form>
						<?php if ($show_lifestyle_checkboxes): ?>
							<?php echo self::get_lifestyle_checkboxs($map_args); ?>
						<?php endif ?>
						</form>
					</div>
				</section>	
			</div>
		<?php
		return ob_get_clean();
	}

	private static function get_area_selectors ($map_args = array()) {

			$cache = new PLS_Cache('form');
			if ($result = $cache->get($map_args)) {
				return $result;
			}

			$response = array();
			$form_options = array();
			$form_options['locality'] = array_merge(array('false' => '---'), PLS_Plugin_API::get_location_list('locality'));
	        $form_options['region'] = array_merge(array('false' => '---'), PLS_Plugin_API::get_location_list('region'));
	        $form_options['postal'] = array_merge(array('false' => '---'),PLS_Plugin_API::get_location_list('postal')); 
	        $form_options['neighborhood'] = array_merge(array('false' => '---'),PLS_Plugin_API::get_location_list('neighborhood')); 
	        
	        $response['location'] = '<div class="location_select"><select name="location" class="location" style="width: 140px">
				<option value="locality">City</option>
				<option value="region">State</option>
				<option value="postal">Zip</option>
				<option value="neighborhood">Neighborhood</option>
			</select></div>';
	        $response['locality'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[locality]', 'class' => 'locality' ), pls_h_options( $form_options['locality'], wp_kses_post(@$_POST['location']['locality'] ), true )) . '</div>';
	        $response['region'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[region]', 'class' => 'region' ), pls_h_options( $form_options['region'], wp_kses_post(@$_POST['location']['region'] ), true )) . '</div>';
	        $response['postal'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[postal]', 'class' => 'postal' ), pls_h_options( $form_options['postal'], wp_kses_post(@$_POST['location']['postal'] ), true )) . '</div>';
	        $response['neighborhood'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[neighborhood]', 'class' => 'neighborhood' ), pls_h_options( $form_options['neighborhood'], wp_kses_post(@$_POST['location']['neighborhood'] ), true )) . '</div>';
	        if ($map_args['lifestyle_distance'] == 'miles') {
	        	$response['radius'] = '<div class="location_select"><select name="radius" class="radius" style="width: 140px">
											<option value="402">1/4 mile</option>
											<option value="804">1/2 mile</option>
											<option value="1207">3/4 mile</option>
											<option value="1609">1 mile</option>
											<option value="4828" selected>3 miles</option>
											<option value="8046">5 miles</option>
											<option value="16093">10 miles</option>
										</select></div>';
	        } else {
				$response['radius'] = '<div class="location_select"><select name="radius" class="radius" style="width: 140px">
											<option value="200">200 meters</option>
											<option value="500">500 meters</option>
											<option value="1000">1000 meters</option>
											<option value="2000">2000 meters</option>
											<option value="5000" selected>5000 meters</option>
											<option value="10000">10000 meters</option>
											<option value="20000">20000 meters</option>
										</select></div>';
	        }
	        $cache->save($response);
	        return $response;
	}

	private static function get_lifestyle_checkboxs () {
		$lifestyle_checkboxes = array('park', 'campground', 'food', 'restaurant', 'bar', 'bowling_alley', 'amusement_park', 'aquarium', 'movie_theater', 'stadium', 'school', 'university', 'pet_store', 'bus_station', 'subway_station', 'train_station', 'clothing_store', 'department_store', 'electronics_store', 'shopping_mall', 'grocery_or_supermarket');
		ob_start();
		?>
			<?php foreach ($lifestyle_checkboxes as $checkbox): ?>
				<section class="lifestyle_checkbox_item" id="lifestyle_checkbox_item">
					<input type="checkbox" name="<?php echo $checkbox ?>" id="<?php echo $checkbox ?>">
					<label for="<?php echo $checkbox ?>"><?php echo ucwords(str_replace('_', ' ', $checkbox)) ?></label>
				</section>
			<?php endforeach ?>	
		<?php
		return ob_get_clean();
	}

	static function make_markers($listings, $marker_args, $map_args) {
    self::$markers = array();
		if ( isset($listings[0]) ) {
			foreach ($listings as $listing) {
				self::make_marker($listing, $marker_args);
			}
		} elseif (!empty($listings)) {
			self::make_marker($listings, $marker_args);
		} elseif ($map_args['featured_id']) {
			$api_response = PLS_Listing_Helper::get_featured($featured_option_id);
			foreach ($api_response['listings'] as $listing) {
				self::make_marker($listing, $marker_args);
			}
		} elseif ($map_args['auto_load_listings']) {
			$api_response = PLS_Plugin_API::get_property_list($map_args['request_params']);
			foreach ($api_response['listings'] as $listing) {
				self::make_marker($listing, $marker_args);
			}
		}
	}

	static function make_marker($listing = array(), $args = array()) {
		extract(self::process_marker_defaults($listing, $args), EXTR_SKIP);
		ob_start();
			?>
				pls_create_listing_marker(<?php echo json_encode($listing); ?>, <?php echo self::$map_js_var ?>);
			<?php
		self::$markers[] = trim(ob_get_clean());
	}

	function utilities () {
		ob_start();
		?>
			<script type="text/javascript">

				function get_map_bounds_for_search(map_js_var) {
					var response = {}
					response.vertices = [];
					response.vertices[0] = {};
					response.vertices[1] = {};
					response.vertices[2] = {};
					response.vertices[3] = {};

					var bounds = map_js_var.map.getBounds();

					response['vertices'][0]['lat'] = bounds.getNorthEast().lat();
					response['vertices'][0]['lng'] = bounds.getNorthEast().lng();

					response['vertices'][1]['lat'] = bounds.getNorthEast().lat();
					response['vertices'][1]['lng'] = bounds.getSouthWest().lng();

					response['vertices'][2]['lat'] = bounds.getSouthWest().lat();
					response['vertices'][2]['lng'] = bounds.getSouthWest().lng();

					response['vertices'][3]['lat'] = bounds.getSouthWest().lat();
					response['vertices'][3]['lng'] = bounds.getNorthEast().lng();
					return response;
				}

				function generate_verticies_inputs () {

				}


				function show_max_results_overlay () {
					jQuery('#full_overlay').show();
					console.log('I would show the max results overlay.')
				}

				function get_search_filters (form_class) {
					var result = {};
					jQuery.each(jQuery('.'+ form_class +', .sort_wrapper').serializeArray(), function(i, field) {
						result[field.name] = field.value;
		            });
		            return result;
				}

				function get_listings () {
					console.log('im getting listings');
				}

				function pls_create_polygon_listeners (polygon, map_js_var, click_type) {
					google.maps.event.addListener(polygon,"mouseover",function(){
						polygon.setOptions({fillOpacity: "0.9"});
					}); 

					google.maps.event.addListener(polygon,"mouseout",function(){
						polygon.setOptions({fillOpacity: "0.4"});
					}); 

					google.maps.event.addListener(polygon,"click",function() {
						pls_show_loading_overlay();
						if (click_type && click_type == 'redirect') {
							window.location.href = this.tax.permalink
							pls_hide_loading_overlay();
						} else {
							var that = this;
							request = {};
							request.action = 'polygon_listings';
							request.vertices = this.tax.vertices;
							map_js_var.selected_polygon = this;
							jQuery.post(info.ajaxurl, request, function(data, textStatus, xhr) {
								if (data) {
									pls_clear_markers(map_js_var);
									for (var i = data.length - 1; i >= 0; i--) {
										pls_create_listing_marker(data[i], map_js_var, true);
									};
									pls_create_polygon(that.tax.vertices,{strokeColor: '#55b429',strokeOpacity: 1.0,strokeWeight: 3, fillOpacity: 0.0}, map_js_var);
									pls_hide_loading_overlay();
								};
							},'json');	
						}
					}); 
				}

				function pls_clear_markers (map_js_var) {
					if (map_js_var && map_js_var.markers) {
						for (var current_marker in map_js_var.markers) {
	                    	map_js_var.markers[current_marker].setMap(null);
	                    }	
	                    map_js_var.markers = [];
					};
				}

				function pls_clear_polygons (map_js_var) {
					if (map_js_var && map_js_var.markers) {
						for (var current_marker in map_js_var.polygons) {
	                    	map_js_var.polygons[current_marker].setMap(null);
	                    }	
	                    map_js_var.polygons = [];
					};
				}

				function pls_create_listing_marker (listing, map_js_var, center) {
					var marker_details = {};
					marker_details.latlng = new google.maps.LatLng(listing['location']['coords'][0], listing['location']['coords'][1]);

					if (listing['images'] && listing['images'][0] && listing['images'][0]['url']) {
				    	var image_url = listing['images'][0]['url'];
				    };
				    marker_details.content = '<div id="content">'+
                        '<div id="siteNotice">'+'</div>'+
                          '<h2 id="firstHeading" class="firstHeading"><a href="'+ listing['cur_data']['url'] + '">' + listing['location']['full_address'] +'</a></h2>'+
                          '<div id="bodyContent">'+
                            '<img width="80px" height="80px" style="float: left" src="'+image_url+'" />' +
                            '<ul style="float: right; width: 130px">' +
                              '<li> Beds: '+ listing['cur_data']['beds'] +'</li>' +
                              '<li> Baths: '+ listing['cur_data']['baths'] +'</li>' +
                              '<li> Price: '+ listing['cur_data']['price'] +'</li>' +
                            '</ul>' +
                          '</div>' +
                          '<div class="viewListing" style="margin: 15px 70px; float: left; font-size: 16px; font-weight: bold;"><a href="'+listing['cur_data']['url']+'">View Details</a></div>' +
                          '<div class="clear"></div>' +
                        '</div>'+
                      '</div>';
                      pls_create_marker(marker_details, map_js_var, center);
				}

				function pls_create_marker (marker_details, map_js_var, center) {
					if (!marker_details.icon) {
						var marker_options = {position: marker_details.latlng};
					} else {
						var marker_options = {position: marker_details.latlng, icon: marker_details.icon };
					};
					var marker = new google.maps.Marker(marker_options);
					var infowindow = new google.maps.InfoWindow({content: marker_details.content});
					map_js_var.infowindows.push(infowindow);
					google.maps.event.addListener(marker, 'click', function() {
						for (i in map_js_var.infowindows) {
							map_js_var.infowindows[i].setMap(null);
						}
						infowindow.open(map_js_var.map,marker);
					});
					marker.setMap(map_js_var.map);
					map_js_var.markers.push(marker);
					if (center) {
						pls_center_map(map_js_var);	
					}
					
				}

				function pls_create_polygon (points, polygon_options, map_js_var) {
					var coords = [];
	        		for (var i = points.length - 1; i >= 0; i--) {
    					coords.push(new google.maps.LatLng( points[i][0], points[i][1]));
	        		};	
	        		if (polygon_options) {
	        			var polyOptions = polygon_options;
	        			polyOptions.paths = coords;
	        		} else {
	        			var polyOptions = {strokeColor: '#000000',strokeOpacity: 1.0,strokeWeight: 3, paths: coords};
	        		}
					var neighborhood = new google.maps.Polygon(polyOptions);
					neighborhood.setMap(map_js_var.map);
					console.log(neighborhood);
					map_js_var.polygons.push(neighborhood);
				}

				function pls_center_map (map_js_var) {
					var bounds = new google.maps.LatLngBounds();
					if (map_js_var.markers.length > 0) {
						for (var i = map_js_var.markers.length - 1; i >= 0; i--) {
							map_js_var.markers[i].setMap(map_js_var.map);
							bounds.extend(map_js_var.markers[i].getPosition());
						};

				        if(typeof map_js_var.map != "undefined") {
				        	map_js_var.map.fitBounds(bounds);
				            google.maps.event.addListenerOnce(map_js_var.map, 'bounds_changed', function(event) {
					            if (this.getZoom() > 15) {
					            	this.setZoom(15);
					            }
				            });
				        }
					};
				}	

				function pls_geocode (address, map_js_var, success_callback, failed_callback, response) {
					var geocoder = new google.maps.Geocoder();
					var bounds = map_js_var.map.getBounds();
				    geocoder.geocode( { 'address': address, bounds: bounds}, function(results, status) {
				      if (status == google.maps.GeocoderStatus.OK) {
				      	success_callback(results[0].geometry.location, response);
				      } else {
				      	failed_callback(status, response);
				      }
				    });
				}

				function pls_show_loading_overlay() {
					jQuery('.map_wrapper #loading_overlay').show();
				}

				function pls_hide_loading_overlay() {
					jQuery('.map_wrapper #loading_overlay').hide();	
				}

				function pls_show_empty_overlay() {
					jQuery('.map_wrapper #empty_overlay').show();
				}

				function pls_hide_empty_overlay() {
					jQuery('.map_wrapper #empty_overlay').hide();	
				}
				
				
			</script>
		<?php
		echo ob_get_clean();
	}

	static function process_defaults ($args) {
		$defaults = array(
        	'lat' => '42.37',
        	'lng' => '-71.03',
        	'center_location' => false,
        	'zoom' => '14',
        	'width' => 300,
        	'height' => 300,
        	'canvas_id' => 'map_canvas',
        	'class' => 'custom_google_map',
        	'map_js_var' => 'pls_google_map',
        	'featured_id' => false,
        	'request_params' => '',
        	'auto_load_listings' => false,
        	'polygon_search' => false,
        	'life_style_search' => false,
        	'show_lifestyle_controls' => false,
        	'show_lifestyle_checkboxes' => false,
        	'loading_overlay' => '<div>Loading...</div>',
        	'empty_overlay' => '<div>No Results</div>',
        	'search_on_load' => false,
        	'polygon_options' => array(),
        	'ajax_form_class' => false,
        	'polygon' => false,
        	'polygon_click_action' => false,
        	'lifestyle_distance' => 'miles',
        	'search_class' => 'pls_listings_search_results',
        	'full_overlay' => '<div>Zoom in to see more results</div>'
        );
        $args = wp_parse_args( $args, $defaults );
        self::$map_js_var = $args['map_js_var'];	
        return $args;
	}

	static function process_marker_defaults ($listing, $args) {
		if (isset($listing) && is_array($listing) && isset($listing['location'])) {
			if (isset($listing['location']['coords']['latitude'])) {
				$coords = $listing['location']['coords'];
				$args['lat'] = $coords['latitude'];
				$args['lng'] = $coords['longitude'];	
			} elseif (is_array($listing['location']['coords'])) {
				$coords = $listing['location']['coords'];
				$args['lat'] = $coords[0];
				$args['lng'] = $coords[1];	
			}
		}
		$defaults = array(
        	'lat' => '42.37',
        	'lng' => '71.03',
        );
        $args = wp_parse_args( $args, $defaults );
        return $args;		
	}

	//for compatibility
	function dynamic($listings = array(), $map_args = array(), $marker_args = array()) {
		return self::listings($listings, $map_args, $marker_args);
	}
	function listings($listings = array(), $map_args = array(), $marker_args = array()) {
		return PLS_Map_Listings::listings($listings, $map_args, $marker_args);
	}
	function neighborhood($listings = array(), $map_args = array(), $marker_args = array(), $polygon) {
		return PLS_Map_Neighborhood::neighborhood($listings, $map_args, $marker_args);	
	}
	function polygon($listings = array(), $map_args = array(), $marker_args = array()) {
		return PLS_Map_Polygon::polygon($listings, $map_args, $marker_args);	
	}
	function lifestyle($listings = array(), $map_args = array(), $marker_args = array()) {
		return PLS_Map_Lifestyle::lifestyle($listings, $map_args, $marker_args);	
	}
	function lifestyle_polygon($listings = array(), $map_args = array(), $marker_args = array()) {
		return PLS_Map_Lifestyle_Polygon::lifestyle_polygon($listings, $map_args, $marker_args);		
	}
}