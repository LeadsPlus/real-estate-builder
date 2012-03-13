<?php 

PL_Pages::init();
class PL_Pages {

	static $property_post_type = 'property';

	function init() {
		add_action('init', array(__CLASS__, 'create_taxonomies'));
	}


	//return many page urls
	function get () {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * ' . 'FROM ' . $wpdb->prefix . 'posts ' . "WHERE post_type = '" . self::$property_post_type . "'");
	    $rows = $wpdb->get_results($sql);
		return $rows;
	}

	//return a page url
	function details ($placester_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT ID, post_modified ' . 'FROM ' . $wpdb->prefix . 'posts ' . "WHERE post_type = '" . self::$property_post_type . "' AND post_name = %s " .'LIMIT 0, 1', $placester_id);
	    $row = $wpdb->get_row($sql);
	    $post_id = 0;
	    if ($row) {
	        $post_id = $row->ID;
	    }
    	return $post_id;
	}

	//create listing page
	function manage_listing ($api_listing) {
		$page_details = array();
		$page_details['post_id'] = self::details($api_listing['id']);
		$page_details['type'] = self::$property_post_type;
		$page_details['title'] = $api_listing['location']['address'];
		$page_details['name'] = $api_listing['id'];
		$page_details['content'] = serialize($api_listing);
		$page_details['taxonomies'] = array('zip_code' => $api_listing['location']['postal']);
		return self::manage($page_details);
	}

	function create_once ($pages_to_create, $force_template) {
		foreach ($pages_to_create as $page_info) {
			$page = get_page_by_title($page_info['title']);
			if (!isset($page->ID)) {
				$page_details = array();
				$page_details['title'] = $page_info['title'];
				if (isset($page_info['template'])) {
					$page_details['post_meta'] = array('_wp_page_template' => $page_info['template']);
				}
				self::manage($page_details);
			} else {
				if (isset($page_info['template'])) {
					delete_post_meta( $page->ID, '_wp_page_template' );
    				add_post_meta( $page->ID, '_wp_page_template', $page_info['template']);
				}
			}
		}
	}

	//create page
	function manage ($args = array()) {
		$defaults = array('post_id' => false, 'type' => 'page', 'title' => '', 'name' => false, 'content' => ' ', 'status' => 'publish', 'post_meta' => array(), 'taxonomies' => array());
		extract(wp_parse_args($args, $defaults));
		$post = array(
                 'post_type'   => $type,
                 'post_title'  => $title,
                 'post_name'   => $name,
                 'post_status' => $status,
                 'post_author' => 1,
                 'post_content'=> $content,
                 'filter'      => 'db'
             );

            if ($post_id <= 0) {
            	$post_id = wp_insert_post($post);
            	if (!empty($post_meta)) {
            		foreach ($post_meta as $key => $value) {
            			add_post_meta($post_id, $key, $value, TRUE);
            		}
            	}
            	if (!empty($taxonomies)) {
	            	foreach ($taxonomies as $taxonomy => $term) {
	            		wp_set_object_terms($post_id, $term, $taxonomy);
	            	}
            	}
            } else {	
                $post['ID'] = $post_id;
                $post_id = wp_update_post($post);
            }
        return $post_id;
	}

	function delete_all() {
		global $wpdb;
    	$posts_table = $wpdb->prefix . 'posts';
    	$results = $wpdb->get_results( "DELETE FROM $posts_table WHERE post_type = '" . self::$property_post_type . "'");
    	if (empty($results)) {
    		return true;
    	}
    	return false;
	}

	function create_taxonomies() {
			// register_taxonomy('zip_code', self::$property_post_type, array('hierarchical' => TRUE,'label' => __('Zip Codes'), 'public' => TRUE,'show_ui' => TRUE,'query_var' => true,'rewrite' => array('slug' => 'properties/zip', 'with_front' => false) ) );
			register_post_type(self::$property_post_type, array('labels' => array('name' => __( 'Properties' ),'singular_name' => __( 'property' )),'public' => true,'has_archive' => true, 'rewrite' => array('slug' => 'properties', 'with_front' => false)));
			// register_post_type('client', array('labels' => array('name' => __( 'client' ),'singular_name' => __( 'client' )),'public' => true,'has_archive' => true, 'rewrite' => array('slug' => 'client', 'with_front' => false)));
			// register_post_type('search', array('labels' => array('name' => __( 'search' ),'singular_name' => __( 'search' )),'public' => true,'has_archive' => true, 'rewrite' => array('slug' => 'search', 'with_front' => false)));
	}
}