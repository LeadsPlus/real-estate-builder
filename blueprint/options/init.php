<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */


// standard path.
$imagepath =  trailingslashit( PLS_EXT_URL ) . 'options-framework/images/';


PLS_Style::add(array(
		"name" => "General",
		"type" => "heading"));

	PLS_Style::add(array(
			"name" => "Site Title",
			"desc" => "Site title in header.",
			"id" => "pls-site-title",
			"type" => "text"));

	PLS_Style::add(array(
			"name" => "Site Subtitle",
			"desc" => "Site subtitle in header.",
			"id" => "pls-site-subtitle",
			"type" => "text"));

	PLS_Style::add(array(
			"name" => "Site Logo",
			"desc" => "Upload your logo here. It will appear in the header and will override the title you've provided above.",
			"id" => "pls-site-logo",
			"type" => "upload"));

		PLS_Style::add(array( 
				"name" => "Site Favicon",
				"desc" => "Upload your favicon here. It will appear in your visitors url and bookmark bar.",
				"id" => "pls-site-favicon",
				"type" => "upload"));

		PLS_Style::add(array( 
				"name" => "Slideshow Listings",
				"desc" => "",
				"id" => "slideshow-featured-listings",
				"type" => "featured_listing"));

		PLS_Style::add(array( 
				"name" => "Featured Listings",
				"desc" => "Select your featured listings here that will display in your sidebar listings widget as well as on the home page's featured list.",
				"id" => "custom-featured-listings",
				"type" => "featured_listing"));

		PLS_Style::add(array( 
				"name" => "Google Analytics Tracking Code",
				"desc" => "Add your google analytics tracking ID code here. It looks something like this: UA-XXXXXXX-X",
				"id" => "pls-google-analytics",
				"type" => "text"));

		PLS_Style::add(array(
				"name" => "Display Theme Debug Messages",
				"desc" => "Display the theme debug panel at the bottom of all non-admin pages. Great for debugging issues with the themes.",
				"id" => "display-debug-messages",
				"std" => "0",
				"type" => "checkbox"));