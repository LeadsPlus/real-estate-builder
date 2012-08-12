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

	public static $subcodes = array('searchform'   => array('bedrooms',
												            'min_beds',
												            'max_beds',
												            'bathrooms',
												            'min_baths',
												            'max_baths',
												            'price',
												            'half_baths',
												            'property_type',
												            'listing_types',
												            'zoning_types',
												            'purchase_types',
												            'available_on',
												            'cities',
												            'states',
												            'zips',
												            'neighborhood',
												            'county',
												            'min_price',
												            'max_price',
												            'min_price_rental',
            												'max_price_rental') );

	public static $form_html = false;

	public function init() 
	{
		add_shortcode('searchform', array(__CLASS__, 'searchform_shortcode_handler'));
		add_shortcode('prop_details', array(__CLASS__, 'prop_details_shortcode_handler'));
		add_shortcode('list', array(__CLASS__, 'list_shortcode_handler'));

		// For any shortcodes that use subcodes, register them to a single handler that bears the shortcode's name
		foreach (self::$subcodes as $code => $subs) {
		  foreach ($subs as $sub) {
			add_shortcode($sub, array(__CLASS__, $code . '_sub_shortcode_handler'));
		  }	
		}

		// Register hooks to generate the forms...
		add_filter('pls_listings_search_form_outer_pls_shortcode', array(__CLASS__, 'searchform_shortcode_context'), 10, 6);

		// TODO: Construct defaults array properly...

		// Ensure all of shortcodes are set to some snippet...
		foreach (self::$codes as $code) {
			add_option( ('pls_' . $code), self::$defaults[$code][0] );
		}
	}

	public static function searchform_shortcode_handler($atts)
	{
		// Handle attributes using shortcode_atts...
		
		return PLS_Partials_Listing_Search_Form::init(array('context' => 'pls_shortcode'));
	}

	public static function searchform_shortcode_context($form, $form_html, $form_options, $section_title, $form_data)
	{
		$shortcode = 'searchform';
		self::$form_html = $form_html;

	  ob_start();
		// Get snippet ID currently associated with this shortcode...
		$option_key = ('pls_' . $shortcode);
		$snippet_name = get_option($option_key, self::$defaults[$shortcode][0]);

		// Determine if snippet is custom (in DB) or default (stored in flat-file)
		$snippet_DB_key = ('pls_' . $shortcode . '_' . $snippet_name);
		$type = ( get_option($snippet_DB_key) ? 'custom' : 'default' );

		$snippet_body = PL_Router::load_snippet($shortcode, $snippet_name, 'custom'); // define this properly...
		echo do_shortcode($snippet_body);
	  return ob_get_clean(); 
	}

	public static function property_details_shortcode_handler($atts)
	{
		// Handle attributes using shortcode_atts...
		return '<h2>Property Details go here...</h2>';
	}

	public static function list_shortcode_handler($atts)
	{
		return PLS_Partials_Get_Listings_Ajax::load(array('context' => 'adfsasdfasdf'));
		// Handle attributes using shortcode_atts...
	}

	/*** Subcode Handlers ***/

	public static function searchform_sub_shortcode_handler ($atts, $content, $tag) {
		pls_dump($tag);
		echo self::$form_html[$tag];
	}

	public static function list_sub_shortcode_handler ($tag) {

	}

}

?>