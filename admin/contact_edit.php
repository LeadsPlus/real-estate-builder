<?php

/**
 * Admin interface: Contact tab
 * Edit details form
 */

include_once(dirname(__FILE__) . '/../core/const.php');
$view_success = false;

$error_message = '';
$error_validation_data = new StdClass;

$company = get_option('placester_company');
if (!$company instanceof StdClass)
    $company = new StdClass;
$user = get_option('placester_user');
if (!$user instanceof StdClass)
    $user = new StdClass;

if (isset($_POST['edit_finish']))
{
    details_compine_with_http($company, $user);
    $error = false;

    try
    {
        try
        {
            placester_user_set($user);

            update_option('placester_user', $user);
        }
        catch (ValidationException $e) 
        {
            $error_message = $e->getMessage();
            $error_validation_data->user = $e->validation_data;
            $error = true;
        }
                      
        if (!$error)
        {
            try
            {
                placester_company_set(get_option('placester_company_id'), $company);
                update_option('placester_company', $company);
            }
            catch (ValidationException $e) 
            {
                $error_message = $e->getMessage();
                $error_validation_data->company = $e->validation_data;
                $error = true;
            }
        }
    }
    catch (Exception $e) 
    {

        $error_message = $e->getMessage();
        $error = true;
    }

    if (!$error)
    {
        $view_success = true;
    }
}
else
{
    // Regular view
    try
    {
        placester_admin_actualize_company_user();
    }
    catch (Exception $e)
    {
        $error_message = $e->getMessage();
        $error = true;
    }

    $company = get_company_details();

    $user = placester_get_user_details();

}

if ($view_success) {
    placester_success_message('Data has been changed');
}
    
if (strlen($error_message) > 0) {
    placester_error_message($error_message);    
}
    

details($company, $user, $error_validation_data);
