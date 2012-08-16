<?php 

class PLS_Partial_Get_Listings {
    /**
     * Returns a list of properties listed formated in a default html.
     *
     * This function takes the raw properties data returned by the plugin and
     * formats wrapps it in html. The returned html is filterable in multiple
     * ways.
     *
     * The defaults are as follows:
     *     'width' - Default 100. The listing image width. If set to 0,
     *          width is not added.
     *     'height' - Default false. The listing image height. If set to 0,
     *          width is not added.
     *     'placeholder_img' - Defaults to placeholder image. The path to the
     *          listing image that should be use if the listing has no images.
     *     'context' - An execution context for the function. Used when the
     *          filters are created.
     *     'context_var' - Any variable that needs to be passed to the filters
     *          when function is executed.
     *     'limit' - Default is 5. Total number of listings to retrieve. Maximum
     *          set to 50.
     * Defines the following filters:
     * pls_listings_request[_context] - Filters the request parameters.
     * pls_listing[_context] - Filters the individual listing html.
     * pls_listings[_context] - Filters the complete listings list html.
     *
     * @static
     * @param array|string $args Optional. Overrides defaults.
     * @return string The html with the list of properties.
     * @since 0.0.1
     */
    function init ($args = '') {

        $cache = new PLS_Cache('list');
        if ($result = $cache->get($args)) {
          return $result;
        }

        /** Define the default argument array. */
        $defaults = array(
            'width' => 100,
            'height' => 0,
            'placeholder_img' => PLS_IMG_URL . "/null/listing-300x180.jpg",
            'context' => '',
            'context_var' => false,
            'featured_option_id' => false,
            /** Placester API arguments. */
            'limit' => 5,
            'sort_type' => 'asc',
            'request_params' => '',
            'neighborhood_polygons' => false
        );

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        /** Extract the arguments after they merged with the defaults. */
        extract( $args, EXTR_SKIP );
        
        /** Sanitize the width. */
        if ( $width ) 
            $width = absint( $width );
            
        /** Sanitize the height. */
        if ( $height ) 
            $height = absint( $height );

        $request_params = wp_parse_args($request_params, array('limit' => $limit, 'sort_type' => $sort_type));

        /** Filter the request parameters. */
        $request_params = apply_filters( pls_get_merged_strings( array( 'pls_listings_request', $context ), '_', 'pre', false ), $request_params, $context_var );
        /** Display a placeholder if the plugin is not active or there is no API key. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) ) {
            global $PLS_API_DEFAULT_LISTING;
            $listings_raw = $PLS_API_DEFAULT_LISTING;
        } elseif (pls_has_plugin_error()) {
            global $PLS_API_DEFAULT_LISTING;
            $listings_raw = $PLS_API_DEFAULT_LISTING;
        } else {

            /** Get the listings list markup and javascript. */
              if ($featured_option_id) {
                $listings_raw = PLS_Listing_Helper::get_featured($featured_option_id);
              } elseif ($neighborhood_polygons) {
                $listings_raw = PLS_Plugin_API::get_polygon_listings( array('neighborhood_polygons' => $neighborhood_polygons ) );
              } else {
                $listings_raw = PLS_Plugin_API::get_listings_list($request_params);
              }
        }
      
        /** Define variable which will contain the html string with the listings. */
        $return = '';

        /** Set the listing image attributes. */
        $listing_img_attr = array();
        if ( $width )
            $listing_img_attr['width'] = $width;
        if ( $height )
            $listing_img_attr['height'] = $height;

        /** Collect the html for each listing. */

        $listings_html = array();

        if(WP_DEBUG !== true) {
          $listing_cache = new PLS_Cache('Listing');
        }

        foreach ( $listings_raw['listings'] as $listing_data ) {
            // pls_dump($listing_data);
            /**
             * Curate the listing_data.
             */

            /** Overwrite the placester url with the local url. */
            // $listing_data->url = PLS_Plugin_API::get_property_url( $listing_data->id );

            $listing_html = '';


            if(WP_DEBUG !== true) {
              $cache_id = array('context' => $context, 'featured_option_id' => $featured_option_id, 'listing_id' => $listing_data['id']);
              if($cached_listing_html = $listing_cache->get($cache_id)) {
                $listing_html = $cached_listing_html;
              }
            }

            if(empty($listing_html)) {

              /** Use the placeholder image if the property has no photo. */
              if ( !$listing_data['images'] ) {
                  $listing_data['images'][0]['url'] = $placeholder_img;
                  $listing_data['images'][0]['order'] = 0;
              }

              /** Remove the ID for each image (not needed by theme developers) and add the image html. */
              foreach ( $listing_data['images'] as $image ) {
                  unset( $image['id'] );
                  $image['html'] = pls_h_img( $image['url'], $listing_data['location']['address'], $listing_img_attr );
              }
                  $location = $listing_data['location'];
                  $full_address = $location['address'] . ' ' . $location['region'] . ', ' . $location['locality'] . ' ' . $location['postal'];
               ob_start();
               ?>


<div class="listing-item grid_8 alpha" itemscope itemtype="http://schema.org/Offer">

  <div class="listing-thumbnail grid_3 alpha">
    <a href="<?php echo @$listing_data['cur_data']['url']; ?>">
      <?php echo PLS_Image::load($listing_data['images'][0]['url'], array('resize' => array('w' => 210, 'h' => 140), 'fancybox' => true, 'as_html' => true, 'html' => array('alt' => $listing_data['location']['full_address'], 'itemprop' => 'image'))); ?>
    </a>
  </div>

  <div class="listing-item-details grid_5 omega">
    <p class="listing-item-address h4" itemprop="name">
      <a href="<?php echo PLS_Plugin_API::get_property_url($listing_data['id']); ?>" rel="bookmark" title="<?php echo $listing_data['location']['address'] ?>" itemprop="url">
        <?php echo $listing_data['location']['address'] . ', ' . $listing_data['location']['locality'] . ' ' . $listing_data['location']['region'] . ' ' . $listing_data['location']['postal']  ?>
      </a>
    </p>

    <div class="basic-details">
      <ul>
        <?php if (!empty($listing_data['cur_data']['beds'])) { ?>
          <li class="basic-details-beds p1"><span>Beds:</span> <?php echo @$listing_data['cur_data']['beds']; ?></li>
        <?php } ?>

        <?php if (!empty($listing_data['cur_data']['baths'])) { ?>
          <li class="basic-details-baths p1"><span>Baths:</span> <?php echo @$listing_data['cur_data']['baths']; ?></li>
        <?php } ?>

        <?php if (!empty($listing_data['cur_data']['half_baths'])) { ?>
          <li class="basic-details-half-baths p1"><span>Half Baths:</span> <?php echo @$listing_data['cur_data']['half_baths']; ?></li>
        <?php } ?>

        <?php if (!empty($listing_data['cur_data']['price'])) { ?>
          <li class="basic-details-price p1" itemprop="price"><span>Price:</span> <?php echo PLS_Format::number($listing_data['cur_data']['price'], array('abbreviate' => false, 'add_currency_sign' => true)); ?></li>
        <?php } ?>

        <?php if (!empty($listing_data['cur_data']['avail_on'])) { ?>
          <li class="basic-details-sqft p1"><span>Sqft:</span> <?php echo PLS_Format::number($listing_data['cur_data']['sqft'], array('abbreviate' => false, 'add_currency_sign' => false)); ?></li>
        <?php } ?>

        <?php if (!empty($listing_data['rets']['mls_id'])) { ?>
          <li class="basic-details-mls p1"><span>MLS ID:</span> <?php echo @$listing_data['rets']['mls_id']; ?></li>
        <?php } ?>
      </ul>
    </div>

    <p class="listing-description p4">
      <?php echo substr($listing_data['cur_data']['desc'], 0, 300); ?>
    </p>

  </div>

  <div class="actions">
    <a class="more-link" href="<?php echo PLS_Plugin_API::get_property_url($listing_data['id']); ?>" itemprop="url">View Property Details</a>
    <?php echo PLS_Plugin_API::placester_favorite_link_toggle(array('property_id' => $listing_data['id'])); ?>
  </div>

  <?php PLS_Listing_Helper::get_compliance(array('context' => 'inline_search', 'agent_name' => @$listing_data['rets']['aname'] , 'office_name' => @$listing_data['rets']['oname'])); ?>

</div>

<?php
        $listing_html = ob_get_clean();

        /** Filter (pls_listing[_context]) the resulting html for a single listing. */
        $listing_html = apply_filters( pls_get_merged_strings( array( 'pls_listing', $context ), '_', 'pre', false ), $listing_html, $listing_data, $request_params, $context_var );

        if(WP_DEBUG !== true) {
          $listing_cache->save($listing_html, PLS_Cache::TTL_LOW);
        }
      }
      
      /** Append the html to an array. This will be passed to the final filter. */
      $listings_html[] = $listing_html;

      /** Merge all the listings html. */
      $return .= $listing_html;

    }

    /** Wrap the listings html. */
    $return = pls_h(
      'section',
      array( 'class' => "pls-listings pls-listings " . pls_get_merged_strings( array( 'pls-listing', $context ), '-', 'pre', false ) ),
      $return
    );

    /** Filter (pls_listings[_context]) the resulting html that contains the collection of listings.  */
    $return = apply_filters( pls_get_merged_strings( array( 'pls_listings', $context ), '_', 'pre', false ), $return, $listings_raw, $listings_html, $request_params, $context_var );
    $cache->save($return);  
    return $return;
  }


}
