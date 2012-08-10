<?php

/****** 
	I need to figure out how to hook this into the WP execution framework properly
	so the rest of the plugin & WP can actually utilize these... 
******/

PL_Shortcodes::init();
class PL_Shortcodes
{
	static 

	public function init() 
	{
		add_shortcode('searchform', array(__CLASS__, 'searchform_shortcode_func'));
		add_shortcode('prop_details', array(__CLASS__, 'prop_details_shortcode_func'));
		add_shortcode('excerpt', array(__CLASS__, 'excerpt_shortcode_func'));
	}

	public static function searchform_shortcode_func($atts)
	{
	  ob_start();
	    // Define these properly...
	  	$shortcode = 'searchform';
	  	$default_snippet = 'ventura.php';

		// Handle attributes using shortcode_atts...

		// Get snippet currently associated with this shortcode...
		$option_key = ('pls_' . $shortcode);
		$snippet_name = get_option($option_key, $default_snippet);
		$snippet_body = PL_Router::load_snippet($shortcode, $snippet_name); // define this properly...

		// For testing purposes, simply echo snippet contents...
		echo $snippet_body;
	  return ob_get_clean();
	}

	public static function property_details_shortcode_func($atts)
	{
		// Handle attributes using shortcode_atts...
		return '<h2>Property Details go here...</h2>';
	}

	public static function excerpt_shortcode_func($atts)
	{
		// Handle attributes using shortcode_atts...
		return '<h2>Excerpt goes here...</h2>';
	}
}

?>