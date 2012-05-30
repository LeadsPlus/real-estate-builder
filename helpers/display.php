<?php 

PL_Display_Helper::init();
class PL_Display_Helper {

	static $excerpt_template_name = 'pl_excerpt_template';
	static $content_template_name = 'pl_contnet_template';
	static $excerpt_templates_name = 'pl_excerpt_templates';
	static $content_templates_name = 'pl_content_templates';

	function init () {
		add_action('wp_ajax_save_template', array(__CLASS__, 'save_template_ajax'));
		add_action('wp_ajax_save_template', array(__CLASS__, 'get_template_ajax'));
	}

	function save_template_ajax () {

	}

	function get_template_ajax () {

	}

	function get_current_template ($arg = array()) {
		extract(wp_parse_args($args, array('type' => 'content')));
		if ($type == 'content') {
			$templates = get_option(self::$content_template_name, array());
		} elseif ($type == 'excerpt') {
			$templates = get_option(self::$excerpt_template_name, array());
		}
		foreach ($templates as $template) {
			
		}
	}

	function save_template ($args = array()) {
		extract(wp_parse_args($args, array('type' => 'content', 'name' => false, 'template_value' => false)));
		if ($name && $template_value) {
			if ($type == 'content') {
				$templates = get_option(self::$content_template_name, array());
			} elseif ($type == 'excerpt') {
				$templates = get_option(self::$excerpt_template_name, array());
			}
			$templates[$name] = $template_value;
			return $templates;
		}	
		return '';
	}

	function get_templates ($arg = array()) {
		extract(wp_parse_args($args, array('type' => 'content')));
		if ($type == 'content') {
			$templates = get_option(self::$content_template_name, array());
		} elseif ($type == 'excerpt') {
			$templates = get_option(self::$excerpt_template_name, array());
		}
	}
//end of class
}