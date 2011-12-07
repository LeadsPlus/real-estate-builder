<?php

/**
 * Admin interface: Contact tab
 * Sign up form
 */
include_once(dirname(__FILE__) . '/../core/const.php');
$view_default = true;
$error = false;
$error_message = '';
$error_validation_data = new StdClass;

$signup_company = new StdClass;
$signup_user = new StdClass;

//
// Sign up action
//
// @TODO Properly inform the user of the fact that this is a signup form
if (isset($_POST['signup_finish']))
{
    details_compine_with_http($signup_company, $signup_user);

    try
    {
        try
        {
            $api_key = get_option('placester_api_key');
            if (strlen($api_key) <= 0)
            {
                $r = placester_user_add($signup_user);

                update_option('placester_api_key', $r->api_key);
                update_option('placester_user_id', $r->user_id);
                update_option('placester_company_id', $r->agency_id);
                update_option('placester_user', $signup_user);
            }
            else
            {
                placester_user_set($signup_user);
                update_option('placester_user', $signup_user);
            }
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
                placester_company_set(
                    get_option('placester_company_id'), $signup_company);
                update_option('placester_company', $signup_company);
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
        include('contact_signup_ok.php');
        $view_default = false;
    }
}


//
// Default view
//
if ($view_default)
{
    if (strlen($error_message) > 0)
        placester_error_message($error_message);

    details($signup_company, $signup_user, $error_validation_data);
}
