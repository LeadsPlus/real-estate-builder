<?php

/****** 
	I need to figure out how to hook this into the WP execution framework properly
	so the rest of the plugin & WP can actually utilize these... 
******/

PL_Shortcodes::init();
class PL_Shortcodes
{
	public static $codes = array('searchform', 'prop_details', 'list');

	public static $p_codes = array('searchform' => 'Search Form', 'prop_details' => 'Property Details', 'list' => 'List');
	
	public static $defaults = array('searchform' 	=> array('ventura', 'columbus', 'highland'),
	  				                'prop_details' 	=> array('Red', 'Yellow', 'Orange'),
			               			'list' 			=> array('Purple', 'Pink', 'Gray') );

	public static $form_options = false;

	public function init() 
	{
		add_shortcode('searchform', array(__CLASS__, 'searchform_shortcode_func'));
		add_shortcode('prop_details', array(__CLASS__, 'prop_details_shortcode_func'));
		add_shortcode('list', array(__CLASS__, 'list_shortcode_func'));
		add_shortcode('bedrooms', array(__CLASS__, 'list_shortcode_func_bedrooms'));
		add_filter('pls_listings_search_form_outer_pls_shortcode', array(__CLASS__, 'searchform_shortcode_context'), 10, 6);

		// TODO: Construct defaults array properly...

		// Ensure all of shortcodes are set to some snippet...
		foreach (self::$codes as $code) {
			add_option( ('pls_' . $code), self::$defaults[$code][0] );
		}
	}

	public static function searchform_shortcode_func($atts)
	{
		// For testing purposes, simply echo snippet contents...
		return PLS_Partials_Listing_Search_Form::init(array('context' => 'pls_shortcode'));
	}

	public static function searchform_shortcode_context($form, $form_html, $form_options, $section_title, $form_data)
	{
		self::$form_options = $form_html;
	 ob_start();
	    // Define these properly...
	  	$shortcode = 'searchform';
	  	$default_snippet = 'ventura.php';

		// Handle attributes using shortcode_atts...

		// Get snippet ID currently associated with this shortcode...
		$option_key = ('pls_' . $shortcode);
		$snippet_name = get_option($option_key, $default_snippet);
		$snippet_body = PL_Router::load_snippet($shortcode, $snippet_name, 'custom'); // define this properly...
		echo do_shortcode($snippet_body);
	  return ob_get_clean(); 
	}



	public static function property_details_shortcode_func($atts)
	{
		// Handle attributes using shortcode_atts...
		return '<h2>Property Details go here...</h2>';
	}

	public static function list_shortcode_func($atts)
	{
		return PLS_Partials_Get_Listings_Ajax::load(array('context' => 'adfsasdfasdf'));
		// Handle attributes using shortcode_atts...
	}

	function list_shortcode_func_bedrooms () {
		// pls_dump(self::$form_options);
		echo self::$form_options['bedrooms'];
	}
}

?>