<?php

PLS_Style::add(array( 
		"name" => "Color Styles",
		"type" => "heading"));

	PLS_Style::add(array( 
			"name" =>  "Site Background",
			"desc" => "Change the site's background.",
			"id" => "site_background",
			"selector" => "body",
			"type" => "background"));

	PLS_Style::add(array( 
			"name" =>  "Inner Background",
			"desc" => "Change the site's inner background.",
			"id" => "inner_background",
			"selector" => ".inner",
			"type" => "background"));