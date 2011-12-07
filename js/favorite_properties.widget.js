jQuery(document).ready(function($) {
    // Remove Favorite Property when Remove is clicked
    $('.side-ctnr.pl_favorite_properties .pl_remove').click( function(e) {
        e.preventDefault();

        $widget = $(this).closest('.pl_favorite_properties');

        $spinner = $widget.find(".pl_spinner");
        $spinner.show();

        $container_li = $(this).closest('li');

        property_id = $(this).attr('href');
        data = {
            action: 'remove_favorite_property',
            property_id: property_id.substr(1),
        };

        $.post(info.ajaxurl, data, function(response) {
            $spinner.hide();
            // If request successfull
            if ( response != 'errors' ) {
                $container_li.remove();

                if ( $widget.find('li').length < 1 ) {
                    $widget.remove();
                }
            }
        });
    });
});
