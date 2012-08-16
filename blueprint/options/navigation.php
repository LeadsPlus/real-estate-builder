<?php  
PLS_Style::add(array( 
		"name" => "Navigation Styles",
		"type" => "heading"));

			PLS_Style::add(array(
					"name" => "Navigation Pages' Typography",
					"desc" => "",
					"id" => "navigation_item_typography",
					"selector" => ".main-nav ul li a",
					"type" => "typography"));

			PLS_Style::add(array(
					"name" => "Navigation Pages' Typography on hover",
					"desc" => "",
					"id" => "navigation_item_typography_hover",
					"selector" => ".main-nav ul li a:hover",
					"type" => "typography"));

			PLS_Style::add(array(
					"name" => "Navigation Pages on hover background",
					"desc" => "",
					"id" => "navigation_item_hover_background",
					"selector" => ".main-nav ul li a:hover",
					"type" => "background"));

			PLS_Style::add(array(
					"name" => "Navigation Current Page",
					"desc" => "",
					"id" => "navigation_current_item_typography",
					"selector" => ".main-nav ul li.current_page_item",
					"type" => "typography"));

			PLS_Style::add(array(
					"name" => "Navigation Current Page Background",
					"desc" => "",
					"id" => "navigation_current_item_background",
					"selector" => ".main-nav ul li.current_page_item",
					"type" => "background"));

			PLS_Style::add(array(
					"name" => "Navigation Border",
					"desc" => "",
					"id" => "navigation_border",
					"selector" => ".main-nav",
					"type" => "border"));