<?php 


PL_Listings::init();

class PL_Listings {
	
	static function init() {
		
		add_action( 'wp_ajax_admin_datatable', array( __CLASS__, 'generate_datatable'  ));
		add_action( 'wp_ajax_generate_search', array( __CLASS__, 'generate_search'  ));

	}

	static function generate_search(){
		include(trailingslashit(PLS_PLUGIN_DIR) . 'properties_divbased.php');
		die();
	}

	static function generate_datatable () {

		//
		// Get data
		//
		$fields = explode( ',', $_REQUEST['fields'] );
		$filter_request = placester_filter_parameters_from_http();
		if ( ! isset( $_REQUEST['no_admin_filter'] ) )
		    placester_add_admin_filters( $filter_request );
		else {
		    if ( ! current_user_can( 'edit_themes' ) )
		        die( 'permission denied' );
		}
		$filter_request['address_mode'] = placester_get_property_address_mode();

		// Paging parameters
		if ( isset( $_GET['iDisplayStart'] ) )
		    $filter_request['offset'] = $_GET['iDisplayStart'];
		if ( isset( $_GET['iDisplayLength'] ) )
		    $filter_request['limit'] = $_GET['iDisplayLength'];

		// Ordering parameters
		if ( isset( $_GET['iSortCol_0'] ) ) {
		    $filter_request['sort_by'] = $fields[intval( $_GET['iSortCol_0'] )];
		    $filter_request['sort_type'] = strtolower( $_GET['sSortDir_0'] );
		}

		// Filtering
		/*
		if ( $_GET['sSearch'] != "" )
		{ 
		   // todo once implemented by API 
		}*/

		// Request
		try {
		    $response = placester_property_list($filter_request);
		    // print_r($response);
		    // print_r($filter_request);
		    $response_properties = array();
		    $response_total = 0;
		    if ($response) {
		        $response_properties = $response->properties;
		        $response_total = $response->total;    
		    }
		    
		}
		catch (Exception $e) {
		    $response_properties = array();
		    $response_total = 0;
		}

		//
		// Process
		//
		self::init_templates();

		$rows = array();
		foreach ( $response_properties as $i )
		    array_push( $rows, self::convert_row( $i, $fields, true ) );

		echo json_encode(
		    array(
		        'sEcho' => @intval( $_GET['sEcho'] ),
		        'iTotalRecords' => $response_total,
		        'iTotalDisplayRecords' => $response_total,
		        'aaData' => $rows
		    ) );
		  
		 die();
		
	}

	static function init_templates() {
	    global $list_details_template;
	    global $map_details_template;
	    global $placester_post_slug;
	    global $new_ids;
	    global $featured_ids;

	    $list_details_template = get_option( 'placester_list_details_template' );
	    $map_details_template = get_option( 'placester_map_info_template' );
	    $placester_post_slug = placester_post_slug();
	    $new_ids = get_option( 'placester_properties_new_ids' );
	    if ( ! is_array( $new_ids ) )
	        $new_ids = array();
	        
	    $featured_ids = get_option( 'placester_properties_featured_ids' );
	    if ( ! is_array( $featured_ids ) )
	        $featured_ids = array();
	}

	static function convert_row( $row, $fields, $is_simple_array ) {
	    global $list_details_template;
	    global $map_details_template;
	    global $new_ids;
	    global $featured_ids;

	    $item = array();
	    foreach ( $fields as $field ) {
	        $v = 'n/a';
	        if ( $field == 'available_on' ||
	                $field == 'bathrooms' ||
	                $field == 'bedrooms' ||
	                $field == 'amenities' ||
	                $field == 'description' ||
	                $field == 'half_baths' ||
	                $field == 'id' ||
	                $field == 'price' ) {
	            $v = ! empty( $row->$field ) ? $row->$field : '';
	        } else if ( $field == 'contact.email' ||
	                $field == 'contact.phone' ) {
	            $subfield = substr( $field, 8 );
	            $v = $row->contact->$subfield;
	        } else if ( $field == 'location.address' ||
	                $field == 'location.city' ||
	                $field == 'location.state' ||
	                $field == 'location.unit' ||
	                $field == 'location.zip' ) {
	            $subfield = substr( $field, 9 );
	            $v = $row->location->$subfield;
	        } else if ( $field == 'location.coords.latitude' ||
	                $field == 'location.coords.longitude' ) {
	            $subfield = substr( $field, 16 );
	            $v = $row->location->coords->$subfield;
	        } else if ( $field == 'has_images' ) {
	            $v = ( count( $row->images ) > 0 ? 'true' : 'false' );
	        } else if ( $field == 'url' ) {
	            $v = placester_get_property_url( $row->id );
	        } else if ( $field == 'images' ) {
	            $v = array();
	            $images = $row->images;
	            if ( count($images) > 0 )
	                foreach ( $images as $image_item )
	                    array_push( $v, $image_item->url );
	        } else if ( $field == 'list_details' )
	            $v = placester_expand_template( $list_details_template, $row );
	        else if ( $field == 'map_details' )
	            $v = placester_expand_template( $map_details_template, $row );
	        else if ( $field == 'is_new' )
	            $v = in_array( $row->id, $new_ids );
	        else if ( $field == 'is_featured' )
	            $v = in_array( $row->id, $featured_ids );
	        else if ( $field == 'featured_image' ) {
	            $images = $row->images;
	            if( count($images) > 0 )
	                $v = $images[0]->url;
	        }

	        if ( $is_simple_array )
	            $item[] = $v;
	        else {
	            $field_parts = explode( '.', $field );
	            $item_for_value = &$item;
	            for ( $n = 0; $n < count( $field_parts ) - 1; $n++ ) {
	                $field_part = $field_parts[$n];
	                if ( ! isset( $item_for_value[$field_part] ) )
	                    $item_for_value[$field_part] = array();
	                $item_for_value = &$item_for_value[$field_part];
	            }

	            $field_part = $field_parts[count( $field_parts ) - 1];
	            $item_for_value[$field_part] = $v;
	        }
	    }

	    return $item;
	}

// end of class
}