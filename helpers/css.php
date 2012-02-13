<?php 

PL_Css_Helper::init();

class PL_Css_Helper {
	
	function init () {		
		// add_action( 'admin_init', array( __CLASS__, 'admin' ));
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin' ));
	}

	function admin ($hook) {
		$pages = array('placester_page_placester_properties', 'placester_page_placester_property_add');

		if (!in_array($hook, $pages)) { return; }

		//always load these
		self::register_enqueue_if_not('global-css', trailingslashit(PL_CSS_URL) .  'global.css');		
		self::register_enqueue_if_not('chosen', trailingslashit(PL_JS_LIB_URL) .  'chosen/chosen.css');		


		if ($hook == 'placester_page_placester_properties') {
			self::register_enqueue_if_not('my-listings', trailingslashit(PL_CSS_ADMIN_URL) .  'my-listings.css');		
			self::register_enqueue_if_not('jquery-ui', trailingslashit(PL_JS_LIB_URL) .  'jquery-ui/css/smoothness/jquery-ui-1.8.17.custom.css');		
		}

		if ($hook == 'placester_page_placester_property_add') {
			self::register_enqueue_if_not('add-listing', trailingslashit(PL_CSS_ADMIN_URL) .  'add-listing.css');			
		}
	}

	private function register_enqueue_if_not($name, $path, $dependencies = array()) {
		if (!wp_style_is($name, 'registered')) {
			wp_register_style($name, $path, $dependencies);		
		}

		if ( !wp_style_is($name, 'queue') ) {
			wp_enqueue_style($name);		
		}	
	}

// end of class
}