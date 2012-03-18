<?php 

PL_Js_Helper::init();

class PL_Js_Helper {

	public function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin' ));
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend' ));
	}	

	public function admin ($hook) {
		$pages = array('placester_page_placester_properties', 'placester_page_placester_property_add', 'placester_page_placester_settings', 'placester_page_placester_support');
		if (!in_array($hook, $pages)) { return; }

		// hack to force jquery to load properly. Needs to be removed once there's time to 
		// sort out who is using what.
    	wp_dequeue_script( 'jquery' );
    	wp_register_script( 'jquery2', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	    wp_enqueue_script( 'jquery2' );
	    
		//    /
		//    /		
		
		self::register_enqueue_if_not('jquery-ui', trailingslashit(PL_JS_LIB_URL) .  'jquery-ui/js/jquery-ui-1.8.17.custom.min.js', array( 'jquery'));
		self::register_enqueue_if_not('global', trailingslashit(PL_JS_URL) .  'admin/global.js', array( 'jquery-ui'));
		self::register_enqueue_if_not('sign-up', trailingslashit(PL_JS_URL) .  'admin/sign-up.js', array( 'jquery-ui'));
		
		
		if ($hook == 'placester_page_placester_properties') {
			self::register_enqueue_if_not('datatables', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.js', array( 'jquery'));			
			self::register_enqueue_if_not('my-listings', trailingslashit(PL_JS_URL) .  'admin/my-listings.js', array( 'jquery'));
		}

		if ($hook == 'placester_page_placester_settings') {
			self::register_enqueue_if_not('my-listings', trailingslashit(PL_JS_URL) .  'admin/settings.js', array( 'jquery'));	
		}

		if ($hook == 'placester_page_placester_property_add') {						
			self::register_enqueue_if_not('blueimp-iframe', trailingslashit(PL_JS_LIB_URL) .  'blueimp/js/jquery.iframe-transport.js', array( 'jquery'));			
			self::register_enqueue_if_not('blueimp-file-upload', trailingslashit(PL_JS_LIB_URL) .  'blueimp/js/jquery.fileupload.js', array( 'jquery'));			
			self::register_enqueue_if_not('add-listing', trailingslashit(PL_JS_URL) .  'admin/add-listing.js', array( 'jquery'));			
		}
		
	}

	public function frontend() {
		self::register_enqueue_if_not('datatables', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.js', array( 'jquery'));			
		self::register_enqueue_if_not('leads', trailingslashit(PL_JS_PUB_URL) .  'leads.js', array( 'jquery'));			
		self::register_enqueue_if_not('membership', trailingslashit(PL_JS_PUB_URL) .  'membership.js', array( 'jquery'));
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