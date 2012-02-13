<?php 
  //top filters, (that filter filters)
  PL_Form::generate(PL_Config::PL_MY_LIST_FORM(), false, false, "pls_admin_my_listings_filters", true, false);  ?>
<?php 
  // generates the search from
  $listings = PL_Config::PL_API_LISTINGS('get');  
  PL_Form::generate($listings['args'], false, "POST", "pls_admin_my_listings", true, false);
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