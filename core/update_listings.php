<?php

/**
 * Checks for indexable pages for listings and creates if missing
 */

add_action('pre_get_posts', 'placester_intercept_query');

/**
 * Ensures that any homepage is set to only search for properties
 * unless specifically querying another post_type.
 *
 * @param object $query
 */

function placester_intercept_query($query) {
	$count = wp_count_posts('property');
	$api_key = placester_get_api_key();
	if ($count->publish > 0 || strlen($api_key) > 0 ) {
		$vars = $query->query_vars;
		if( $query->is_home && !isset( $vars['post_type'] ) )
			$query->set( 'post_type', 'property' );
		placester_update_listings();
	}
}

/**
 * Compares all property listings with local database and creates needed listing pages
 *
 */
function placester_update_listings() {
	global $wpdb;
    
    $sql = $wpdb->get_results(
        'SELECT post_name ' .
        'FROM ' . $wpdb->prefix . 'posts ' .
        "WHERE post_type = 'property' ");
	$sqls = array_map('placester_compare_id', $sql);
	
    
    try {
        $all_listings = placester_get_properties();
    }
    catch (PlaceSterNoApiKeyException $e) {
        
    }
    if($all_listings) {
    foreach ($all_listings->properties as $listing) {
    	$listings[] = $listing->id;
    	$data[] = $listing;
    }

    $result = array_diff($listings, $sqls);

	if(!empty($result)) {
		$keys = array_keys($result);
    foreach ($keys as $key) {

    	$post = placester_prep_properties($data[$key]);
    	
		$post_id = wp_insert_post($post['post']);
		placester_set_post_terms($post_id, $post['terms'], $data[$key]);
		
		
    }
    
	}
}

}

/**
 * Used for array_map on local database post_name's
 *
 * @return object $post_name
 */
function placester_compare_id($sql) {
	return $sql->post_name;
}

/**
 * Used for array_map on term_id's for updating posts with specific terms
 *
 * @return object $term['term_id']
 */
function placester_get_term_id($term) {
	return $term['term_id'];
}

function placester_prep_properties($data) {
	$image_info = "";
	if($data->images) {
		$image_info = "\n\n";
		foreach($data->images as $image) {
		    if (!empty($caption)) {
    			$image_caption = ($image->caption) ?  $image->caption : '' ;
		    }
			$image_info .= '<a class="placester_fancybox" href="'. $image->url . '" title="' . $image_caption . '"><img height="150" width="150" src="' . $image->url . '" alt="" /></a>';
		}
	}


	$bedrooms = ($data->bedrooms >= 10) ? 10 : $data->bedrooms;

	for ($i=1; $i <= $bedrooms; $i++) { 
		$bedrooms_num[] = "$i";
	}

	foreach ($bedrooms_num as $value) {
		$bedroom_terms[] = term_exists($value, 'bedrooms');
	}
	$bedroom_terms = array_map('placester_get_term_id', $bedroom_terms);

	$bathrooms = ($data->bathrooms >= 10) ? 10 : $data->bathrooms;

	for($i=1; $i <= $bathrooms; $i++) {
		$bathrooms_num[] = "$i";
	}

	foreach ($bedrooms_num as $value) {
		$bathroom_terms[] = term_exists($value, 'baths');
	}
	$bathroom_terms = array_map('placester_get_term_id', $bathroom_terms);

	$price = $data->price;
	if( $price <= 500 ) {
		$price = array('all', '0-500');
	} elseif ($price <= 1000) {
		$price = array('all', '501-1000');
	} elseif ($price <= 1500) {
		$price = array('all', '1001-1500');
	} elseif ($price <= 2000) {
		$price = array('all', '1501-2000');
	} elseif ($price <= 2500) {
		$price = array('all', '2001-2500');
	} else {
		$price = array('all', '2500');
	}

	foreach ($price as $value) {
		$price_terms[] = term_exists($value, 'rent');
	}
	$price_terms = array_map('placester_get_term_id', $price_terms);

	$half_baths = ($data->half_baths == 1) ? '.5' : '';
	$amenities = !empty($data->amenities) ? implode(', ', $data->amenities) : '';
	$post_content = $data->description .
					"\n\nPrice: &#36;" . $data->price .
					"\nAddress: " . $data->location->full_address .
					"\nBedrooms: " . $data->bedrooms .
					"\nBaths: " . $data->bathrooms . $half_baths .
					"\nAvailable on: " . $data->available_on .
					"\nAmenities: " . $amenities .
					"\n\nPhone: " . $data->contact->phone .
					"\n<a href='mailto:" . $data->contact->email . "?subject=Listing " . $data->id . 
					"'>" . $data->contact->email . "</a>" .
					$image_info;

	$post = array(
		'post_type'   => 'property',
		'post_title'  => $data->location->full_address,
		'post_name'   => $data->id,
		'post_status' => 'publish',
		'post_author' => 1,
		'post_content'=> $post_content,
		'filter'      => 'db',
	);

	return array( 'post' => $post,
				  'terms' => array( 'bedroom_terms' => $bedroom_terms,
				  					'bathroom_terms' => $bathroom_terms,
				  					'price_terms' => $price_terms
				  )
			) ; 
}

function placester_set_post_terms($post_id, $terms, $data) {
	wp_set_post_terms( $post_id, $terms['bedroom_terms'], 'bedrooms');
	wp_set_post_terms( $post_id, $terms['bathroom_terms'], 'baths');
	wp_set_post_terms( $post_id, $terms['price_terms'], 'rent');
	wp_set_post_terms( $post_id, array( $data->location->city, 'all' ), 'city');
	wp_set_post_terms( $post_id, array( $data->location->state, 'all' ), 'state');
	wp_set_post_terms( $post_id, array( $data->location->zip, 'all' ), 'zip');
}
