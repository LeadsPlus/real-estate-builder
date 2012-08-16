<?php 


PLS_Style::add(array( 
            "name" => "Custom CSS",
            "type" => "heading"));

PLS_Style::add(array( 
                "name" => "Custom CSS",
                "desc" => "Enter custom css styles here. Will override any theme styles as well as any theme options you've already set.",
                "id" => "pls-custom-css",
                "type" => "textarea"));

							PLS_Style::add(array(
									"name" => "Activate custom css options",
									"desc" => "Allows you to enter custom css directly. Will override theme defaults, as well as any options you've set",
									"id" => "pls-css-options",
									"std" => "1",
									"type" => "checkbox"));

