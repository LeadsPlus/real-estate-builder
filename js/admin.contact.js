
/*
 * Handles image uploading
 */
jQuery(document).ready(function()
{
    jQuery('.file_upload').click(function()
    {
        var id = jQuery(this).attr('id');
        id = id.substr(0, id.length - 7);
        jQuery('#' + id + '_file').upload(
            'admin.php?page=placester_contact&ajax_action=upload', 
            function(res)
            {
                jQuery('#' + id + '_thumbnail').html(
                    '<img src="' + res.thumbnail + '" />');
                jQuery('#' + id).val(res.id);
            },
            'json');
    });

    // DRY dies in agony
    // TODO Resurect DRY on UI change
    detached_zip_row = false;
    detached_state_row = false;
    changed_state_label = false;
    $country = jQuery('#company_location_country');
    $zip_row = jQuery('#company_location_zip').closest('tr');
    $state_row = jQuery('#company_location_state').closest('tr');
    if ($country.val() == "BZ") {
        $zip_row = $zip_row.detach();
        detached_zip_row = true;
    }
    if ($country.val() == "UK") {
        $state_row.find('label').html('County');
        changed_state_label = true;
    }
    // if ($country.val() == "ZA") {
        // $state_row = $state_row.detach();
        // detached_state_row = true;
    // }

    $country.change(function(){

        if ($(this).val() == "UK") {
            $state_row.find('label').html('County');
            changed_state_label = true;
        } else if (changed_state_label) {
            $state_row.find('label').html('State');
        }

        // if ($(this).val() == "ZA") {
            // $state_row = $state_row.detach();
            // detached_state_row = true;
        // } else if (detached_state_row) {
            // $('#company_location_city').closest('tr').after($state_row);
            // detached_state_row = false;
        // }

        if ($(this).val() == "BZ") {
            $zip_row = $zip_row.detach();
            detached_zip_row = true;
        } else if (detached_zip_row) {
            $(this).closest('tr').after($zip_row);
            detached_zip_row = false;
        }

    });
});
