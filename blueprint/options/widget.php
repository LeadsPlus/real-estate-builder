<?php 

$typography_defaults = array('color' => '', 'face' => '', 'size' => '', 'style' => '');


PLS_Style::add(array( 
		"name" => "Widget Styles",
		"type" => "heading"));

		PLS_Style::add(array(
				"name" => "Widget Titles",
				"desc" => "",
				"id" => "widget_title_typography",
				"selector" => "aside h3",
				"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Search Widget",
				"desc" => "These options will customize the sidebar search widget.",
				"type" => "info"));

				PLS_Style::add(array(
						"name" => "Quick Search Widget  - label typography",
						"desc" => "",
						"id" => "widget_search_label_typography",
						"selector" => "aside .pls-quick-search label",
						"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Agent Widget",
				"desc" => "These options will customize the sidebar agent widget.",
				"type" => "info"));

				PLS_Style::add(array(
						"name" => "Agent Widget  - agent name typography",
						"desc" => "",
						"id" => "widget_agent_name_typography",
						"selector" => "aside .widget-pls-agent h5",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Agent Widget  - agent email typography",
						"desc" => "",
						"id" => "widget_agent_email_typography",
						"selector" => "aside .widget-pls-agent span.email a",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Agent Widget  - agent phone typography",
						"desc" => "",
						"id" => "widget_agent_phone_typography",
						"selector" => "aside .widget-pls-agent span.phone",
						"type" => "typography"));

				PLS_Style::add(array(
						"name" => "Listing Widget",
						"desc" => "These options will customize the sidebar listings widget.",
						"type" => "info"));

						PLS_Style::add(array(
								"name" => "Listings Widget  - listings address typography",
								"desc" => "",
								"id" => "widget_listings_address_typography",
								"selector" => "aside .pls-listings h4 a",
								"type" => "typography"));

						PLS_Style::add(array(
								"name" => "Listings Widget  - listings details typography",
								"desc" => "",
								"id" => "widget_listings_details_typography",
								"selector" => "aside .pls-listings .details",
								"type" => "typography"));

						PLS_Style::add(array(
								"name" => "Listings Widget  - listings image border",
								"desc" => "",
								"id" => "widget_listings_image_border",
								"selector" => "aside .pls-listings .featured-image a img",
								"type" => "border"));

						PLS_Style::add(array(
								"name" => "Listings Widget  - listings image background",
								"desc" => "",
								"id" => "widget_listings_image_background",
								"selector" => "aside .pls-listings .featured-image",
								"type" => "background"));

						PLS_Style::add(array(
								"name" => "Listings Widget  - Learn More link",
								"desc" => "",
								"id" => "widget_listings_learn_more_link",
								"selector" => "aside .pls-listings a.learn-more",
								"type" => "typography"));

						PLS_Style::add(array(
								"name" => "Listings Widget  - Learn More link on hover",
								"desc" => "",
								"id" => "widget_listings_learn_more_link_hover",
								"selector" => "aside .pls-listings a.learn-more:hover",
								"type" => "typography"));
