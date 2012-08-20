<?php 

PL_Cache::init();
class PL_Cache {

	const TTL_LOW  = 900; // 15 minutes
	const TTL_HIGH = 172800; // 48 hours

	public static $offset = 0;
	public $type = 'general';
	public $transient_id = false;

	function __construct ($type = 'general') {
		$this->offset = get_option('pls_cache_offset', 0);
		$this->type = $type;
	}

	public static function init () {

		// Allow cache to be cleared by going to url like http://example.com/?clear_cache
		if(isset($_GET['clear_cache']) || isset($_POST['clear_cache'])) {
			// style-util.php calls its PLS_Style::init() immediately
			// so this can't be tied to a hook
			self::invalidate();
		}

		add_action('wp_ajax_user_empty_cache', array(__CLASS__, 'clear' ) );
		add_action('switch_theme', array(__CLASS__, 'invalidate'));

	}

	function get () {

		// Just ignore caching for admins
		if(is_admin() || is_admin_bar_showing()) {
			return false;
		}

		// Backdoor to ignore the cache completely
		if(isset($_GET['no_cache']) || isset($_POST['no_cache'])) {
			return false;
		}
	
		$func_args = func_get_args();
		$arg_hash = rawToShortMD5(MD5_85_ALPHABET, md5(http_build_query( $func_args ), true));
		$this->transient_id = 'pl_' . $this->type . $this->offset . '_' . $arg_hash;
        $transient = get_transient($this->transient_id);
        if ($transient) {
        	return $transient;
        } else {
        	return false;
        }
	}

	public function save ($result, $duration = 172800) {
		// Don't save any content from logged in users
		// We were getting things like "log out" links cached
		if ($this->transient_id && !is_user_logged_in()) {
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

	public static function invalidate() {
		$cache = new self();
		$cache->offset += 1;
		if($cache->offset > 99) {
			$cache->offset = 0;
		}
		update_option('pls_cache_offset', $cache->offset);
	}

//end class
}

// Flush our cache when admins save option pages or configure widgets
add_action('init', 'PL_Options_Save_Flush');
function PL_Options_Save_Flush() {
	// Check if options are being saved
	$doing_ajax = ( defined('DOING_AJAX') && DOING_AJAX );
	$editing_widgets = ( isset($_GET['savewidgets']) || isset($_POST['savewidgets']));
	if($_SERVER['REQUEST_METHOD'] == 'POST' && is_admin() && (!$doing_ajax || $editing_widgets)) {

		// Flush the cache
		PL_Cache::invalidate();

	}
}

/* Functions for converting between notations and short MD5 generation.
 * No license (public domain) but backlink is always welcome :)
 * By Proger_XP. http://proger.i-forge.net/Short_MD5
 * Usage: rawToShortMD5(MD5_85_ALPHABET, md5($str, true))
 * (passing true as the 2nd param to md5 returns raw binary, not a hex-encoded 32-char string)
 */
define('MD5_24_ALPHABET', '0123456789abcdefghijklmnopqrstuvwxyzABCDE');
define('MD5_85_ALPHABET', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$%^&*()"|;:?\/\'[]<>');

function RawToShortMD5($alphabet, $raw) {
  $result = '';
  $length = strlen(DecToBase($alphabet, 2147483647));

  foreach (str_split($raw, 4) as $dword) {
    $dword = ord($dword[0]) + ord($dword[1]) * 256 + ord($dword[2]) * 65536 + ord($dword[3]) * 16777216;
    $result .= str_pad(DecToBase($alphabet, $dword), $length, $alphabet[0], STR_PAD_LEFT);
  }

  return $result;
}

function DecToBase(&$alphabet, $dword) {
  $rem = fmod($dword, strlen($alphabet));
  if ($dword < strlen($alphabet)) {
    return $alphabet[$rem];
  } else {
    return DecToBase($alphabet, ($dword - $rem) / strlen($alphabet)).$alphabet[$rem];
  }
}
