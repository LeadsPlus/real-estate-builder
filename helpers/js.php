<?php 

PL_Js_Helper::init();

class PL_Js_Helper {

	public function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin' ));
	}	

	public function admin ($hook) {
		if ($hook != 'placester_page_placester_properties') {
			return;
		}

		// hack to force jquery to load properly. Needs to be removed once there's time to 
		// sort out who is using what.
    	wp_register_script( 'jquery2', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
	    wp_enqueue_script( 'jquery2' );
		
		self::register_enqueue_if_not('chosen', trailingslashit(PL_JS_LIB_URL) .  'chosen/chosen.jquery.min.js', array( 'jquery'));			
		self::register_enqueue_if_not('datatables', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.js', array( 'jquery'));			
		self::register_enqueue_if_not('chosen-custom', trailingslashit(PL_JS_URL) .  'admin/my-listings.js', array( 'jquery'));		
	}

	private function register_enqueue_if_not($name, $path, $dependencies = array(), $version = null, $in_footer = false) {
		if (!wp_script_is($name, 'registered')) {
			wp_register_script($name, $path, $dependencies, $version, $in_footer);		
		}

		if ( !wp_script_is($name, 'queue') ) {
			wp_enqueue_script($name);		
		}	
	}

//end class
}