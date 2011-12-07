<?php

/**
 * Admin interface: Edit listing form
 * Entry point
 */

include_once(dirname(__FILE__) . '/../core/const.php');
include_once('property_parts.php');

$p = new StdClass;
$v = new StdClass;

$error = false;
$error_message = '';
$view_success = false;

$property_id = $_REQUEST['id'];

//
// Create property
//
if (isset($_POST['edit_finish']))
{
    $p = http_property_data();
    
    try
    {
        $p->url = placester_get_property_url($property_id);
        $r = placester_property_set($property_id, $p);
    }
    catch (ValidationException $e) 
    {
        $error_message = $e->getMessage();
        $v = $e->validation_data;

        if ($error_message != 'OK')
            $error = true;
    }
    catch (Exception $e) 
    {
        $error_message = $e->getmessage();
        if ($error_message != 'OK')
            $error = true;
    }
    if (!$error) {
        placester_update_listing( $property_id );
        placester_success_message('This listing has been successfully modified.');
        $view_success = true;
    }

}

$p = placester_property_get($property_id);

include('property_edit_form.php');
