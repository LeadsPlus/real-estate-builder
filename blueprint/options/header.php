<?php 

PLS_Style::add(array( 
		"name" => "Header Styles",
		"type" => "heading"));
		
		PLS_Style::add(array(
				"name" => "Site Title Typography",
				"desc" => "",
				"id" => "header_title",
				"selector" => "header h1 a, header h1 a:visited",
				"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Site Title on hover",
				"desc" => "",
				"id" => "header_title_hover",
				"selector" => "header h1 a:hover",
				"type" => "typography"));

			PLS_Style::add(array(
					"name" => "Site Sub-Title Typography",
					"desc" => "",
					"id" => "header_subtitle",
					"selector" => "header h2",
					"type" => "typography"));
