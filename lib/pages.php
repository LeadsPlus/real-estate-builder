<?php 

PL_Pages::init();
class PL_Pages {

	static $property_post_type = 'property';

	function init() {
		add_action('init', array(__CLASS__, 'create_taxonomies'));
		add_action('wp_footer', array(__CLASS__,'force_rewrite_update'));
		add_action('admin_footer', array(__CLASS__,'force_rewrite_update'));
		add_action( '404_template', array( __CLASS__, 'dump_permalinks'  ));
	}

	//return many page urls
	function get () {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * ' . 'FROM ' . $wpdb->prefix . 'posts ' . "WHERE post_type = '" . self::$property_post_type . "'");
	    $rows = $wpdb->get_results($sql, ARRAY_A);
		return $rows;
	}

	//return a page url
	function details ($placester_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT ID, post_modified ' . 'FROM ' . $wpdb->prefix . 'posts ' . "WHERE post_type = '" . self::$property_post_type . "' AND post_name = %s " .'LIMIT 0, 1', $placester_id);
	    $row = $wpdb->get_row($sql, OBJECT, 0);
	    if (isset($row->ID)) {
	        $post_id = $row->ID;
	        $cache[$placester_id] = $post_id;
	        return $post_id;
	    }    	
	}

	//create listing page
	function manage_listing ($api_listing) {
		$page_details = array();
		$page_details['post_id'] = self::details($api_listing['id']);
		$page_details['type'] = self::$property_post_type;
		$page_details['title'] = $api_listing['location']['address'];
		$page_details['name'] = $api_listing['id'];
		$page_details['content'] = '';
		$page_details['taxonomies'] = array(
										'zip' => $api_listing['location']['postal'], 
										'city' => $api_listing['location']['locality'],
										'state' => $api_listing['location']['region'],
										'neighborhood' => $api_listing['location']['neighborhood'],
										'street' => $api_listing['location']['address'],
										'beds' => (string) $api_listing['cur_data']['beds'],
										'baths' => (string) $api_listing['cur_data']['beds'],
										'half-baths' => (string) $api_listing['cur_data']['half_baths'],
										'mlsid' => (string) $api_listing['rets']['mls_id']
									);
		$page_details['post_meta'] = array('listing_data' => serialize($api_listing));
		// pls_dump($page_details['taxonomies']);
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

	function delete_by_name ($name) {
		global $wpdb;
    	$posts_table = $wpdb->prefix . 'posts';
    	$results = $wpdb->get_results( "DELETE FROM $posts_table WHERE post_name = '".$name."' AND post_type = '" . self::$property_post_type . "'");
    	if (empty($results)) {
    		return true;
    	}
    	return false;	
	}

	function create_taxonomies() {
		register_post_type(self::$property_post_type, array('labels' => array('name' => __( 'Properties' ),'singular_name' => __( 'property' )),'public' => true,'has_archive' => true, 'rewrite' => true, 'query_var' => true, 'taxonomies' => array('category', 'post_tag')));

		global $wp_rewrite;
	    $property_structure = '/property/%state%/%city%/%zip%/%neighborhood%/%street%/%'.self::$property_post_type.'%';
        $wp_rewrite->add_rewrite_tag("%property%", '([^/]+)', "property=");
        $wp_rewrite->add_permastruct('property', $property_structure, false);
	}


	function force_rewrite_update () {
		if ( PL_PLUGIN_VERSION ) {
			$old_version = get_option('pl_plugin_version');
			if ($old_version != PL_PLUGIN_VERSION) {
				update_option('pl_plugin_version', PL_PLUGIN_VERSION);
				global $wp_rewrite;
				$wp_rewrite->flush_rules();
				PL_Cache::invalidate();
				// PL_HTTP::clear_cache();
				self::delete_all();
			}
		}
	}

	public function dump_permalinks () {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

}