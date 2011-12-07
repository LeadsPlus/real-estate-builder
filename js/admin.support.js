/*
 * Support tab ajax functionality
 */
jQuery(document).ready(function()
{
    /*
     * Click handler - Adds new "file upload"
     */
    jQuery('#support_more_files').live('click', function() 
    {
        jQuery(this).before('<input type="file" name="files[]" /><br />');

        return false;
    });



    /*
     * Form submit handler - validation
     */
    jQuery('#placester_form').live('submit', function() 
    {
        var url = jQuery('.required #support_url');
        var name = jQuery('.required #support_name');
        var email = jQuery('.required #support_email');
        var phone = jQuery('.required #support_phone');
        var subject = jQuery('.required #support_subject');
        var description = jQuery('.required #support_description');

        if (url.size() && url.val() == '') 
        {
            alert('Please enter the address of your site in the Site URL field.');
            url.focus();
            return false;
        }

        if (name.size() && name.val() == '') 
        {
            alert('Please enter your name in the Name field.');
            name.focus();
            return false;
        }

        if (email.size() && !/^[a-z0-9_\-\.]+@[a-z0-9-\.]+\.[a-z]{2,5}$/.test(email.val().toLowerCase())) 
        {
            alert('Please enter valid email address in the E-Mail field.');
            email.focus();
            return false;
        }

        if (phone.size() && !/^[0-9\-\.\ \(\)\+]+$/.test(phone.val())) 
        {
            alert('Please enter your phone in the phone field.');
            phone.focus();
            return false;
        }

        if (subject.size() && subject.val() == '') 
        {
            alert('Please enter subject in the subject field.');
            subject.focus();
            return false;
        }

        if (description.size() && description.val() == '') 
        {
            alert('Please describe the issue in the issue description field.');
            description.focus();
            return false;
        }

        return true;
    });



    /*
     * Loading form by AJAX on selection of support type
     */
    jQuery('#support_request_type').live('change', function() 
    {
        var request_type = jQuery(this);

        if (request_type.val() == '') 
        {
            alert('Please select request type.');
            request_type.focus();

            return false;
        }

        jQuery('#support_container').html(
            '<div id="support_loading">Loading...</div>').load(
            'admin.php?page=placester_support&ajax_action=' + request_type.val());
        return false;
    });


    
    /*
     * Cancellation of support type selection
     */
    jQuery('#support_cancel').live('click', function() 
    {
        jQuery('#support_container').html(
            '<div id="support_loading">Loading...</div>').load(
            'admin.php?page=placester_support&ajax_action=intro');
    });
});

