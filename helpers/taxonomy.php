<?php 

PL_Taxonomy_Helper::init();
class PL_Taxonomy_Helper {

	static $location_taxonomies = array('state' => 'State', 'zip' => 'Zip', 'city' => 'City', 'neighborhood' => 'Neighborhood');

	function init () {
		add_action('init', array(__CLASS__, 'register_taxonomies'));
		add_filter('post_type_link', array(__CLASS__, 'get_property_permalink'), 10, 3);
		add_action('wp_ajax_save_polygon', array(__CLASS__, 'save_polygon'));
		add_action('wp_ajax_update_polygon', array(__CLASS__, 'update_polygon'));
		add_action('wp_ajax_delete_polygon', array(__CLASS__, 'delete_polygon'));
		add_action('wp_ajax_get_polygons_datatable', array(__CLASS__, 'get_polygons_datatable'));
		add_action('wp_ajax_get_polygon', array(__CLASS__, 'get_polygon'));
		add_action('wp_ajax_get_polygons_by_type', array(__CLASS__, 'ajax_get_polygons_by_type'));
		add_action('wp_ajax_nopriv_lifestyle_polygon', array(__CLASS__, 'lifestyle_polygon'));
		add_action('wp_ajax_lifestyle_polygon', array(__CLASS__, 'lifestyle_polygon'));
		add_action('wp_ajax_polygon_listings', array(__CLASS__, 'polygon_lisitngs'));
		add_action('wp_ajax_nopriv_polygon_listings', array(__CLASS__, 'polygon_lisitngs'));
	}

	function register_taxonomies () {
		register_taxonomy('state', array('property', 'post', 'page', 'mediapage', 'attachment'), array('hierarchical' => TRUE,'label' => __('States'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => array('with_front' => false, 'hierarchical' => false) ) );
		register_taxonomy('zip', array('property', 'post', 'page', 'mediapage', 'attachment'), array('hierarchical' => TRUE,'label' => __('Zip Codes'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => array('with_front' => false, 'hierarchical' => false) ) );
		register_taxonomy('city', array('property', 'post', 'page', 'mediapage', 'attachment'), array('hierarchical' => TRUE,'label' => __('Cities'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => array('with_front' => false, 'hierarchical' => false) ) );
		register_taxonomy('neighborhood', array('property', 'post', 'page', 'mediapage', 'attachment'), array('hierarchical' => TRUE,'label' => __('Neighborhoods'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => array('with_front' => false, 'hierarchical' => false) ) );
		register_taxonomy('street', array('property', 'post', 'page', 'mediapage', 'attachment'), array('hierarchical' => TRUE,'label' => __('Streets'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => array('with_front' => false, 'hierarchical' => false) ) );
		
		register_taxonomy('beds', 'property', array('hierarchical' => TRUE,'label' => __('Beds'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => true ) );
		register_taxonomy('baths', 'property', array('hierarchical' => TRUE,'label' => __('Baths'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => true ) );
		register_taxonomy('half-baths', 'property', array('hierarchical' => TRUE,'label' => __('Half-baths'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => true ) );
		register_taxonomy('mlsid', 'property', array('hierarchical' => TRUE,'label' => __('MLS ID'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => true ) );
	}

	function polygon_lisitngs () {
		if (isset($_POST['vertices'])) {
			$vertices = $_POST['vertices'];
			if (!empty($vertices)) {
				$request = '';
				foreach ($vertices as $key => $point) {
					$request .= 'polygon['.$key. '][0]=' . $point['lat'] .'&';
					$request .= 'polygon['.$key .'][1]=' . $point['lng'] .'&';
				}
				$api_listings = PL_Listing_Helper::results($request);
				$response = $api_listings['listings'];
				echo json_encode($response);
			}
		}
		die();
	}

	function lifestyle_polygon () {
		$request = wp_parse_args(wp_kses_post($_POST), array('location' => '', 'radius' => '', 'types' => ''));
		$places_response = PL_Google_Places_Helper::search($request);
		$points = array();
		foreach ($places_response as $place) {
			$points[] = array($place['geometry']['location']['lat'], $place['geometry']['location']['lng']);
		}
		if (!empty($points)) {
			$hull_response = self::find_hull($points, array('include_listings' => true));
		} else {
			$hull_response = array();
		}
		$response = array_merge($hull_response, array('places' => $places_response));
		echo json_encode($response);
		die();
	}

	function find_hull ($points = array(), $settings = array()) {
		extract(wp_parse_args($settings, array('include_listings' => false)));
		$response = array();
		if (!empty($points)) {
			$hull = new ConvexHull( $points );
			$response['polygon'] = $hull->getHullPoints();
		} else {
			$response['polygon'] = array();
		}
		if ($include_listings) {
			if (!empty($response['polygon'])) {
				$request = '';
				foreach ($response['polygon'] as $key => $point) {
					$request .= 'polygon['.$key. '][0]=' . $point[0] .'&';
					$request .= 'polygon['.$key .'][1]=' . $point[1] .'&';
				}
			}
			$api_listings = PL_Listing_Helper::results($request);
			$response['listings'] = $api_listings['listings'];
		}
		return $response;
	}

	function update_polygon () {
		PL_Option_Helper::set_polygons(array(), (int)$_POST['id']);
		self::save_polygon();
	}

	function save_polygon () {
		$polygon = array();
		$polygon['name'] = $_POST['name'];
		$polygon['tax'] = $_POST['tax'];
		$polygon['slug'] = $_POST['slug'];
		$polygon['settings'] = $_POST['settings'];
		$polygon['vertices'] = $_POST['vertices'];
		$response = PL_Option_Helper::set_polygons($polygon);
		if ($response) {
			echo json_encode(array('response' => true, 'message' => 'Polygon successfully saved. Updating list...'));	
		} else {
			echo json_encode(array('response' => false, 'message' => 'There was an error. Please try again.'));	
		}
		die();	
	}

	function delete_polygon () {
		$response = PL_Option_Helper::set_polygons(array(), (int)$_POST['id']);
		echo json_encode($response);
		die();		
	}

	function get_polygon () {
		$polygons = PL_Option_Helper::get_polygons();
		if ($polygons[$_POST['id']]) {
			$polygons[$_POST['id']]['id'] = $_POST['id'];
			echo json_encode(array('result' => true, 'polygon' => $polygons[$_POST['id']]));
		} else {
			echo json_encode(array('result' => false, 'message' => 'There was an error. Please try again.'));
		}
		die();
	}

	function ajax_polygons_as_items () {
		echo json_encode(self::polygons_as_items());
		die();
	}

	function get_polygons_datatable () {
		$raw_polygons = PL_Option_Helper::get_polygons();
		// pls_dump($raw_polygons);
		$polygons = array();
		$start = 0;
		foreach ($raw_polygons as $key => $polygon) {
			$polygons[$start][] = $polygon['name'];
			$polygons[$start][] = $polygon['tax'];
			$polygons[$start][] = $polygon['slug'];
			$polygons[$start][] = '<a id="edit_item" class="' . $key . '" href="#">(Edit)</a><input type="hidden" name="id" value="' . $key . '" id="id">';
			$polygons[$start][] = '<a id="remove_item" class="' . $key . '" href="#">(Remove)</a><input type="hidden" name="id" value="' . $key . '" id="id">';
			$start++;
		}

		// Required for datatables.js to function properly.
		// $response['sEcho'] = $_POST['sEcho'];
		$response['aaData'] = $polygons;
		$response['iTotalRecords'] = count($polygons);
		$response['iTotalDisplayRecords'] = count($polygons);
		echo json_encode($response);
		die();
	}

	function get_polygons_by_type ($type = false) {
		if (!$type) {
			$type = $_POST['type'];
		}
		$response = array();
		$polygons = PL_Option_Helper::get_polygons();
		foreach ($polygons as $key => $polygon) {
			$polygon['permalink'] = get_term_link( $polygon['slug'], $polygon['tax'] );
			if ($polygon['tax'] == $type) {
				$response[] = $polygon;
			}
		}
		return $response;
	}

	function get_polygons_by_slug ($slug = false) {
		
		$response = array();
		$polygons = PL_Option_Helper::get_polygons();
		foreach ($polygons as $key => $polygon) {
			$polygon['permalink'] = get_term_link( $polygon['slug'], $polygon['tax'] );
			if ($polygon['slug'] == $slug) {
				$response[] = $polygon;
			}
		}
		return $response;
	}

	function get_polygon_detail ($args = array()) {
		extract(wp_parse_args($args, array('tax' => false, 'slug' => false)));
		$response = array();
		$polygons = PL_Option_Helper::get_polygons();
		if ($slug && $tax) {
			foreach ($polygons as $key => $polygon) {
				if ($polygon['slug'] == $slug && $polygon['tax'] == $tax) {
					$polygon['permalink'] = get_term_link( $polygon['slug'], $polygon['tax'] );
					return $polygon;
				}
			}
		}
		return array();
	}

	function ajax_get_polygons_by_type ($type = false) {
		echo json_encode(self::get_polygons_by_type($type));
		die();
	}

	function taxonomies_as_selects () {
		$taxonomies = self::get_taxonomies();
		ob_start();
		?>
		<select id="poly_taxonomies" name="poly_taxonomies" >
			<?php foreach ($taxonomies as $slug => $label): ?>
				<option value="<?php echo $slug ?>"><?php echo $label ?></option>
			<?php endforeach ?>
		</select>

		<?php foreach ($taxonomies as $slug => $label): ?>
			<select class="poly_taxonmy_values" name="<?php echo $slug ?>" style="display: none;" id="<?php echo $slug ?>">
					<option value="false"> --- </option>
				<?php foreach (self::get_taxonomy_items($slug) as $item): ?>
					<option value="<?php echo $item['slug'] ?>"><?php echo $item['name'] ?></option>
				<?php endforeach ?>
			</select>
		<?php endforeach ?>
		<?php
		return ob_get_clean();
	}

	function taxonomies_as_checkboxes () {
		$taxonomies = self::get_taxonomies();
		ob_start();
		?>
			<form>
				<?php foreach ($taxonomies as $slug => $label): ?>
					<section>
						<input type="radio" id="<?php echo $slug ?>" name="type" value="<?php echo $slug ?>">
						<label for="<?php echo $slug ?>"><?php echo $label ?></label>
					</section>
				<?php endforeach ?>	
			</form>		
		<?php
		return ob_get_clean();
	}

	function get_taxonomies () {
		return self::$location_taxonomies;
	}

	function get_taxonomy_items ($tax, $args = array()) {
		$terms = get_terms( $tax, $args );
		$response = array();
		foreach ($terms as $key => $term) {
			foreach ($term as $term_key => $term_value) {
				$response[$key][$term_key] = $term_value;
			}
			$response[$key]['permalink'] = get_term_link($response[$key]['slug'], $tax);	
		}
		return $response;
		// pls_dump($response);
	}

	function get_property_permalink ($permalink, $post_id, $leavename) {
		$post = get_post($post_id);
		$state = '';
		$zip = '';
		$city = '';
		$street = '';
        $rewritecode = array('%state%','%city%','%zip%','%neighborhood%','%street%', $leavename ? '' : '%postname%', $leavename ? '' : '%pagename%', $leavename ? '' : '%pagename%');
        
        if ( !empty($permalink) && $post->post_type == 'property' && !in_array($post->post_status, array('draft', 'pending', 'auto-draft')) ) {
        
            if (strpos($permalink, '%state%')) {
            	$terms = wp_get_object_terms($post->ID, 'state');  
            	if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
            		$state = $terms[0]->slug;	
            	} else {
            		$state = 'unassigned-state';
            	}
            }

            if (strpos($permalink, '%zip%')) {
            	$terms = wp_get_object_terms($post->ID, 'zip');  
            	if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
            		$zip = $terms[0]->slug;	
            	} else {
            		$zip = 'unassigned-zip';
            	}
            }

	        if (strpos($permalink, '%city%')){
	            $terms = wp_get_object_terms($post->ID, 'city');  
	            if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
	            	$city = $terms[0]->slug;	
	            } else {
	            	$city = 'unassigned-city';
	            } 
	        } 

	        if (strpos($permalink, '%neighborhood%')){
	            $terms = wp_get_object_terms($post->ID, 'neighborhood');  
	            if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
	            	$neighborhood = $terms[0]->slug;	
	            } else {
	            	$neighborhood = 'unassigned-neighborhood';
	            } 
	        } 

	        if (strpos($permalink, '%street%')){
	            $terms = wp_get_object_terms($post->ID, 'street');  
	            if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
	            	$street = $terms[0]->slug;	
	            } else {
	            	$street = 'unassigned-street';
	            } 
	        }           

	        $rewritereplace = array( $state, $city, $zip, $neighborhood, $street, $post->post_name, $post->post_name, $post->post_name, $post->post_name, $post->post_name);
	        $permalink = str_replace($rewritecode, $rewritereplace, $permalink);
        }
        return $permalink;
	}
}