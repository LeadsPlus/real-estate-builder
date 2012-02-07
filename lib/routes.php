<?php 

class PL_Router {

	private static function router($template, $params, $wrap = false) {
		
		ob_start();
			extract($params);
			self::load_builder_view('header.php');
			self::load_builder_view($template);
			self::load_builder_view('footer.php');
		echo ob_get_clean();

	}

	private static function load_builder_view($template) {
		include_once(trailingslashit(PL_VIEWS_ADMIN_DIR) . $template);
	}
	
	public function my_listings() {

		self:: router('my-listings.php', array('test'=>'donkey'), false);
	}

	public function header() {
		
	}

	public function footer() {
		
	}

//end of class
}