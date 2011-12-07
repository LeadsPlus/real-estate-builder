jQuery(document).ready(function() {
    $invite_form = jQuery('#rm_invite_form');
    $wrap = $('#wpbody-content').find('.wrap').eq(0);

    $invite_form.validate({
        //set the rules for the field names
        rules: {
            rm_email: {
                required: true,
                email: true
            },
        },
        //set messages to appear inline
        messages: {
            rm_email: "Please enter a valid email address.",
        }
    });


    $invite_form.submit(function(e) {
        e.preventDefault();

        if ( $invite_form.valid() ) {
            $spinner = $invite_form.find(".pl_spinner");
            $spinner.show();
            email_address = $invite_form.find('#rm_email').val();
            email_body = $invite_form.find('#rm_message').val();

            data = {
                action: 'send_lead_invite',
                email: email_address,
                message: email_body,
            };

            $.post(ajaxurl, data, function(response) {
                $spinner.hide();
                if ( $(response).hasClass('success') ) {
                    $invite_form.find('input[type="text"], textarea').each(function(){
                        $(this).val('');
                    });
                }
                $wrap.find('.pl_roommate_invite_send').fadeOut('fast');
                $wrap.find('h2').after(response);
            });
        }

    });

    $('.accept-invite').live('click', function(e){
            e.preventDefault();
            $alert_div = $(this).closest('.updated');
            inviter_id = $alert_div.attr('id');

            data = {
                action: 'accept_invitation',
                inviter_id: inviter_id
            };

            $.post(ajaxurl, data, function(response) {
                if ( $(response).hasClass('success') ) {
                    $alert_div.fadeOut('fast');
                }

                $wrap.find('#' + inviter_id + '.pl_roommate_invite_notice').fadeOut('fast');

                $wrap.find('h2').after(response);
            });
    });

    $('.decline-invite').live('click', function(e){
            e.preventDefault();
            $alert_div = $(this).closest('.updated');
            inviter_id = $alert_div.attr('id');

            data = {
                action: 'decline_invitation',
                inviter_id: inviter_id
            };

            $.post(ajaxurl, data, function(response) {
                if (response == 'success') {
                    $alert_div.fadeOut('fast');
                }
            });
    });

    $('.delete_roommate').live('click', function(e){
        e.preventDefault();

        $spinner = $("#placester_roommates").closest('.postbox-container').find(".pl_spinner");
        $spinner.show();

        split_url = getUrlVars($(this).attr('href'));

        $container_row = $(this).closest('tr');

        data = {
            action: 'delete_roommate',
            roommate_wp_id: split_url['wp_id']
        };

        $.post(ajaxurl, data, function(response) {
            $spinner.hide();
            if ( $(response).hasClass('success') ) {
                $container_row.remove();
                if ( $("#placester_roommates tbody tr").length < 1 ) {
                    $("#placester_roommates").remove();
                }
            }

            $wrap.find('h2').after(response);
        });
    });

    $('#placester_favorite_properties .remove_from_favorites').click(function(e) {
        e.preventDefault();
        
        $spinner = $(this).closest('table').parent().find(".pl_spinner");
        $spinner.show();

        property_id = $(this).attr('href');
        data = {
            action: 'remove_favorite_property',
            property_id: property_id.substr(1)
        };

        $container_row = $(this).closest('tr');

        $.post(ajaxurl, data, function(response) {
            $spinner.hide();
            // If request successfull
            if ( response != 'success' ) {
                $container_row.remove();
                // TODO If empty, display suggestion to add favorites
                if ( $("#placester_favorite_properties tbody tr").length < 1 ) {
                    $("#placester_favorite_properties").remove();
                }
            }
        });
    });

    $lead_profile_form = $('#pl-lead-profile');
    $lead_profile_form.validate({
        //set the rules for the field names
        rules: {
            user_phone: {
                digits: true,
                rangelength: [10, 11]
            },
            user_email: {
                email: true,
                required: true
            },
        },
        //set messages to appear inline
        messages: {
            user_email: "Please enter a valid email address.",
            user_phone: "The phone number must either have 10 or 11 numerical characters.",
        }
    });

    $lead_profile_form.submit(function(e) {
        e.preventDefault();
        if ($lead_profile_form.valid()) {
            $spinner = $lead_profile_form.find(".pl_spinner");
            $spinner.show();

            first_name = $lead_profile_form.find('#user_first_name').val();
            last_name = $lead_profile_form.find('#user_last_name').val();
            email = $lead_profile_form.find('#user_email').val();
            phone = $lead_profile_form.find('#user_phone').val();
            password = $lead_profile_form.find('#user_new_password').val();

            data = {
                action: 'update_lead_profile',
                user_first_name: first_name,
                user_last_name: last_name,
                user_email: email,
                user_phone: phone,
                user_new_password: password
            };

            $.post(ajaxurl, data, function(response) {
                $spinner.hide();
                if ($(response).hasClass('success')) {
                    if ($('#user_new_password').val()) {
                        $lead_profile_form.find('#user_new_password').val('');
                        $('.default-password-nag').remove();
                    }
                }
                // default-password-nag
                $lead_profile_form.closest('.wrap').find('.pl_leads_profile_update').fadeOut('fast');
                $lead_profile_form.closest('.wrap').find('h2').after(response);
            });
        }
    })

});

/** ---------------------------
 *  Utility functions
 *  --------------------------- */

/**
 *  Reads the GET URL variables and returns them as an associative array
 */
function getUrlVars(url) {
    var vars = [], hash;
    var hashes = url.slice(url.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

