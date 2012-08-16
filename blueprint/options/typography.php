<?php 

    PLS_Style::add(array( 
            "name" => "Typography Options",
            "type" => "heading"));

    PLS_Style::add(array(
            "name" => "Paragraph Style Options",
            "desc" => "This is the base font style across your entire website.",
            "id" => "body.font",
            "std" => "",
            "type" => "typography"));



	PLS_Style::add(array(
            "name" => "Normal Link Styles",
            "desc" => "This is the base font style for links across your entire website.",
            "id" => "body.a",
            "std" => "",
            "type" => "typography"));


      PLS_Style::add(array(
            "name" => "Hover Link Styles",
            "desc" => "This is the base font style for links on hover across your entire website.",
            "id" => "body.a:hover",
            "std" => "",
            "type" => "typography"));


      PLS_Style::add(array(
            "name" => "Visited Link Styles",
            "desc" => "This is the base font style for visited links across your entire website.",
            "id" => "pls-visited-link-styles",
            "selector" => "body.a:visited",
            "type" => "typography"));
