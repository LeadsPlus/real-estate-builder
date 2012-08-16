<?php 

class PLS_Pages {

	public function get_page_by_name ($page_name, $page_template = '', $create = false) {
		$page_object = get_page_by_title($page_name);
		if (isset($page_object->ID)) {
			return $page_object->ID;
		} elseif ($create) {
			$page_list[] = array( 'title' => $page_name, 'template' => $page_template);
			PLS_Plugin_API::create_page($page_list);
		}
	}

	function create_once ($pages_to_create) {
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

}