<?php 

  $listings = PL_Config::PL_API_LISTINGS('get');
  PL_Form::generate($listings['args'], false, "POST", "pls_admin_my_listings");
?>
<div id="container" style="width: 99%">
  <table id="placester_listings_list" class="widefat post fixed placester_properties" cellspacing="0">
    <thead>
      <tr>
        <!-- <th scope="col" class="manage-column" style="width: 50px">test</th>
        <th scope="col" class="manage-column" style="width: 25px">test</th>
        <th scope="col" class="manage-column">Address</th>
        <th scope="col" class="manage-column" style="width: 70px">Baths</th>
        <th scope="col" class="manage-column" style="width: 70px">Beds</th>
        <th scope="col" class="manage-column" style="width: 70px">Price</th>
        <th scope="col" class="manage-column" style="width: 100px">City</th>
        <th scope="col" class="manage-column" style="width: 100px">Image</th> -->
        <th>Images</th>
        <th>Street</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Zoning</th>
        <th>Purchase</th>
        <th>Listing</th>
        <th>Property</th>
        <th>Beds</th>
        <th>Baths</th>
        <th>Price</th>
        <th>Sqft</th>
        <th>Available</th>
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
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </tfoot>
  </table>
</div>

<?php 
/**
 * Admin interface: My Listings tab
 * Entry point
 */


/*
$base_url = WP_PLUGIN_URL . '/placester';

$locations = new StdClass;
$locations->city = array();
$locations->state = array();
$locations->zip = array();
try
{
    $locations = placester_location_list();
    if ($locations) {
    sort($locations->city);
    sort($locations->state);
    sort($locations->zip); 
    ?>
    <script>
    var correctApiKey = true;
    </script>
    <?php
    }
}
catch (Exception $e)
{
    // placester_warning_message('You need to add your contact details before you can continue.  Navigate to the <a href="/wp-admin/admin.php?page=placester_contact">personal tab</a> and add an email address to start.');
}

?>
<script>

var placesterListLone_base_url = '<?php echo $base_url ?>';

</script>

<div class="wrap">
  <?php 
  //placester_admin_header('placester_properties',
      // '<a href="admin.php?page=placester_property_add" ' .
      // 'class="button add-new-h2">Add New</a>');
  ?>
  <?php //if ($provider = placester_provider_check()): ?>
      <?php
      //placester_warning_message(
          // 'Your account is being synced with <a href="'.$provider["url"].'">'.$provider["name"].'</a> anything you do there will appear here automatically.',
          // 'warning_provider_api_key');
      
      ?>
  <?php //endif; ?>


*/