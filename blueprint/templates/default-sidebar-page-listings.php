<?php
	
	// Lead Caputure Form
	$instance = array("title" => "Have a question?", "number" => 9);
	$args = array();
	$sb = new Placester_Contact_Widget();
	$sb->number = $instance['number'];
	$sb->widget($args,$instance);


	// Agent Widget
	$instance = array("title" => "Give us a call", "number" => 9);
	$args = array();
	$sb = new PLS_Widget_Agent();
	$sb->number = $instance['number'];
	$sb->widget($args,$instance);


	// Recent Lisitngs Widget
	$instance = array("title" => "Recent Listings", "number" => 9);
	$args = array();
	$sb = new PLS_Widget_Listings();
	$sb->number = $instance['number'];
	$sb->widget($args,$instance);
