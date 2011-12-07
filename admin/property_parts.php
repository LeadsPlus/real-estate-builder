<?php

/**
 * Admin interface: Edit listing / Add listing utilities
 */

global $listing_types;
global $zoning_types;
global $purchase_types;

$listing_types = array();
$zoning_types = array();
$purchase_types = array();

$listing_types['housing'] = 'Housing';
$listing_types['parking'] = 'Parking';
$listing_types['sublet'] = 'Sublet';
$listing_types['vacation'] = 'Vacation';
$listing_types['land'] = 'Land';
$listing_types['other'] = 'Other';

$zoning_types['residential'] = 'Residential';
$zoning_types['commercial'] = 'Commercial';
$zoning_types['residential,commercial'] = 'Mixed';

$purchase_types['rental'] = 'Rental';
$purchase_types['sale'] = 'Sale';
$purchase_types['rental,sale'] = 'Rental or Sale';

/**
 * Returns property value, or null if not exists.
 * Returns value of $o->p1->p2 when $property = 'p1/p2'
 *
 * @param object $o
 * @param string $property
 * @return string or null
 */
function p($o, $property)
{
    if (!isset($o))
        return null;

    $parts = explode('/', $property);
    for ($n = 0; $n < count($parts); $n++)
    {
        $p = $parts[$n];
        if (!isset($o->$p))
            return null;

        $o = $o->$p;
        if (is_array($o))
            $o = $o[0];
    }

    return $o;
}



/**
 * Prints dropdown with possible selected value $value.
 *
 * @param string $property_name
 * @param array $possible_values
 * @param string $value
 * @param string $width
 */
function control_dropdown($property_name, $possible_values, $value, 
    $width = '')
{

    $id = str_replace('/', '_', $property_name);
    ?>
    <select class="heading form-input-tip" 
      name="<?php echo $property_name ?>" 
      id="<?php echo $id ?>" 
      style="width: <?php echo $width ?>" 
      class="heading form-input-tip">
    <?php

    foreach ($possible_values as $key => $name)
    {
        if (!is_array($value))
            $is_selected = ($key == $value);
        else
        {
            $is_selected = false;
            $possible_values_exploded = explode(',', $key);

            if ($possible_values_exploded == $value) {
                $is_selected = true;
            }
        }

        echo '<option value="' . $key . '"' . ($is_selected ? ' selected' : '') . 
            '>' . $name . '</option>';
    }

    echo '</select>';
}



/**
 * Prints dropdown inside html <table> column
 * with possible validation error messages
 *
 * @param string $label
 * @param array $possible_values
 * @param string $property_name
 * @param string $value
 * @param object $validation_object
 * @param string $width
 * @param string $colspan
 */
function column_dropdown($label, $possible_values, $property_name, 
    $value, $validation_object, $width = '', $colspan = '1', $default_value ='')
{
    $value = placester_get_property_value($value, $property_name);

    $value = empty($value) ? $default_value : $value;
    // if (is_array($value)) {
    //     $count = count($value);
    //     if ($count > 1) {
    //     
    //     }
    // }
    $validation_message = p($validation_object, $property_name);

    ?>
    <th scope="row"><label for="<?php echo $property_name ?>"><?php echo $label ?></label></th>
    <td colspan="<?php echo $colspan ?>">
      <?php 
      control_dropdown($property_name, $possible_values, $value, $width);
      if (isset($validation_message))
      {
          echo '<br/><div class="placester_error">';
          echo $validation_message;
          echo '</div>';
      }
      ?>
    </td>
    <?php
}



/**
 * Prints dropdown in html <table> row
 * with possible validation error messages
 *
 * @param string $label
 * @param array $possible_values
 * @param string $property_name
 * @param string $value
 * @param object $validation_object
 */
function row_dropdown($label, $possible_values, $property_name, 
    $value, $validation_object)
{
    ?>
    <tr valign="top">
      <?php 
      column_dropdown($label, $possible_values, $property_name, 
          $value, $validation_object, '', 3);
      ?>
    </tr>
    <?php
}



/**
 * Prints textbox inside html <table> column
 * with possible validation error messages
 *
 * @param string $label
 * @param string $property_name
 * @param string $value
 * @param object $validation_object
 * @param string $colspan
 */
function column_textbox($label, $property_name, $value, $validation_object, 
    $colspan = '1')
{
    $value = placester_get_property_value($value, $property_name);

    $value = ($property_name == 'available_on') ? str_replace('-', '/', $value) : $value;
    $validation_message = p($validation_object, $property_name);
    $id = str_replace('/', '_', $property_name);

    ?>
    <th scope="row"><label for="<?php echo $id ?>"><?php echo $label ?></label></th>
    <td colspan="<?php echo $colspan ?>">
      <input type="text" name="<?php echo $property_name ?>"
        id="<?php echo $id ?>"
        value="<?php echo htmlspecialchars($value) ?>" 
        class="heading form-input-tip" 
        style="width:100%" />
      <?php 
      if (isset($validation_message))
      {
          echo '<br/><div class="placester_error">';
          echo $validation_message;
          echo '</div>';
      }
      ?>
    </td>
    <?php
}



/**
 * Prints textbox in html <table> row
 * with possible validation error messages
 *
 * @param string $label
 * @param string $property_name
 * @param string $value
 * @param object $validation_object
 */
function row_textbox($label, $property_name, $value, $validation_object)
{
    ?>
    <tr valign="top">
      <?php 
      column_textbox($label, $property_name, $value, $validation_object, 3);
      ?>
    </tr>
    <?php

}



/**
 * Prints textarea in html <table> row
 * with possible validation error messages
 *
 * @param string $label
 * @param string $property_name
 * @param string $value
 * @param object $validation_object
 */
function control_textarea($label, $property_name, $value, $validation_object)
{
    $value = placester_get_property_value($value, $property_name);
    $validation_message = p($validation_object, $property_name);

    ?>
    <textarea 
      name="<?php echo $property_name ?>" 
      id="<?php echo $property_name ?>" 
      rows="5" 
      class="heading form-input-tip" 
      style="width:100%"><?php echo htmlspecialchars($value) ?></textarea>
    <?php 
    if (isset($validation_message))
    {
        echo '<br/><div style="color:red">';
        echo $validation_message;
        echo '</div>';
    }
}

/**
 * Prints the images box containing the uploadify button
 *
 */
function uploadify_box($p) {
    placester_postbox_header('Images'); 
?>
      <div id="placester_images_box" class="clearfix">
<?php
    $uploadify_button = '';
    $form_url = admin_url() . 'admin.php?page=placester_properties&id=' . $p->id;
    echo '<div id="placester_listing_images" class="clearfix" style="padding: 10px">';
    if ( $p->images ) { 
?>
    <form>
<?php
        foreach ( $p->images as $i ) {
?>
            <div class="img">
                <img src="<?php echo $i->url ?>"/>
                <a href="<?php echo $form_url ?>&delete=<?php echo urlencode($i->id) ?>" class="remove">
                Delete
                </a>
            </div>
<?php
        }
    } else {
?>
     <p>This listing has no images added</p>
<?php
    }
    echo '</div>';
?>
    <div class="foot clearfix">
        <a href="#" class="refresh">Refresh</a>
        <a href="#" class="button" id="placester_delete_all_images">Delete all images</a>
    </div>
<?php
        ?>
      </div>
    <?php 
    placester_postbox_footer(); 
    $uploadify_button = '<input id="file_upload" name="file_upload" type="file" />'; 
    echo '<div class="uploadify_button" style="margin-bottom: 20px">' . $uploadify_button . '</div>';
}

/**
 * Prints images upload control in html <table> row
 * with possible validation error messages
 *
 * @param string $validation_message
 */
function box_images($validation_message = '')
{
    placester_postbox_header('Images');

    ?>

  <div id="placester_images_box" class="clearfix" style="padding: 10px">
    <input type="file" name="images[]" class="multi" />
    <?php 

    if (isset($validation_message))
    {
        echo '<br/><div style="color:red">';
        echo $validation_message;
        echo '</div>';
    }
    placester_postbox_footer();
?>
        </div>
    <?php
}



/**
 * Returns property object from HTTP POST data
 *
 * @return object
 */

function http_property_data()
{
    $p = new StdClass;
    foreach ($_POST as $key => $value)
        placester_set_property_value($p, $key, $value);

    $lt = explode(',', $p->listing_types);
    $zt = explode(',', $p->zoning_types);
    $pt = explode(',', $p->purchase_types);

    $p->zoning_types = array();
    foreach ($zt as $zone) {
        $p->zoning_types[] = $zone;
    }

    $p->listing_types = array();
    foreach ($lt as $listing) {
        $p->listing_types[] = $listing;
    }

    $p->purchase_types = array();
    foreach ($pt as $purchase) {
        $p->purchase_types[] = $purchase;
    }

    return $p;
}

/**
 * Prints property add / edit form
 *
 * @param object $p
 * @param object $v
 */
function property_form($p, $v)
{
    global $placester_const_property_types;
    global $listing_types;
    global $zoning_types;
    global $purchase_types;

    if (isset($_REQUEST['delete']))
    {
        $r = placester_property_image_delete($p->id, $_REQUEST['delete']);
        if ( !$r ) {
            header( 'Location: ' . admin_url('admin.php?page=placester_properties&id=' . $_REQUEST['id'] . '&deleted=1') );
        }
    } else if (isset($_REQUEST['deleted'])) {
        placester_warning_message( 'Image was deleted' );
    }         

    $company = get_option('placester_company');

    if (! placester_is_api_key_specified())
    {
        ?>
        <input id="property_readonly" type="hidden" value="warning_no_api_key" />
        <?php
    }
    else if ($company instanceof StdClass &&
        isset($company->provider) && isset($company->provider->name))
    {
        $name = $company->provider->name;
        placester_warning_message("We're automatically pulling " .
            'in your listings from ' . $name . '. ' .
            'Add or edit your listings with ' . $name, 'provider_warning');
        ?>
        <input id="property_readonly" type="hidden" value="provider_warning" />
        <?php
    }


    $array_1_9 = array();
    for ($n = 1; $n <= 9; $n++)
        $array_1_9[$n] = $n . '&nbsp;&nbsp;';

    $array_0_9 = array();
    for ($n = 0; $n <= 9; $n++)
        $array_0_9[$n] = $n . '&nbsp;&nbsp;';
    ?>

    <?php placester_postbox_header('Type'); ?>
    <table class="form-table">
      <tr>
      <?php
      row_dropdown('Listing type', $listing_types, 'listing_types', $p, $v);
      ?>
      </tr>
      <tr>
      <?php
      row_dropdown('Zoning Type', $zoning_types, 'zoning_types', $p, $v);
      ?>
      </tr>
      <tr>
      <?php
      row_dropdown('Purchase Type', $purchase_types, 'purchase_types', $p, $v);
      ?>
      <tr>
    </table>
    <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('Basics'); ?>
    <table class="form-table">
      <tr>
        <?php
        column_dropdown('Beds', $array_1_9, 'bedrooms', $p, $v);
        column_textbox('Available on<span class="placester_required">*</span>', 'available_on', $p, $v);
        ?>
      </tr>
      <tr>
        <?php
        column_dropdown('Baths', $array_1_9, 'bathrooms', $p, $v);
        column_dropdown('Property Type', $placester_const_property_types, 
            'property_type', $p, $v);
        ?>
      </tr>
      <tr>
        <?php
        column_dropdown('Half baths', $array_0_9, 'half_baths', $p, $v);
        column_textbox('Price<span class="placester_required">*</span>', 'price', $p, $v);
        ?>
      </tr>
    </table>
    <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('Address'); ?>
    <table class="form-table">
      <tr>
        <?php
        column_textbox('Address<span class="placester_required">*</span>', 'location/address', $p, $v);
        echo '<p>';
        column_textbox('Zip<span class="placester_required">*</span>', 'location/zip', $p, $v);
        echo '</p>';
        ?>
      </tr>
      <tr>
        <?php
        column_textbox('City<span class="placester_required">*</span>', 'location/city', $p, $v);
        column_textbox('Unit', 'location/unit', $p, $v);
        ?>
      </tr>
      <tr>
        <?php
        echo '<p>';
        column_textbox('State<span class="placester_required">*</span>', 'location/state', $p, $v);
        echo '</p>';
        column_textbox('Neighborhood', 'location/neighborhood', $p, $v);
        ?>
      </tr>
      <tr>
    <?php

        $default_country = get_option('placester_default_country'); 
        global $placester_countries;
        column_dropdown('Country', $placester_countries, 'location/country', $p, $v, '', 1, $default_country);
        ?>
        </tr>
      <tr>
        <?php
        column_textbox('Latitude', 'location/coords/latitude', $p, $v);
        ?>
        <td colspan="2" rowspan="3" style="text-align: right">
          <div>
            <input type=button value="&nbsp;&nbsp;Locate by Address&nbsp;&nbsp;"
              onclick="map_geocoded_address = ''; map_geocode_address();" />
          </div>
          <div id="map" style="width: 300px; height: 150px; float: right; clear: both"></div>
        </td>
      </tr>
      <tr>
        <?php
        column_textbox('Longitude', 'location/coords/longitude', $p, $v);
        ?>
      </tr>
      <tr>
        <td colspan="2">
          <br />
          <br />
          <br />
          <br />
        </td>
      </tr>
    </table>
    <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('Description'); ?>
      <div style="padding: 5px">
        <?php control_textarea('Description', 'description', $p, $v); ?>
      </div>
    <?php placester_postbox_footer(); ?>
    
    <?php
}
