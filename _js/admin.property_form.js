jQuery(document).ready(function() {
    // Hide different field for different countries
    // DRY dies in agony
    // TODO Resurect DRY on UI change
    detached_zip_row = false;
    // detached_state_row = false;
    changed_state_label = false;
    $country = jQuery('#location_country');
    $zip_row = jQuery('#location_zip').closest('tr');
    $zip_th = $zip_row.find('label[for="location_zip"]').closest('th');
    $zip_td = $zip_row.find('#location_zip').closest('td');
    // $state_row = jQuery('#location_state').closest('tr');
    // $state_th = $state_row.find('label[for="location_state"]').closest('th');
    // $state_td = $state_row.find('#location_state').closest('td');

    if ($country.val() == "BZ") {
        $zip_th = $zip_th.detach();
        $zip_td = $zip_td.detach();
        detached_zip_row = true;
    }
    if ($country.val() == "UK") {
        $state_row.find('label').html('County');
        changed_state_label = true;
    }
    // if ($country.val() == "ZA") {
        // $state_th = $state_th.detach();
        // $state_td = $state_td.detach();
        // detached_state_row = true;
    // }

    $country.change(function(){
        if ($(this).val() == "UK") {
            $state_th.find('label').html('County<span class="placester_required">*</span>');
            changed_state_label = true;
        } else if (changed_state_label) {
            $state_th.find('label').html('State<span class="placester_required">*</span>');
        }

        // if ($(this).val() == "ZA") {
            // $state_th = $state_th.detach();
            // $state_td = $state_td.detach();
            // detached_state_row = true;
        // } else if (detached_state_row) {
            // $nbh = $state_row.find('label[for="location_neighborhood"]').closest('th');
            // $nbh.before($state_th);
            // $nbh.before($state_td);
            // detached_state_row = false;
        // }
        if ($(this).val() == "BZ") {
            $zip_th = $zip_th.detach();
            $zip_td = $zip_td.detach();
            detached_zip_row = true;
        } else if (detached_zip_row) {
            $adr = $zip_row.find('#location_address').closest('td');
            $adr.after($zip_td);
            $adr.after($zip_th);
            detached_zip_row = false;
        }
    });
});
