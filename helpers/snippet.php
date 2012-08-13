<?php

// TODO: replace WP DB functions when blueprint is integrated...

PL_Snippet_Helper::init();

class PL_Snippet_Helper {

	// Static?  Why or why not?
	public function init() 
	{
		add_action( 'wp_ajax_get_snippet_body', array(__CLASS__, 'get_snippet_body_ajax' ) );
		add_action( 'wp_ajax_activate_snippet', array(__CLASS__, 'activate_snippet_ajax') );
		add_action( 'wp_ajax_save_custom_snippet', array(__CLASS__, 'save_custom_snippet_ajax') );
	}	

	// Static?  Why or why not?
	public function get_snippet_body_ajax() 
	{
		if ($_POST['shortcode'] && $_POST['snippet'] && $_POST['type']) {
			$snippet_body = html_entity_decode(PL_Router::load_snippet($_POST['shortcode'], $_POST['snippet'], $_POST['type']), ENT_QUOTES);
			$response = array( 'snippet_body' =>  $snippet_body);
			echo json_encode($response);
		} else {
			echo array();
		}

		die();
	}

	public function activate_snippet_ajax() 
	{
		if ($_POST['shortcode'] && $_POST['snippet']) {
			//$snippet_DB_key = ( $_POST['type'] == 'default' ? $_POST['snippet'] : ('pls_' . $_POST['shortcode'] . '_' . $_POST['snippet_name']) );
			$shortcode_DB_key = ('pls_' . $_POST['shortcode']);
			update_option($shortcode_DB_key, $_POST['snippet']);

			// Try to retrieve the option that was just set...
			$activated_snippet_id = get_option($shortcode_DB_key);
			echo json_encode(array('activated_snippet_id' => $activated_snippet_id));
		} else {
			echo array();
		}

		die();
	}

	public function save_custom_snippet_ajax() 
	{
		if ($_POST['shortcode'] && $_POST['snippet'] && $_POST['snippet_body']) 
		{
			// Format & sanitize snippet_body...
			$snippet_body = stripslashes($_POST['snippet_body']);
			$snippet_body = preg_replace('/<\?.*?(\?>|$)/', '', strip_tags($snippet_body, "<a><p><script><div><span><section><label><br><scr'+'ipt>"));
			$snippet_body = htmlentities($snippet_body, ENT_QUOTES);

			// This is sure to be unique due to restrictions enforced in the UI...
			$snippet_DB_key = ('pls_' . $_POST['shortcode'] . '_' . $_POST['snippet']);
			update_option($snippet_DB_key, $snippet_body);

			// Add to the list of custom snippet IDs for this shortcode...
			$snippet_list_DB_key = ('pls_' . $_POST['shortcode'] . '_list');
			$snip_arr = get_option($snippet_list_DB_key, array()); // If it doesn't exist, create a blank array to append...
			$snip_arr[] = $_POST['snippet'];

			// Update (or add) list in (to) DB...
			update_option($snippet_list_DB_key, $snip_arr);

			// Blow-out the cache so the changes to the snippet can take effect...
			PL_Cache::clear();

			echo json_encode(array('unique_id' => $snippet_DB_key, 'id_array' => $snip_arr));
		} else {
			echo array();
		}

		die();
	}

	public function delete_snippet_ajax() 
	{
		// TODO...
	}

	////////////////
	// Utility Funcs

	public static function get_shortcode_snippet_list($shortcode) 
	{
		// Get list of custom snippet ids for this shortcode...
		$snippet_list_DB_key = ('pls_' . $shortcode . '_list');
		$snip_arr = get_option($snippet_list_DB_key, array());

		$default_snippets = PL_Shortcodes::$defaults[$shortcode];

		$snippet_type_map = array();
		
		foreach ($default_snippets as $snippet) {
			$snippet_type_map[$snippet] = 'default';
		}

		// Add Custom snippets..
		foreach ($snip_arr as $snippet) {
			$snippet_type_map[$snippet] = 'custom'; 
		}

		return $snippet_type_map;
	}

	public static function get_active_snippet_map() 
	{
		$active_snippet_map = array();

		foreach (PL_Shortcodes::$codes as $code) {
			$active_snippet_map[$code] = get_option(('pls_' . $code), '');
		}

		return $active_snippet_map;
	}
}

?>