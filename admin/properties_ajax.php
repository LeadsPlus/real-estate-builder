<?php 

/**
 * Admin interface: My Listings tab
 * Script called by AJAX to get properties
 */

require_once(dirname(__FILE__) . "/../../../../wp-load.php");
include("../../../../wp-admin/admin.php");

wp();
if (!current_user_can("edit_themes"))
    die("permission denied");
status_header(200);

$field = $_REQUEST['field'];
$property_id = $_REQUEST['id'];

if ($field == 'is_new')
    $v = get_option('placester_properties_new_ids');
else
    $v = get_option('placester_properties_featured_ids');

if (!is_array($v))
    $v = array();

if (in_array($property_id, $v))
{
    $key = array_search($property_id, $v);
    array_splice($v, $key, 1);
    $new_value = false;
}
else
{
    array_push($v, $property_id);
    $new_value = true;
}

if ($field == 'is_new')
    update_option('placester_properties_new_ids', $v);
else
    update_option('placester_properties_featured_ids', $v);

$output = array('new_value' => $new_value);
echo json_encode($output);
