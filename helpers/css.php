<?php 

PL_Css_Helper::init();

class PL_Css_Helper {
	
	function init () {		
		add_action( 'admin_init', array( __CLASS__, 'admin' ));
	}

	function admin () {
		self::register_enqueue_if_not('chosen', trailingslashit(PL_JS_LIB_URL) .  'chosen/chosen.css');		
		self::register_enqueue_if_not('datatable', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.css');		
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