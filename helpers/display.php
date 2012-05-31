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

	function get_templates_as_options ($args = array()) {
		$args = wp_parse_args($args, array('type' => 'content'));
		$templates = self::get_templates($args);
		if (!empty($templates)) {
			ob_start();
			?>
				<?php foreach ($templates as $value => $label): ?>
					<option value="<?php echo $value ?>"><?php echo $label ?></option>
				<?php endforeach ?>
			<?php
			return ob_get_clean();
		} else {
			return '';
		}
		
	}

	private function get_templates ($args = array()) {
		$tempaltes = array();
		if ($args['type'] == 'content') {
			$dir = PL_TPL_CON_DIR; 	
		} else {
			$dir = PL_TPL_EXR_DIR; 	
		}
		
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while ( (($file = readdir($dh)) !== false) )  {
		        	if ($file != '.' && $file != '..') {
		        		$templates[] = $file;
		        	}
		        }
		        closedir($dh);
		    }
		}
		return $templates;
	}

	function get_custom_templates ($args = array()) {
		extract(wp_parse_args($args, array('type' => 'content')));
		if ($type == 'content') {
			$templates = get_option(self::$content_template_name, array());
		} elseif ($type == 'excerpt') {
			$templates = get_option(self::$excerpt_template_name, array());
		}
		
	}

//end of class
}