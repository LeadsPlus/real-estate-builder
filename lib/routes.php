<?php 
class PL_Router {

	private static function router($template, $params, $wrap = false, $directory = PL_VIEWS_ADMIN_DIR) {
		ob_start();
			// delete_option('placester_api_key');
			self::load_builder_view('header.php');
			if (!PL_Option_Helper::api_key()) {
				do_action('sign-up-action');
				self::load_builder_partial('sign-up.php');	
			}
			self::load_builder_view($template, $directory, $params);	
			self::load_builder_view('footer.php');
		echo ob_get_clean();	
		
	}

	public static function load_builder_partial($template, $params = array(), $return = false) {
		ob_start();
			if (!empty($params)) {
				extract($params);
			}
			include(trailingslashit(PL_VIEWS_PART_DIR) . $template);
		if ($return) {
			return ob_get_clean();
		} else {
			echo ob_get_clean();	
		}
	}

	public static function load_builder_library ($template, $directory = PL_JS_LIB_DIR) {
		include_once(trailingslashit($directory) . $template);
	}

	public static function load_builder_helper ($template, $directory = PL_HLP_DIR) {
		include_once(trailingslashit($directory) . $template);
	}

	private static function load_builder_view($template, $directory = PL_VIEWS_ADMIN_DIR, $params = array()) {
		ob_start();
			if (!empty($params)) {
				extract($params);
			}
			include_once(trailingslashit($directory) . $template);
			echo ob_get_clean();	
	}
	
	public function my_listings() {
		self:: router('my-listings.php', array('test'=>'donkey'), false);
	}

	public function add_listings() {
		if (isset($_GET['id'])) {
			$_POST = PL_Listing_Helper::details($_GET);
			switch ($_POST['compound_type']) {
				case 'res_sale':
					$_POST['property_type-res_sale'] = $_POST['property_type'];
					break;

				case 'res_rental':
					$_POST['property_type-res_rental'] = $_POST['property_type'];
					break;

				case 'vac_rental':
					$_POST['property_type-vac_rental'] = $_POST['property_type'];
					break;					

				case 'comm_sale':
					$_POST['property_type-comm_sale'] = $_POST['property_type'];
					break;

				case 'comm_rental':
					$_POST['property_type-comm_rental'] = $_POST['property_type'];
					break;

				case 'sublet':
					$_POST['property_type-sublet'] = $_POST['property_type'];
					break;
				
				default:
					# code...
					break;
			}
		}
		self:: router('add-listing.php', array(), false);
	}

	public static function load_snippet($shortcode, $snippet, $type) {
		ob_start();
			// Add parameter validation code...
		  switch ($type) 
		  {
		  	case 'custom' :
		  	  $snippet_DB_key = ('pls_' . $shortcode . '_' . $snippet);
		  	  echo get_option($snippet_DB_key, 'Cannot find custom snippet...');
		  	  break;                                                                                                     
		  	case 'default' :
		  	default :
		  	  $filename = (trailingslashit(PL_VIEWS_SHORT_DIR) . trailingslashit($shortcode) . $snippet . '.php');
		  	  //echo $filename;
		  	  include $filename;
		  }
		return ob_get_clean();
	}

	public function theme_gallery() {
		if (isset($_GET['theme_url'])) {
			self:: router('install-theme.php', array('test'=>'donkey'), false);	
		} else {
			self:: router('theme-gallery.php', array('test'=>'donkey'), false);	
		}
	}

	public function settings() {
		self:: router('settings/general.php', array(), false);
	}
	public function settings_polygons() {
		self:: router('settings/polygons.php', array(), false);
	}
	public function settings_property_pages() {
		self:: router('settings/property.php', array(), false);
	}
	public function settings_international() {
		self:: router('settings/international.php', array(), false);
	}
	public function settings_neighborhood() {
		self:: router('settings/neighborhood.php', array(), false);
	}
	public function settings_caching() {
		self:: router('settings/caching.php', array(), false);
	}
	public function settings_filtering() {
		self:: router('settings/filtering.php', array(), false);
	}
	public function settings_template() {
		self:: router('settings/template.php', array(), false);
	}
	public function settings_client() {
		self::load_builder_helper('membership.php');
		self:: router('settings/client.php', PL_Membership_Helper::get_client_settings(), false);
	}

	public function support() {
		self:: router('support.php', array(), false);
	}

	public function integrations() {
		self:: router('integrations.php', array(), false);
	}

//end of class
}