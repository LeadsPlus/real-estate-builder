<?php

PLS_Style::add(array( 
		"name" => "Listing Styles",
		"type" => "heading"));

		PLS_Style::add(array( 
				"name" => "General Listing Styles",
				"desc" => "",
				"type" => "info"));

				PLS_Style::add(array(
						"name" => "Listing Address Link",
						"desc" => "",
						"id" => "listing_address",
						"selector" => ".listing-item h3 a, h3 a:visited",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Listing Address link on hover",
						"desc" => "",
						"id" => "listing_address_hover",
						"selector" => ".listing-item h3 a:hover",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Listing Details",
						"desc" => "",
						"id" => "listing_details",
						"selector" => ".listing-item ul li, .listing-item .basic-details p",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Listing Image",
						"desc" => "",
						"id" => "listing_featured_image",
						"selector" => ".listing-item .listing-thumbnail img",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Listing Image Border",
						"desc" => "",
						"id" => "listing_image_border",
						"selector" => ".listing-item .listing-thumbnail img",
						"type" => "border"));

				PLS_Style::add(array(
						"name" => "Listing Image Background",
						"desc" => "",
						"id" => "listing_image_background",
						"selector" => ".listing-item .listing-thumbnail img",
						"type" => "background"));

				PLS_Style::add(array(
						"name" => "Listing Description",
						"desc" => "",
						"id" => "listing_description_text",
						"selector" => ".listing-item .listing-description",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Listing 'View Property Details' link",
						"desc" => "",
						"id" => "listing_view_details_link",
						"selector" => ".listing-item a.more-link",
						"type" => "typography"));


		PLS_Style::add(array( 
				"name" => "Single Property Styles",
				"desc" => "",
				"type" => "info"));

				PLS_Style::add(array(
						"name" => "Single Property Address",
						"desc" => "",
						"id" => "single_property_address",
						"selector" => "body.single-property article.property-details h2",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Single Property Main Image background",
						"desc" => "",
						"id" => "single_property_main_image_background",
						"selector" => "body.single-property .property-details-slideshow img",
						"type" => "background"));

				PLS_Style::add(array(
						"name" => "Single Property Section Titles",
						"desc" => "",
						"id" => "single_property_section_titles",
						"selector" => "body.single-property .details-wrapper h3, body.single-property .amenities h3, body.single-property .map-wrapper h3",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Single Property Paragraph Text",
						"desc" => "",
						"id" => "single_property_paragraph_text",
						"selector" => "body.single-property .details-wrapper p, body.single-property .amenities p",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Single Property Details List",
						"desc" => "",
						"id" => "single_property_paragraph_details_list",
						"selector" => "body.single-property .details-wrapper ul li, body.single-property .amenities ul li",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Single Property Gallery Image Background",
						"desc" => "",
						"id" => "single_property_gallery_image_background",
						"selector" => "body.single-property .property-image-gallery img, body.single-property .map",
						"type" => "background"));

				PLS_Style::add(array(
						"name" => "Single Property Gallery Image Border",
						"desc" => "",
						"id" => "single_property_gallery_image_border",
						"selector" => "body.single-property .property-image-gallery img, body.single-property .map",
						"type" => "border"));

