<?php 

PLS_Route::init();

class PLS_Route {

	static $request = array();
	const CACHE_NONE = 0;
	const CACHE_PER_PAGE = 1;

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 */
	static $base;

	// hooks take care of everything, developer has full control over
	// file system.
	function init()
	{
		

		if (current_theme_supports('pls-routing-util-templates') )  {
			// hooks into template_routing for auto wrapping header
			// and footer.
			 add_filter( 'template_include', array( __CLASS__, 'routing_logic' ) );

			
			// hook into each classification, so we can store the request locally. 
			add_action( '404_template', array( __CLASS__, 'handle_404'  ));
			add_action( 'search_template', array( __CLASS__, 'handle_search'  ));
			add_action( 'home_template', array( __CLASS__, 'handle_home'  ));	
			add_action( 'front_page_template', array( __CLASS__, 'handle_front_page'  ));	
			add_action( 'paged_template', array( __CLASS__, 'handle_paged'  ));	
			add_action( 'attachment_template', array( __CLASS__, 'handle_attachment'  ));	
			add_action( 'taxonomy_template', array( __CLASS__, 'handle_taxonomy'  ));	
			add_action( 'archive_template', array( __CLASS__, 'handle_archive'  ));	
			add_action( 'date_template', array( __CLASS__, 'handle_date'  ));	
			add_action( 'tag_template', array( __CLASS__, 'handle_tag'  ));	
			add_action( 'author_template', array( __CLASS__, 'handle_author'  ));	
			add_action( 'single_template', array( __CLASS__, 'handle_single'  ));	
			add_action( 'page_template', array( __CLASS__, 'handle_page'  ));	
			add_action( 'category_template', array( __CLASS__, 'handle_category'  ));	
			add_action( 'comments_popup_template', array( __CLASS__, 'handle_popup_comments'  ));	
			add_action( 'comments_template', array( __CLASS__, 'handle_comments'  ));
		}
	}

	function routing_logic ($template)
	{
		
		// debug messages;
		PLS_Debug::add_msg("routing_logic used.....");
		PLS_Debug::add_msg("We've recorded the request as: " . self::$request);
		PLS_Debug::add_msg('Wordpress wants:' . $template);
		
		$new_template = '';

		// if wrapping is true. TODO: Make wrapping optional.
		if (true) {
			// fire wrapper
			self::wrapper();
			// if wrapper is used, it will handle the proper
			// loading. returning blank will clear the filter
			// causing no additional pages to be included. 
			
		} else {
			// check the request var to see what template is being
			// requested, load that template if theme, child, or blueprint
			// has it. Handle dynamic does this naturally. 

			$new_template = self::handle_dynamic();
		}		
		
		return $new_template;
	}


	// checks for a user defined file, if not present returns the required blueprint template.

	// direct copy + paste of WP's locate function
	// modified to alternate searching for the dev's
	// templates, then look for blueprints.
	function router ($template_names, $load = false, $require_once = true, $include_vars = false, $cache_type = self::CACHE_NONE) {

		PLS_Debug::add_msg('[[Hit Router!]] Searching for: ');
		PLS_Debug::add_msg($template_names);

		$located = self::locate_blueprint_template($template_names);

		PLS_Debug::add_msg('Template Located: ' . $located);

		if ( $load && '' != $located  ) {
			PLS_Debug::add_msg('[[Load Requested:]] ' . $located);

			// Capture/cache rendered html unless we're in debug mode
			if((WP_DEBUG !== true) && self::CACHE_PER_PAGE === $cache_type) {        
				$cache = new PLS_Cache('Template');
				$cache_args = array('template' => $located, 'uri' => $_SERVER[REQUEST_URI]);
				if ($result = $cache->get($cache_args)) {
					PLS_Debug::add_msg('[[Router cache hit!]] Returning rendered HTML for : ' . $located);
					echo $result;
					return $located;
				}
				ob_start();
			}

			load_template( $located, $require_once);

			// Capture/cache rendered html unless we're in debug mode
			if((WP_DEBUG !== true) && self::CACHE_PER_PAGE === $cache_type) {
				$result = ob_get_clean();
				$cache->save($result);
				echo $result;
			}

		} elseif ($include_vars) {
			ob_start();
				extract($include_vars);
				load_template( $located, $require_once);
			echo ob_get_clean();
		}
			
		return $located;
		
	}

	// determines which file to load
	// broken off from router so it can be reused. 
	function locate_blueprint_template ($template_names)
	{
		$located = '';

		// Cache template locations
		if((WP_DEBUG !== true)) {	
			$cache = new PLS_Cache('Located Template');
			$cache_args = array('template_names' => $template_names);
			if ($located = $cache->get($cache_args)) {
				PLS_Debug::add_msg('[[Template location cache hit!]] Returning cached location : ' . $located);
				return $located;
			}
		}

		foreach ( (array) $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists(get_stylesheet_directory() . '/' . $template_name)) {
				$located = get_stylesheet_directory() . '/' . $template_name;
				break;
			} else if ( file_exists(PLS_TPL_DIR . '/' . $template_name) ) {
				$located = PLS_TPL_DIR . '/' . $template_name;
				break;
			}
		}

		// Cache template locations	
		if((WP_DEBUG !== true)) {
			$cache->save($located);	
		}
		return $located;
	}

		// determines which file to load
	// broken off from router so it can be reused. 
	function locate_blueprint_option ($template_names)
	{
		$located = '';

		foreach ( (array) $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists(trailingslashit(get_stylesheet_directory()) . 'options/' . $template_name)) {
				$located = trailingslashit(get_stylesheet_directory()) . 'options/' . $template_name;
				break;
			} else if ( file_exists(trailingslashit( PLS_OP_DIR ) . $template_name) ) {
				$located = trailingslashit( PLS_OP_DIR ) . $template_name;
				break;
			}
		}
		return $located;
	}
	
	// displays the html of a given page. 
	//
	// needs to be updated so it can be safely overwritten 
	// by dropping in a properly named file into the theme root. 
	function get_template_part($slug, $name = null)
	{
		PLS_Debug::add_msg('Get Template Requested for: ' . $slug . ' and ' . $name);
		do_action( "get_template_part_{$slug}", $slug, $name );

		$templates = array();
		if ( isset($name) )
			$templates[] = "{$slug}-{$name}.php";

		$templates[] = "{$slug}.php";

		self::router($templates, true, false);
	}


	//
	//	Public utility functions that can be used to intellegently
	//	request the correct template. These will naturally use the
	// 	the router which respects templates from the theme, and 
	// 	child theme before falling back to blueprint
	//

	function handle_dynamic() {
		return self::router(self::$request, true, null, null, self::CACHE_PER_PAGE);
	}

	function handle_header() {
		// Header is loaded directly rather then
		// being set as a request and then looping
		// the routing table.
		//
		return self::router('header.php', true, null, null, self::CACHE_PER_PAGE);
	}

	function handle_sidebar() {
		// Sidebar is loaded directly rather then
		// being set as a request and then looping
		// the routing table.
		//

        $sidebars = array();
        foreach ((array)self::$request as $item) {
            $sidebars[] = 'sidebar-' . $item;
        }
        $sidebars[] = 'sidebar.php';
		return self::router($sidebars, true, null, null, self::CACHE_PER_PAGE);
	}

	function handle_default_sidebar() {
		// Sidebar is loaded directly rather then
		// being set as a request and then looping
		// the routing table.
        $sidebars = array();
        foreach ( (array) self::$request as $item) {
            $sidebars[] = 'default-sidebar-' . $item;
        }
        $sidebars[] = 'default-sidebar.php';
		return self::router($sidebars, true, null, null, self::CACHE_PER_PAGE);
	}

	function handle_footer() {
		// Footer is loaded directly rather then
		// being set as a request and then looping
		// the routing table.
		//
		return self::router('footer.php', true, null, null, self::CACHE_PER_PAGE);
	}

	// 
	//	Hooked functions, likely not a good idea to mess
	//	around down here. 
	//

	// hooked to handle comments templates
	function handle_comments() {
		return self::router('comments.php');
	}

	// hooked to handle comments templates
	function handle_popup_comments() {
		return self::router('popup_comments.php');	
	}

	// hooked to 404
	function handle_404() {
		
		// sets the request for the standard 404
		// template
		self::$request[] = '404.php';
	}

	// hooked to search
	function handle_search() {
		
		// sets the request for the standard search
		// template
		self::$request[] = 'search.php';
	}

	// hooked to home + index
	function handle_home() {

		//check for index.php, same hook as home.
		self::$request = array_merge(self::$request, array( 'home.php', 'index.php' ));		
	}

	// hooked to front-page.php
	function handle_front_page () {
		self::$request[] = 'front-page.php';
	}

	function handle_paged() {
		self::$request[] = 'paged.php';
	}

	function handle_date() {
		self::$request[] = 'date.php';
	}

	// needs additional logic to handle different types of 
	// post type archives. 
	function handle_archive($templates) {

		$post_type = get_query_var( 'post_type' );

		$templates = array();

		if ( $post_type ) {
			$templates[] = "archive-{$post_type}.php";
		}
			
		$templates[] = 'archive.php';

		self::$request = array_merge(self::$request, $templates);
	}


	// for author pages
	function handle_author() {
		$author = get_queried_object();

		$templates = array();

		$templates[] = "author-{$author->user_nicename}.php";
		$templates[] = "author-{$author->ID}.php";
		$templates[] = 'author.php';

		self::$request = array_merge(self::$request, $templates);
	}


	// hooked to handle page templates
	function handle_category($templates) {
		$category = get_queried_object();

		$templates = array();

		$templates[] = "category-{$category->slug}.php";
		$templates[] = "category-{$category->term_id}.php";
		$templates[] = 'category.php';

		self::$request = array_merge(self::$request, $templates);
	}


	// for tag tempaltes
	function handle_tag() {

		$tag = get_queried_object();

		$templates = array();

		$templates[] = "tag-{$tag->slug}.php";
		$templates[] = "tag-{$tag->term_id}.php";
		$templates[] = 'tag.php';

		self::$request = array_merge(self::$request, $templates);
	}

	// attachment pages, not sure what to do with this.
	// needs some additional logic so blueprint can handle
	// all the different template types
	function handle_attachment() {
		
		global $posts;
		
		$type = explode('/', $posts[0]->post_mime_type);
		if ( $template = get_query_template($type[0]) )
			return $template;
		elseif ( $template = get_query_template($type[1]) )
			return $template;
		elseif ( $template = get_query_template("$type[0]_$type[1]") )
			return $template;
		else

		self::$request =  array_merge(self::$request, $templates);
	}

	// hooked to handle single templates
	function handle_single() {

		$object = get_queried_object();

		$templates = array();

		$templates[] = "single-{$object->post_type}.php";
		$templates[] = "single.php";
		
		self::$request =  array_merge(self::$request, $templates);
	}

	// hooked to handle page templates
	function handle_page($template) {
		
		// this is a direct copy and paste from WP.
		// becuase wordpress isn't verbose about what it
		// discovers it does this reuqest. We'll need
		// to duplicate it. 
		$id = get_queried_object_id();
		$template = get_post_meta($id, '_wp_page_template', true);
		$pagename = get_query_var('pagename');

		if ( !$pagename && $id > 0 ) {
			// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
			$post = get_queried_object();
			$pagename = $post->post_name;
		}

		if ( 'default' == $template )
			$template = '';
		
		PLS_Debug::add_msg("Of type Page, searching for:");
		PLS_Debug::add_msg($template);
		
		$templates = array();
		if ( !empty($template) && !validate_file($template) )
			$templates[] = $template;
		if ( $pagename )
			$templates[] = "page-$pagename.php";
		if ( $id )
			$templates[] = "page-$id.php";
		$templates[] = 'page.php';

		// The possible templates are stored in the 
		// request var so router can use them later
		// when the filter is called to decide
		// which pages to look for. 
		self::$request =  array_merge(self::$request, $templates);
	}

	function handle_taxonomy() {
		global $query_string;
		$args = wp_parse_args($query_string, array('state' => false, 'city' => false, 'neighborhood' => false, 'zip' => false, 'street' => false, 'mlsid' => false));
		extract($args);

		$templates = array();

		if ($state || $city || $zip || $neighborhood || $street || $mlsid) {
			if ($street) {
				$templates[] = 'attribute-street.php';
			} elseif ($neighborhood) {
				$templates[] = 'attribute-neighborhood.php';
			} elseif ($zip) {
				$templates[] = 'attribute-zip.php';
			} elseif ($city) {
				$templates[] = 'attribute-city.php';
			} elseif ($state) {
				$templates[] = 'attribute-state.php';
			} elseif ($mlsid) {
				$templates[] = 'attribute-mlsid.php';
			}
			$templates[] = 'attribute.php';
			self::$request =  array_merge(self::$request, $templates);
		} else {
			$term = get_queried_object();
			$taxonomy = $term->taxonomy;

			

			$templates[] = "taxonomy-$taxonomy-{$term->slug}.php";
			$templates[] = "taxonomy-$taxonomy.php";
			$templates[] = 'taxonomy.php';

		}

		self::$request =  array_merge(self::$request, $templates);
	}




	/**
	 * A class that adds theme wrapping functionality
	 *
	 * This allows theme developers to avoid code repetition by adding the common 
	 * surrounding code from templates to a wrapper.php file.
	 *
	 * Based on the ideas of http://scribu.net/wordpress/theme-wrappers.html
	 * Modified for Blueprints needs.
	 *
	 */
	static function wrapper() {
			
		PLS_Debug::add_msg('Wrapper used..');

		$base = '';
		$templates = array();

		// we need to construct a list of wrapper files
		// we're looking for. The basic construction is:
		// Looks for wrapper-[base].php and then 
		// for blueprint/wrappers/wrapper-[base].php
		// 
		// This is done for situations when we can have multiple
		// templates used for the same file. Like pages, archives,
		// etc..
		foreach ( (array) self::$request as $variation) {
			$base = substr( basename( $variation), 0, -4 );	
			$templates[] = sprintf( 'wrapper-%s', $variation );
		}
		
		$templates[] = 'wrapper.php';
		
		// if wrapper is being used, it will load attempt
		// to load the various wrapper iterations.
		// wrapper needs to have PLS_Route::handle_dynamic to 
		// actually load the requested page after wrapper is 
		// loaded. 
		return self::router( $templates, true );
	}



// end class	
}

 ?>