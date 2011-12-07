<?php

/**
 * Admin interface: My Listings tab
 * Entry point
 */

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
  placester_admin_header('placester_properties',
      '<a href="admin.php?page=placester_property_add" ' .
      'class="button add-new-h2">Add New</a>');
  ?>
  <?php if ($provider = placester_provider_check()): ?>
      <?php
      placester_warning_message(
          'Your account is being synced with <a href="'.$provider["url"].'">'.$provider["name"].'</a> anything you do there will appear here automatically.',
          'warning_provider_api_key');
      
      ?>
  <?php endif; ?>
  <div style="margin-top: 10px">
    <div class="view-switch">
      <a id="switch_list"><img class="current" id="view-switch-list" src="<?php echo esc_url( includes_url( 'images/blank.gif' ) ); ?>" width="20" height="20" title="<?php _e('List View') ?>" alt="<?php _e('List View') ?>" /></a>
      <a id="switch_excerpt"><img id="view-switch-excerpt" src="<?php echo esc_url( includes_url( 'images/blank.gif' ) ); ?>" width="20" height="20" title="<?php _e('Excerpt View') ?>" alt="<?php _e('Excerpt View') ?>" /></a>
    </div>
    
    <table>
      <tr>
        <td>City:</td>
        <td>
          <select id='location_city'>
            <option></option>
            <?php 
            foreach ($locations->city as $city)
                echo '<option>' . $city . '</option>';
            ?>
          </select>
        </td>
        <td>State:</td>
        <td>
          <select id='location_state'>
            <option></option>
            <?php 
            foreach ($locations->state as $state)
                echo '<option>' . $state . '</option>';
            ?>
          </select>
        </td>
        <td>Zip:</td>
        <td>
          <select id='location_zip'>
            <option></option>
            <?php 
            foreach ($locations->zip as $zip)
                echo '<option>' . $zip . '</option>';
            ?>
          </select>
        </td>
        <td>Bathrooms:</td>
        <td>
             <select id='min_bathrooms'>
                 <option>1</option>
                 <option>2</option>
                 <option>3</option>
                 <option>4</option>
                 <option>5</option>
                 <option>6</option>
                 <option>7</option>
                 <option>8</option>
                 <option>9</option>
                 <option>10</option>
              </select>               
        </td>
        <td>Bedrooms:</td>
        <td>
            <select id='min_bedrooms'>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
             </select>
        </td>
        <td></td>
        <td><p class="submit" style="margin: 0; padding: 0"><input id='filter_button' type=button value='Filter' /></p></td>
      </tr>
    </table>
  </div>
  <div class="clear"></div>
  
  <div id="container">
    <table id="placester_listings_list" class="widefat post fixed placester_properties" cellspacing="0">
      <thead>
        <tr>
          <th scope="col" class="manage-column" style="width: 50px"></th>
          <th scope="col" class="manage-column" style="width: 25px"></th>
          <th scope="col" class="manage-column">Address</th>
          <th scope="col" class="manage-column" style="width: 70px">Baths</th>
          <th scope="col" class="manage-column" style="width: 70px">Beds</th>
          <th scope="col" class="manage-column" style="width: 70px">Price</th>
          <th scope="col" class="manage-column" style="width: 100px">City</th>
          <th scope="col" class="manage-column" style="width: 100px">Image</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th scope="col" class="manage-column" style="width: 50px"></th>
          <th scope="col" class="manage-column" style="width: 25px"></th>
          <th scope="col" class="manage-column">Address</th>
          <th scope="col" class="manage-column" style="width: 50px">Baths</th>
          <th scope="col" class="manage-column" style="width: 50px">Beds</th>
          <th scope="col" class="manage-column" style="width: 50px">Price</th>
          <th scope="col" class="manage-column" style="width: 100px">City</th>
          <th scope="col" class="manage-column" style="width: 100px">Image</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
