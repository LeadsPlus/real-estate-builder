<?php

PLS_Style::add(array( 
		"name" => "Search Options",
		"type" => "heading"));


//What search filters are available?

	PLS_Style::add(array( 
			"name" =>  "Visible Filters on Search Page",
			"desc" => "Use the checkboxes to indicate which filters are visible to searchers. Note that if you choose too many it might be overwhelming!",
			"id" => "listing-search-page",
			"selector" => "body",
			"options" => array(
				'half_baths' => 'Half Baths',
				'price' => 'Price',
				'min_price' => 'Min Price',
				'max_price' => 'Max Price',
				'sqft' => 'Square Feet',
				'bathrooms' => 'Baths',
				'available_on' => 'Available On',
				'bedrooms' => 'Beds',
				'property_type' => 'Property Type',
            	'listing_types' => 'Listing Type',
            	'zoning_types' => 'Zonging Type',
            	'purchase_types' => 'Purchase Type',
            	'available_on' => 'Available On',
            	'cities' => 'City',
            	'states' => 'State',
            	'zips' => 'Zip Code',
				'desc' => 'Description',
				'lt_sz' => 'Lot Size',
				'ngb_shop' => 'Local Shopping',
				'ngb_hgwy' => 'Local Highway Access',
				'grnt_tops' => 'Granite Counter Tops',
				'ngb_med' => 'Local Medical Facilities',
				'ngb_trails' => 'Local Walk/Jog Trails',
				'cent_ht' => 'Central Heat',
				'pk_spce' => 'Parking Spaces Included',
				'air_cond' => 'Air Conditioning',
				'price_unit' => 'Unit Price',
				'lt_sz_unit' => 'Unit Lot Size',
				'lse_trms' => 'Lease Terms',
				'ngb_trans' => 'Local Public Transportation',
				'off_den' => 'Office / Den',
				'frnshed' => 'Furnished',
				'refrig' => 'Refrigerator',
				'deposit' => 'Deposit',
				'ngb_pubsch' => 'Local Public Schools',
				'beds_avail' => 'Beds Available',
				'hide_all' => 'Hide All',
			),
			"type" => "multicheck"));

//What are the options in those filters?
	
	//price range
	PLS_Style::add(array(
		"name" => "Price Range Options",
		"desc" => "Use the controls below to change what options are displayed when users click the bedrooms filer",
		"type" => "info"));

	PLS_Style::add(array(
		"name" => "Min Search Price",
		"desc" => "",
		"id" => "pls-option-price-min",
		"std" => "0",
		"type" => "text"));

	PLS_Style::add(array(
		"name" => "Max Search Price",
		"desc" => "",
		"id" => "pls-option-price-max",
		"std" => "1000000",
		"type" => "text"));

	PLS_Style::add(array(
		"name" => "Price Increment",
		"desc" => "",
		"std" => "50000",
		"id" => "pls-option-price-inc",
		"type" => "text"));
	
	//beds
	PLS_Style::add(array(
				"name" => "Bedroom Options",
				"desc" => "Use the controls below to change what options are displayed when users click the bedrooms filter",
				"type" => "info"));

	PLS_Style::add(array(
		"name" => "Bedroom Options Start",
		"desc" => "",
		"id" => "pls-option-bed-min",
		"std" => "0",
		"type" => "text"));

	PLS_Style::add(array(
		"name" => "Bedroom Options End",
		"desc" => "",
		"id" => "pls-option-bed-max",
		"std" => "15",
		"type" => "text"));

	//baths
	PLS_Style::add(array(
		"name" => "Bathroom Options",
		"desc" => "Use the controls below to change what options are displayed when users click the bathrooms filter",
		"type" => "info"));

	PLS_Style::add(array(
		"name" => "Bathroom Options Start",
		"desc" => "",
		"id" => "pls-option-bath-min",
		"std" => "0",
		"type" => "text"));

	PLS_Style::add(array(
		"name" => "Bathroom Options End",
		"desc" => "",
		"id" => "pls-option-bath-max",
		"std" => "10",
		"type" => "text"));

	//half-baths
	PLS_Style::add(array(
		"name" => "Half Bath Options",
		"desc" => "Use the controls below to change what options are displayed when users click the bathrooms filter",
		"type" => "info"));

	PLS_Style::add(array(
		"name" => "Half Bath Options Start",
		"desc" => "",
		"id" => "pls-option-half-bath-min",
		"std" => "0",
		"type" => "text"));

	PLS_Style::add(array(
		"name" => "Half Bath Options End",
		"desc" => "",
		"id" => "pls-option-half-bath-max",
		"std" => "5",
		"type" => "text"));