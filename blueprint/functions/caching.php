<?php 

add_action('init', 'PLS_Options_Save_Flush');
add_action('switch_theme', array('PLS_Cache', 'invalidate'));

class PLS_Cache {

	const TTL_LOW  = 900; // 15 minutes
	const TTL_HIGH = 172800; // 48 hours

	public static $offset = 0;
	public $type = 'general';
	public $transient_id = false;

	function __construct ($type = 'general') {
		$this->offset = get_option('pls_cache_offset', 0);
		$this->type = $type;
	}

	function get () {
		
		// Just ignore caching for admins
		if(is_admin() || is_admin_bar_showing()) {
			return false;
		}

		$function_args = func_get_args();
		$arg_hash = rawToShortMD5(MD5_85_ALPHABET, md5(http_build_query($function_args), true));
		$this->transient_id = 'pl_' . $this->type . $this->offset . '_' . $arg_hash;
        $transient = get_transient($this->transient_id);
        if ($transient) {
        	return $transient;
        } else {
        	return false;
        }
	}

	public function save ($result, $duration = 172800) {
		// Just ignore caching for admins
		if ($this->transient_id && !is_admin() && !is_admin_bar_showing()) {
			set_transient($this->transient_id, $result , $duration);
		}
	}

	public function delete () {
		if ($this->transient_id) {
			delete_transient($this->transient_id);
		}
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

function PLS_Options_Save_Flush() {
	// Check if options are being saved
	if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == "/wp-admin/options.php") {

		// Flush the cache
		PLS_Cache::invalidate();

		// Prime the cache
		// PLS_Plugin_API::get_listings_list(array());
		// PLS_Plugin_API::get_listings_details_list(array());

	}
}



$pls_widget_cache = new PLS_Widget_Cache();
/**
 * Based loosely on WP Widget Cache
 * Automatically caches widgets per page and only for GET requests
 * @see http://wordpress.org/extend/plugins/wp-widget-cache/screenshots/
 */
class PLS_Widget_Cache {

	function __construct() {
		// WP Widget Cache ties into the wp_head hook, but Blueprint caches the page header
		// so wp_head might not get invoked. Hook into wp instead.
		add_action('wp', array(__CLASS__,'widget_cache_redirect_callback'), 99999);
	}

	function widget_cache_redirect_callback()
	{
		global $wp_registered_widgets;

		// For every widget on every registered sidebar...
		foreach ( $wp_registered_widgets as $id => $widget )
		{
			// Attach an id
			array_push($wp_registered_widgets[$id]['params'],$id);
			// Store the original callback so we can render it if needed
			$wp_registered_widgets[$id]['callback_wc_redirect']=$wp_registered_widgets[$id]['callback'];
			// Render our own callback so we're called to render it instead
			$wp_registered_widgets[$id]['callback']=array(__CLASS__, 'widget_cache_redirected_callback');
		}
	}

	function widget_cache_redirected_callback()
	{
		global $wp_registered_widgets;

		$params=func_get_args();											// get all the passed params
		$id=array_pop($params);												// take off the widget ID
		$params['widget_class'] = __CLASS__;
		$params['cache_url'] = $_SERVER['REQUEST_URI']; // Cache per page

		$cache = new PLS_Cache('Widget');
		if('GET' === $_SERVER['REQUEST_METHOD'] && WP_DEBUG !== true && $html = $cache->get($params)) {
			// Cache hit. Return the html.
			echo $html;
			return;

		}
		else {

			// Cache miss. Render the HTML.
			$callback=$wp_registered_widgets[$id]['callback_wc_redirect'];		// find the real callback

			// Just in case the callback isn't callable...
			if(!is_callable($callback)) {
				return;
			}

			// Let the widget render itself into an output buffer
			// Cache it & echo the rendered html
			ob_start();
			call_user_func_array($callback, $params);
			$html = ob_get_clean();
			$cache->save($html, PLS_Cache::TTL_LOW);
			echo $html;

		}
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
