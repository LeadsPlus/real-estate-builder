jQuery(document).ready(function() {

    // Don't ajaxify the add to favorites link for guests
    $('#pl_add_favorite:not(.guest)').live('click', function(e) {
            e.preventDefault();

            $spinner = $(this).parent().find(".pl_spinner");
            $spinner.show();

            property_id = $(this).attr('href');

            data = {
                action: 'add_favorite_property',
                property_id: property_id.substr(1),
            };

            $.post(info.ajaxurl, data, function(response) {
                $spinner.hide();
                if ( response = 'success' ) {
                    $('#pl_add_favorite').hide();
                    $('#pl_remove_favorite').show();
                }
            });
    });

    $('#pl_remove_favorite').click(function(e) {
        e.preventDefault();
        
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
            }
        });

    });
        
});
