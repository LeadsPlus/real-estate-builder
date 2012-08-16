<?php 

PLS_Taxonomy::init();
class PLS_Taxonomy {

	static $custom_meta = array();

	function init () {
		add_action('init', array(__CLASS__, 'metadata_customizations')); 
	}

	function get ($args = array()) {

		$cache = new PLS_Cache('nbh');
		if ($result = $cache->get($args)) {
			return $result;
		}

		extract(self::process_args($args), EXTR_SKIP);
		$subject = array();
		if ($street) {
			$subject += array('taxonomy' => 'street', 'term' => $street, 'api_field' => 'address');
		} elseif ($neighborhood) {
			$subject += array('taxonomy' => 'neighborhood', 'term' => $neighborhood, 'api_field' => 'neighborhood');
		} elseif ($zip) {
			$subject += array('taxonomy' => 'zip', 'term' => $zip, 'api_field' => 'postal');
		} elseif ($city) {
			$subject += array('taxonomy' => 'city', 'term' => $city, 'api_field' => 'locality');
		} elseif ($state) {
			$subject += array('taxonomy' => 'state', 'term' => $state, 'api_field' => 'region');
		}
		$term = get_term_by('slug', $subject['term'], $subject['taxonomy'], ARRAY_A );
		$custom_data = array();
		foreach (self::$custom_meta as $meta) {
			$custom_data[$meta['id']] = get_tax_meta($term['term_id'],$meta['id']);
		}
		$term = wp_parse_args($term, $custom_data);
		$term['api_field'] = $subject['api_field'];

		//if there's a polygon, use that to get listings. Otherwise, use the name of the neighborhood
		$polygon = PLS_Plugin_API::get_taxonomies_by_slug($subject['term']);
		if (is_array($polygon) && !empty($polygon[0])) {
			$polygon[0]['neighborhood_polygons'] = $polygon[0]['name'];
			$listings_raw = PLS_Plugin_API::get_polygon_listings( $polygon[0] );
			$term['listings'] = pls_get_listings( "limit=5&context=home&neighborhood_polygons=" . $polygon[0]['name'] );
		} else {
			$listings_raw = PLS_Plugin_API::get_property_list("location[" . $term['api_field'] . "]=" . $term['name']);  	
			$term['listings'] = pls_get_listings( "limit=5&context=home&request_params=location[" . $term['api_field'] . "]=" . $term['name'] );
		}

		$term['areas'] = array('locality' => array(), 'postal' => array(), 'neighborhood' => array(), 'address' => array());
		$locality_tree = array('city' => array('postal', 'neighborhood', 'address'), 'zip' => array('neighborhood', 'address'), 'neighborhood' => array('address'), 'street' => array());

		$term['listings_raw'] = $listings_raw['listings'];

		//assemble all the photos
		$api_translations = array('locality' => 'city', 'neighborhood' => 'neighborhood', 'postal' => 'zip', 'address' => 'street');
		$term['listing_photos'] = array();
		$count = 0;
		if (isset($listings_raw['listings'])) {
			foreach ($listings_raw['listings'] as $key => $listing) {
				if (!empty($listing['images'])) {
					foreach ($listing['images'] as $image) {
						if ($count > $image_limit) {
							break;
						}
						$term['listing_photos'][] = array('full_address' => $listing['location']['full_address'], 'image_url' => $image['url'], 'listing_url' => $listing['cur_data']['url']);
						$count++;
					}
				}
				if (isset($locality_tree[$subject['taxonomy']])) {
					foreach ($locality_tree[$subject['taxonomy']] as $locality) {
						$link = array('name' => $listing['location'][$locality], 'permalink' => get_term_link($listing['location'][$locality], $api_translations[$locality] ));
						if (is_string($link['permalink'])) {
							$term['areas'][$locality][] = $link;
						}
					}
				}
			}
		}
		$term['polygon'] = PLS_Plugin_API::get_polygon_detail(array('tax' => $term['api_field'], 'slug' => $subject['term']));
		$cache->save($term);
		return $term;
	}

	function get_links ($location) {
		$response = array();
		$neighborhoods = array('state' => false, 'city' => false, 'neighborhood' => false, 'zip' => false, 'street' => false);
		$api_translations = array('state' => 'region', 'city' => 'locality', 'neighborhood' => 'neighborhood', 'zip' => 'postal', 'street' => 'address');
		global $query_string;
		$args = wp_parse_args($query_string, $neighborhoods);
		foreach ($neighborhoods as $neighborhood => $value) {
			if (isset($args[$neighborhood]) && isset($location[$api_translations[$neighborhood]])) {
				$term_link = get_term_link( $args[$neighborhood], $neighborhood );
				if (!is_object($term_link)) {
					$response[ $location[$api_translations[$neighborhood]] ] = $term_link;	
				} else {
					$response[ $location[$api_translations[$neighborhood]] ] = '';	
				}
			}
		}
		return $response;
	}

	function add_meta ($type, $id, $label) {
		if (in_array($type, array('text', 'textarea', 'checkbox', 'image', 'file', 'wysiwyg'))) {
			self::$custom_meta[] = array('type' => $type, 'id' => $id, 'label' => $label);
		} else {
			return false;
		}
		
	}

	function metadata_customizations () {
        include_once(PLS_Route::locate_blueprint_option('meta.php'));        
		
		//throws random errors if you aren't an admin, can't be loaded with admin_init...
        if (!is_admin() || !class_exists('Tax_Meta_Class')) {
        	return;	
        }
        
		$config = array('id' => 'demo_meta_box', 'title' => 'Demo Meta Box', 'pages' => array('state', 'city', 'zip', 'street', 'neighborhood'), 'context' => 'normal', 'fields' => array(), 'local_images' => false, 'use_with_theme' => false );
		$my_meta = new Tax_Meta_Class($config);
		foreach (self::$custom_meta as $meta) {
			switch ($meta['type']) {
				case 'text':
					$my_meta->addText($meta['id'],array('name'=> $meta['label']));
					break;
				case 'textarea':
					$my_meta->addTextarea($meta['id'],array('name'=> $meta['label']));
					break;
				case 'wysiwyg':
					$my_meta->addCheckbox($meta['id'],array('name'=> $meta['label']));
					break;
				case 'image':
					$my_meta->addImage($meta['id'],array('name'=> $meta['label']));
					break;
				case 'file':
					$my_meta->addFile($meta['id'],array('name'=> $meta['label']));
					break;				
				case 'checkbox':
					$my_meta->addCheckbox($meta['id'],array('name'=> $meta['label']));
					break;				
			}
		}
		$my_meta->Finish();
	}

	function process_args ($args) {
		$defaults = array(
        	
        );
        $args = wp_parse_args( $args, $defaults );
        return $args;
	}

//end of class
}