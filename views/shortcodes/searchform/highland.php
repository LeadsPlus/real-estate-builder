<?php 

add_filter('pls_listings_search_form_outer_listings_search', 'custom_search_form_html', 10, 6);

function custom_search_form_html ($form, $form_html, $form_options, $section_title, $form_data) {
	// pls_dump($form_html);
	ob_start();
	?>
      <form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>listings" class="pls_search_form_listings">
        <div class="form-l">
          <h6>Location</h6>
          <label>City</label>
          <div class="slt-full">
            <?php echo $form_html['cities']; ?>
          </div>
          <label>State</label>
          <div class="slt-full">
            <?php echo $form_html['states'] ?>  
          </div>
          <label>Zip</label>
          <div class="slt-full">
            <?php echo $form_html['zips'] ?>  
          </div>
        </div><!--form-l-->
        <div class="form-m">
          <h6>Listing Type</h6>
          <label>Zoning Type</label>
          <div class="slt-full">
            <?php echo $form_html['zoning_types'] ?>
          </div>
          <label>Transaction Type</label>
          <div class="slt-full">
            <?php echo $form_html['purchase_types'] ?>
          </div>
          <label>Property Type</label>
          <div class="slt-full">
            <?php echo $form_html['property_type'] ?>
          </div>
        </div><!--form-m-->
        <div class="form-r">
          <div class="form-r-l">
            <h6>Details</h6>
            <label>Bed(s)</label>
            <div class="slt-sma">
              <?php echo $form_html['bedrooms'] ?>  
            </div>   	
            <label>Bath(s)</label>
            <div class="slt-sma">
              <?php echo $form_html['bathrooms'] ?>  
            </div>                  	              
          </div><!--form-r-l-->
          <div class="form-r-r">
            <h6>Price Range</h6>
            <label>Price From</label>
            <div class="slt-sma">
              <?php echo $form_html['min_price'] ?> 
            </div>
            <label>Price To</label>
            <div class="slt-sma">
              <?php echo $form_html['max_price'] ?> 
            </div>
          </div><!--form-r-r-->
          <div class="clr"></div>
          <!--<label>Available On</label>
          <div class="slt-full">
            <?php //echo $form_html['available_on'] ?> 
          </div>-->
        </div><!--form-r-->
        <div class="clearfix"></div>                                                       
        <input type="submit" name="submit" value="Search Now!" id="full-search">          
        <div class="clearfix"></div>
        </form>
	   
     <?php
	
    $search_form = trim(ob_get_clean());
    return $search_form;
  
}