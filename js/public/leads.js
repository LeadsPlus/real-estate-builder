jQuery(document).ready(function($) {

    // Don't ajaxify the add to favorites link for guests
    $('#pl_add_favorite:not(.guest)').live('click', function(e) {
            e.preventDefault();

            var spinner = $(this).parent().find(".pl_spinner");
            spinner.show();

            property_id = $(this).attr('href');

            data = {
                action: 'add_favorite_property',
                property_id: property_id.substr(1),
            };
            var that = this;
            $.post(info.ajaxurl, data, function(response) {
                spinner.hide();
                if ( response.id ) {
                    $(that).hide();
                    if ($(that).attr('id') == 'pl_add_favorite') {
                        $(that).parent().find('#pl_remove_favorite').show();
                    } else {
                        $(that).parent().find('#pl_add_favorite').show();
                    };
                }
            },'json');
    });

    $('#pl_remove_favorite').live('click',function(e) {
        e.preventDefault();
        var that = this;
        $spinner = $(this).parent().find(".pl_spinner");
        $spinner.show();

        property_id = $(this).attr('href');
        data = {
            action: 'remove_favorite_property',
            property_id: property_id.substr(1)
        };

        $.post(info.ajaxurl, data, function(response) {
            $spinner.hide();
            // If request successfull
            if ( response != 'errors' ) {
                $('#pl_add_favorite').show();
                $('#pl_remove_favorite').hide();

                $(that).closest('tr').remove();
            }
        },'json');

    });
        
});
