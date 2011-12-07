/* 
 * General Placester scripts that
 * apply to the whole wordpress backend
 * 
 */
jQuery(document).ready(function($) {
    jQuery('#hide-theme-alert')
        .click(function(e){
            e.preventDefault();
            $warning = jQuery(this).closest('.updated');
            data = {
                action: 'update_theme_alert',
            };
            jQuery.get(ajaxurl, data, function(response) {
                if (response) {
                    $warning.fadeOut('200', function() {
                        jQuery(this).remove();
                    });
                }
            });
        });

    $extra = $("#cb_placester_activate_client_accounts").closest('tbody').find('.extra_info').eq(0);
    if ($("#cb_placester_activate_client_accounts").attr('checked')) {
        $extra.show();
    } else {
        $extra.hide();
    }
    $("#cb_placester_activate_client_accounts").change(function(e) {
        if ($(this).attr('checked')) {
            $extra.show();
        } else {
            $extra.hide();
        }
    });

    // Don't allow Lead creation or editing from the admin menu
    $selected_role = $('#your-profile #role option:selected');
    if ( $selected_role.attr('value') == 'placester_lead' ) {
        $selected_role.siblings(':not(:selected)').remove();
        $selected_role.parent().after('<span class="description">The role cannot be changed for leads.</span>');
        $('#first_name')
            .attr("disabled","disabled")
            .after('<span class="description">This field can only be changed by the user.</span>');
        $('#email')
            .attr("disabled","disabled")
            .after('<span class="description">This field can only be changed by the user.</span>');
        $('#last_name')
            .attr("disabled","disabled")
            .after('<span class="description">This field can only be changed by the user.</span>');
    } else {
        $('#your-profile #role option[value="placester_lead"]').remove();
    }

    $('#wpwrap form#createuser #role option[value="placester_lead"]').remove();
    $('#wpbody-content #new_role option[value="placester_lead"]').remove();
});
