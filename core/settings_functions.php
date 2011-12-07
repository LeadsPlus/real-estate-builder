<?php

/**
 * Functions related to extraction of plugin settings
 */

/**
 * Returns object containing user details
 *
 * @return object
 */
function placester_get_user_details()
{ 
    $user = get_option('placester_user');

    $company = get_option('placester_company');
    if(isset($company->location)) $location = $company->location;

    $user->logo_url = plugins_url('/images/null/profile-110-90.png', dirname(__FILE__));
    if (property_exists($user, 'logo'))
    {
        $id = $user->logo;
        if (strlen($id) > 0)
        {
            $urls = wp_get_attachment_image_src($id, 'full');
            $user->logo_url = $urls[0];
        }
    }

    if (!isset($user->first_name))
        $user->first_name = null;
    if (!isset($user->last_name))
        $user->last_name = null;
    if (!isset($user->location))
    {
        $user->location = new StdClass;
        $user->location->address = isset($location->address) ? $location->address : null;
        $user->location->city = isset($location->city) ? $location->city : null;
        $user->location->state = isset($location->state) ? $location->state : null;
        $user->location->zip = isset($location->zip) ? $location->zip : null;
        $user->location->country = isset($location->country) ? $location->country : null;
        $user->location->unit = isset($location->unit) ? $location->unit : null;
        $user->location->coords = isset($location->coords) ? $location->coords : null;
    }
    if (!isset($user->phone))
        $user->phone = null;
    if (!isset($user->email))
        $user->email = null;
    if (!isset($user->description))
        $user->description = null;//"Just a simple description of myself. Something more substantial is coming shortly.";

    return $user;
}



/**
 * Returns object containing company details
 *
 * @return object
 */
function get_company_details()
{
    $company = get_option('placester_company');

    $company->logo_url = plugins_url('/images/null/logo-190-160.png', dirname(__FILE__));
    if (property_exists($company, 'logo'))
    {
        $id = $company->logo;
        if (strlen($id) > 0)
        {
            $urls = wp_get_attachment_image_src($id, 'full');
            $company->logo_url = $urls[0];
        }
    }

    if (!isset($company->name))
        $company->name = null;
    if (!isset($company->location))
    {
        $company->location = new StdClass;
        $company->location->address = null;
        $company->location->city = null;
        $company->location->zip = null;
        $company->location->state = null;
        $company->location->country = null;
        $company->location->unit = null;
        $company->location->coords = array(null, null);
    }
    if (!isset($company->phone))
        $company->phone = null;
    if (!isset($company->description))
        $company->description = null;//'A great description is on the way, for now - this should do.';

    return $company;
}
