<?php 
  //top filters, (that filter filters)
  PL_Form::generate_form(PL_Config::PL_MY_LIST_FORM(), array('method' => "POST", 'title' => false, 'include_submit' => false, 'id' => 'pls_admin_my_listings_filters') );  ?>
<?php 
  // generates the search from   false, "POST", "pls_admin_my_listings", true, false
  PL_Form::generate_form(PL_Config::PL_API_LISTINGS('get', 'args'),array('method' => "POST", 'title' => true, 'include_submit' => false, 'id' => 'pls_admin_my_listings'));
?>
<div id="container" style="width: 99%">
  <table id="placester_listings_list" class="widefat post fixed placester_properties" cellspacing="0">
    <thead>
      <tr>
        <th><span></span></th>
        <th><span>Street</span></th>
        <th><span>Zip</span></th>
        <th><span>Type</span></th>
        <th><span>Listing</span></th>
        <th><span>Property</span></th>
        <th><span>Beds</span></th>
        <th><span>Baths</span></th>
        <th><span>Price</span></th>
        <th><span>Sqft</span></th>
        <th><span>Available</span></th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </tfoot>
  </table>
</div>
<div style="display:none" id="delete_listing_confirm">
  <div id="delete_response_message"></div>
  <div>Are you sure you want to permanently delete <span id="delete_listing_address"></span>?</div>  
</div>
