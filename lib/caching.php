<?php 

PL_Cache::init();
class PL_Cache {

	public $type = 'general';
	public $transient_id = false;

	function __construct ($type = 'general') {
		$this->$type = $type;
	}

	public static function init () {
		add_action('wp_ajax_user_empty_cache', array(__CLASS__, 'clear' ) );
	}

	function get () {
		$arg_hash = md5(http_build_query(func_get_args()));
		$this->transient_id = 'pl_' . $this->type . '_' . $arg_hash;
        $transient = get_transient($this->transient_id);
        if ($transient) {
        	return $transient;
        } else {
        	return false;
        }
	}

	public function save ($result, $duration = 172800) {
		if ($this->transient_id) {
			set_transient($this->transient_id, $result , $duration);
		}
	}

	public static function items() {
		global $wpdb;
	    $placester_options = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'options ' ."WHERE option_name LIKE '_transient_pl_%'", ARRAY_A);		
	    if ($placester_options && is_array($placester_options)) {
	    	return $placester_options;
	    } else {
	    	return false;
	    }
	}

	public static function clear() {
		global $wpdb;
	    $placester_options = $wpdb->get_results('SELECT option_name FROM ' . $wpdb->prefix . 'options ' ."WHERE option_name LIKE '_transient_pl_%'");
	    foreach ($placester_options as $option) {
	        delete_option( $option->option_name );
	    }
		echo json_encode(array('result' => true, 'message' => 'You\'ve successfully cleared your cache'));
		die();
	}

	public static function delete($option_name) {
		$option_name = str_replace('_transient_', '', $option_name);
		$result = delete_transient( $option_name );
		return $result;
	}

//end class
}