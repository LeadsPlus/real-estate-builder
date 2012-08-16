<?php 
PLS_Partials_Get_Listings_Ajax::init();
class PLS_Partials_Get_Listings_Ajax {

	/**
     * Returns the list of listings managed by ajax. It includes pagination and 
     * 'sort by' controls.
     * 
     * The defaults are as follows:
     *     'placeholder_img' - Defaults to placeholder image. The path to the 
     *          listing image that should be use if the listing has no images.
     *     'loading_img' - Defaults to the Wordpress spinner. Path to the 
     *          loader image.
     *     'image_width' - Defaults to 100. The with of the listing image.
     *     'crop_description' - Defaults to false. Wether the description 
     *     should be cropped or not.
     *     'context' - An execution context for the function. Used when the 
     *          filters are created.
     *     'context_var' - Any variable that needs to be passed to the filters 
     *          when function is executed.
     * Defines the following hooks:
     *      pls_listings_list_ajax_item_html[_context] - Filters html for each 
     *          item in the list
     *      pls_listings_list_ajax_no_results_html[_context] - Filters what 
     *          should be displayed when no results are found.
     *      pls_listings_list_ajax_html[_context] - Filters the html for the 
     *          whole list.
     *      pls_listings_list_ajax_sort_by_options[_context] - Filters the 
     *          options from the "Sort by" select box.
     *
     * @static
     * @param array $args Optional. Overrides defaults.
     * @return string The html and js.
     * @since 0.0.1
     */
    function init() {
        // Hook the callback for ajax requests
        add_action('wp_ajax_pls_listings_ajax', array(__CLASS__, 'get' ) );
        add_action('wp_ajax_nopriv_pls_listings_ajax', array(__CLASS__, 'get' ) );

        add_action( 'wp_ajax_pls_listings_fav_ajax', array(__CLASS__,'get_favorites'));
        add_action( 'wp_ajax_nopriv_pls_listings_fav_ajax', array(__CLASS__,'get_favorites'));
    }

    function get_favorites () {
      $favorite_ids = PLS_Plugin_API::get_listings_fav_ids();
      self::get(array('property_ids' => $favorite_ids, 'allow_id_empty' => true));
    }

    function load($args = array()) {    
        // * Set the options for the "Sort by" select. 
        $defaults = array(
            'placeholder_img' => PLS_IMG_URL . "/null/listing-300x180.jpg",
            'loading_img' => admin_url( 'images/wpspin_light.gif' ),
            'image_width' => 100,
            'sort_type' => 'desc',
            'crop_description' => 0,
            'listings_per_page' => get_option( 'posts_per_page' ),
            'context' => '',
            'context_var' => NULL,
            'append_to_map' => true,
            'search_query' => $_POST,
            'table_id' => 'placester_listings_list',
            'show_sort' => true,
            'search_class' => 'pls_search_form_listings'
        );

        /** Extract the arguments after they merged with the defaults. */
        $args = wp_parse_args( $args, $defaults );
        extract( $args, EXTR_SKIP );
        
        $sort_by_options = array('images' => 'Images','location.address' => 'Address', 'location.locality' => 'City', 'location.region' => 'State', 'location.postal' => 'Zip', 'zoning_types' => 'Zoning', 'purchase_types' => 'Purchase Type', 'listing_types' => 'Listing Type', 'property_type' => 'Property Type', 'cur_data.beds' => 'Beds', 'cur_data.baths' => 'Baths', 'cur_data.price' => 'Price', 'cur_data.sqft' => 'Square Feet', 'cur_data.avail_on' => 'Date Available');;

        // /** Filter the "Sort by" options. */
        $sort_by_options = apply_filters("pls_listings_list_ajax_sort_by_options", $sort_by_options);

        ob_start();
        ?>
            <!-- Sort Dropdown -->
            <?php if ($show_sort): ?>
              <form class="sort_wrapper">
                  <div class="sort_item">
                    <label for="sort_by">Sort By</label>
                    <select name="sort_by" id="sort_by">
                        <?php foreach ($sort_by_options as $key => $value): ?>
                            <option value="<?php echo $key ?>"><?php echo $value ?></option>
                        <?php endforeach ?>
                    </select>
                  </div>
                  <div class="sort_item">
                    <label for="sort_by">Sort Direction</label>
                    <select name="sort_type" id="sort_dir">
                        <option value="desc">Descending</option>
                        <option value="asc">Ascending</option>
                    </select>
                  </div>
              </form>  
            <?php endif ?>
            

              <!-- Datatable -->
              <div class="clear"></div>

              <div id="container" style="width: 99%">
                <div id="context" class="<?php echo $context ?>"></div>
                <table id="<?php echo $table_id ?>" class="widefat post fixed placester_properties" cellspacing="0">
                  <thead>
                    <tr>
                      <th><span></span></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
          <?php
          extract($args);
          include(trailingslashit( PLS_JS_DIR ) . 'scripts/get-listings-ajax.php');
        echo ob_get_clean();
    }

	function get ($args = array()) {
	
		    // Pagination
        $_POST['limit'] = $_POST['iDisplayLength'];
        $_POST['offset'] = $_POST['iDisplayStart'];     

        /** Define the default argument array. */
        $defaults = array(
            'placeholder_img' => PLS_IMG_URL . "/null/listing-300x180.jpg",
            'loading_img' => admin_url( 'images/wpspin_light.gif' ),
            'image_width' => 100,
            'sort_type' => 'desc',
            'crop_description' => 0,
            'listings_per_page' => get_option( 'posts_per_page' ),
            'context' => $_POST['context'],
            'context_var' => NULL,
            'append_to_map' => true,
            'search_query' => $_POST,
            'property_ids' => array(),
            'allow_id_empty' => false
        );
        
        if (isset($defaults['search_query']['sEcho'])) {
          unset($defaults['search_query']['sEcho']);
        }

        $cache = new PLS_Cache('list');
        if ($transient = $cache->get($defaults)) {
            $transient['sEcho'] = $_POST['sEcho'];
            echo json_encode($transient);
            die();
        }

        /** Extract the arguments after they merged with the defaults. */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

        /** Display a placeholder if the plugin is not active or there is no API key. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) ) {
            global $PLS_API_DEFAULT_LISTING;
            $api_response = $PLS_API_DEFAULT_LISTING;
        } elseif (pls_has_plugin_error()) {
            global $PLS_API_DEFAULT_LISTING;
            $api_response = $PLS_API_DEFAULT_LISTING;
        } else {
            /** Get the listings list markup and javascript. */
            if (!empty($property_ids) || $allow_id_empty) {
              $api_response = PLS_Plugin_API::get_listings_details_list(array('property_ids' => $property_ids, 'limit' => $_POST['limit'], 'offset' => $_POST['offset']));
            } elseif (isset($search_query['neighborhood_polygons']) && !empty($search_query['neighborhood_polygons']) ) {
              $api_response = PLS_Plugin_API::get_polygon_listings( $search_query );
            } else {
              $api_response = PLS_Plugin_API::get_listings_list($search_query);
            }
        }

        $response = array();        
        
        // build response for datatables.js
        $listings = array();
        $listings_cache = new PLS_Cache('Listing Thumbnail');

        foreach ($api_response['listings'] as $key => $listing) {
          $cache_id = array('context' => $context, 'listing_id' => $listing['id']);
          if(!($item_html = $listings_cache->get($cache_id))) {
            if (empty($listing['images'])) {
                $listing['images'][0] = array('url' => $placeholder_img);
            }
            ob_start();
            // pls_dump($listing);
            ?>

<div class="listing-item grid_8 alpha" itemscope itemtype="http://schema.org/Offer">

  <div class="listing-thumbnail grid_3 alpha">
         <a href="<?php echo @$listing['cur_data']['url']; ?>" itemprop="url"><?php echo PLS_Image::load($listing['images'][0]['url'], array('resize' => array('w' => 210, 'h' => 140), 'fancybox' => true, 'as_html' => true, 'html' => array('alt' => $listing['location']['full_address'], 'itemprop' => 'image'))); ?></a>
  </div>

  <div class="listing-item-details grid_5 omega">
    <header>
      <p class="listing-item-address h4" itemprop="name">
        <a href="<?php echo PLS_Plugin_API::get_property_url($listing['id']); ?>" rel="bookmark" title="<?php echo $listing['location']['address'] ?>" itemprop="url">
          <?php echo $listing['location']['address'] . ', ' . $listing['location']['locality'] . ' ' . $listing['location']['region'] . ' ' . $listing['location']['postal']  ?>
        </a>
      </p>
    </header>

    <div class="basic-details">
      <ul>
      	<?php if (!empty($listing['cur_data']['beds'])) { ?>
      		<li class="basic-details-beds p1"><span>Beds:</span> <?php echo @$listing['cur_data']['beds']; ?></li>
      	<?php } ?>

      	<?php if (!empty($listing['cur_data']['baths'])) { ?>
      		<li class="basic-details-baths p1"><span>Baths:</span> <?php echo @$listing['cur_data']['baths']; ?></li>
      	<?php } ?>

      	<?php if (!empty($listing['cur_data']['half_baths'])) { ?>
      		<li class="basic-details-half-baths p1"><span>Half Baths:</span> <?php echo @$listing['cur_data']['half_baths']; ?></li>
      	<?php } ?>

      	<?php if (!empty($listing['cur_data']['price'])) { ?>
      		<li class="basic-details-price p1" itemprop="price"><span>Price:</span> <?php echo PLS_Format::number($listing['cur_data']['price'], array('abbreviate' => false, 'add_currency_sign' => true)); ?></li>
      	<?php } ?>

      	<?php if (!empty($listing['cur_data']['avail_on'])) { ?>
      		<li class="basic-details-sqft p1"><span>Sqft:</span> <?php echo PLS_Format::number($listing['cur_data']['sqft'], array('abbreviate' => false, 'add_currency_sign' => false)); ?></li>
      	<?php } ?>

        <?php if (!empty($listing['rets']['mls_id'])) { ?>
          <li class="basic-details-mls p1"><span>MLS ID:</span> <?php echo @$listing['rets']['mls_id']; ?></li>
        <?php } ?>
      </ul>
    </div>

    <p class="listing-description p4" itemprop="description">
      <?php echo substr($listing['cur_data']['desc'], 0, 300); ?>
    </p>

  </div>

  <div class="actions">
    <a class="more-link" href="<?php echo PLS_Plugin_API::get_property_url($listing['id']); ?>" itemprop="url">View Property Details</a>
    <?php echo PLS_Plugin_API::placester_favorite_link_toggle(array('property_id' => $listing['id'])); ?>
  </div>
    
  <?php PLS_Listing_Helper::get_compliance(array('context' => 'inline_search', 'agent_name' => @$listing['rets']['aname'] , 'office_name' => @$listing['rets']['oname'])); ?>

</div>
            <?php
              $item_html = ob_get_clean();
              $item_html = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_item_html", $context ), '_', 'pre', false ), htmlspecialchars_decode( $item_html ), $listing, $context_var);
              $listings_cache->save($item_html);
            }

            $listings[$key][] = $item_html;
            $listings[$key][] = $listing;
        }

        // Required for datatables.js to function properly.
        $response['sEcho'] = $_POST['sEcho'];
        $response['aaData'] = $listings;
        $response['iTotalRecords'] = $api_response['total'];
        $response['iTotalDisplayRecords'] = $api_response['total'];

        $cache->save($response);

        ob_start("ob_gzhandler");
        echo json_encode($response);

        // Enable W3TC Debugging
        // if(WP_DEBUG === true) {
        //   $db = w3_instance('W3_DbCache');        
        //   echo "\r\n\r\n".$db->_get_debug_info();
        //   $w3_objectcache = w3_instance('W3_ObjectCache');
        //   echo "\r\n\r\n".$w3_objectcache->_get_debug_info();
        // }

        //wordpress echos out a 0 randomly. die prevents it.
        die();
	}

}//end of class