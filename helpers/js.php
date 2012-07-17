<?php 

PL_Js_Helper::init();

class PL_Js_Helper {

	public function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin' ));
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend' ));
		add_action('admin_head',array(__CLASS__, 'admin_menu_url'));
	}	

	public function admin ($hook) {
		$pages = array('placester_page_placester_properties', 'placester_page_placester_property_add', 'placester_page_placester_settings', 'placester_page_placester_support', 'placester_page_placester_theme_gallery', 'placester_page_placester_integrations',
			'placester_page_placester_settings_polygons', 'placester_page_placester_settings_property_pages', 'placester_page_placester_settings_international', 'placester_page_placester_settings_neighborhood', 'placester_page_placester_settings_caching', 'placester_page_placester_settings_filtering', 'placester_page_placester_settings_client');
		if (!in_array($hook, $pages)) { return; }

		// hack to force jquery to load properly. Needs to be removed once there's time to 
		// sort out who is using what.
    	wp_dequeue_script( 'jquery' );
    	wp_register_script( 'jquery2', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	    wp_enqueue_script( 'jquery2' );	
		
		self::register_enqueue_if_not('jquery-ui', trailingslashit(PL_JS_LIB_URL) .  'jquery-ui/js/jquery-ui-1.8.17.custom.min.js', array( 'jquery'));
		self::register_enqueue_if_not('global', trailingslashit(PL_JS_URL) .  'admin/global.js', array( 'jquery-ui'));
		self::register_enqueue_if_not('sign-up', trailingslashit(PL_JS_URL) .  'admin/sign-up.js', array( 'jquery-ui'));
		self::register_enqueue_if_not('free-trial', trailingslashit(PL_JS_URL) .  'admin/free-trial.js', array( 'jquery-ui'));
		
		
		if ($hook == 'placester_page_placester_properties') {
			self::register_enqueue_if_not('datatables', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.js', array( 'jquery'));			
			self::register_enqueue_if_not('my-listings', trailingslashit(PL_JS_URL) .  'admin/my-listings.js', array( 'jquery'));
		}

		if ($hook == 'placester_page_placester_property_add') {						
			self::register_enqueue_if_not('blueimp-iframe', trailingslashit(PL_JS_LIB_URL) .  'blueimp/js/jquery.iframe-transport.js', array( 'jquery'));			
			self::register_enqueue_if_not('blueimp-file-upload', trailingslashit(PL_JS_LIB_URL) .  'blueimp/js/jquery.fileupload.js', array( 'jquery'));			
			self::register_enqueue_if_not('add-listing', trailingslashit(PL_JS_URL) .  'admin/add-listing.js', array( 'jquery'));			
		}

		if ($hook == 'placester_page_placester_theme_gallery') {						
			self::register_enqueue_if_not('theme-gallery', trailingslashit(PL_JS_URL) .  'admin/theme-gallery.js', array( 'jquery'));			
		}

		if ($hook == 'placester_page_placester_integrations') {						
			self::register_enqueue_if_not('integration', trailingslashit(PL_JS_URL) .  'admin/integration.js', array( 'jquery'));			
		}


		if ($hook == 'placester_page_placester_settings') {
			self::register_enqueue_if_not('settings', trailingslashit(PL_JS_URL) .  'admin/settings/general.js', array( 'jquery'));	
		}

		if ($hook == 'placester_page_placester_settings_polygons') {
			self::register_enqueue_if_not('settings', trailingslashit(PL_JS_URL) .  'admin/settings/polygon.js', array( 'jquery'));	
			self::register_enqueue_if_not('new-colorpicker', trailingslashit(PL_JS_URL) .  'lib/colorpicker/js/colorpicker.js', array( 'jquery'));	
			self::register_enqueue_if_not('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false', array( 'jquery'));	
			self::register_enqueue_if_not('text-overlay', trailingslashit(PL_JS_URL) .  'lib/google-maps/text-overlay.js', array( 'jquery'));	
			self::register_enqueue_if_not('datatables', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.js', array( 'jquery'));	
		}

		if ($hook == 'placester_page_placester_settings_property_pages') {
			self::register_enqueue_if_not('settings-property', trailingslashit(PL_JS_URL) .  'admin/settings/property.js', array( 'jquery'));	
			self::register_enqueue_if_not('datatables', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.js', array( 'jquery'));	
		}
		if ($hook == 'placester_page_placester_settings_international') {
			self::register_enqueue_if_not('settings', trailingslashit(PL_JS_URL) .  'admin/settings/international.js', array( 'jquery'));	
			self::register_enqueue_if_not('settings', trailingslashit(PL_JS_URL) .  'admin/settings.js', array( 'jquery'));	
		}
		if ($hook == 'placester_page_placester_settings_neighborhood') {
			self::register_enqueue_if_not('settings', trailingslashit(PL_JS_URL) .  'admin/settings.js', array( 'jquery'));	
		}
		if ($hook == 'placester_page_placester_settings_caching') {
			self::register_enqueue_if_not('settings', trailingslashit(PL_JS_URL) .  'admin/settings/caching.js', array( 'jquery'));	
			self::register_enqueue_if_not('datatables', trailingslashit(PL_JS_LIB_URL) .  'datatables/jquery.dataTables.js', array( 'jquery'));	
		}
		if ($hook == 'placester_page_placester_settings_filtering') {
			self::register_enqueue_if_not('settings', trailingslashit(PL_JS_URL) .  'admin/settings/filtering.js', array( 'jquery'));	
		}
		if ($hook == 'placester_page_placester_settings_client') {
			self::register_enqueue_if_not('settings-client', trailingslashit(PL_JS_URL) .  'admin/settings/client.js', array( 'jquery'));	
		}
		
	}

	public function admin_menu_url () {
		?>
			<script type="text/javascript">
				var adminurl = '<?php echo ADMIN_MENU_URL; ?>';
				var siteurl = '<?php echo site_url(); ?>';
			</script>
		<?php

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