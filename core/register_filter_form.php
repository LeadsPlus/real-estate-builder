<?php

/**
 * Body of "placester_register_filter_form()" function
 * This file is processed only when function is really called
 */

?>
<script>
var placesterFilter_fields = 
    [
        'available_on',
        'bathrooms',
        'bedrooms',
        'half_baths',
        'location[city]',
        'location[state]',
        'location[zip]',
        'max_bathrooms',
        'max_bedrooms',
        'max_half_baths',
        'max_price',
        'min_bathrooms',
        'min_bedrooms',
        'min_half_baths',
        'min_price',
        'property_type',
        'listing_types',
        'purchase_types',
        'zoning_types',
        'is_new',
        'is_featured'
    ];



/*
 * Utility to get current HTTP GET parameters
 */
jQuery.extend(
{
    getUrlVars: function()
    {
        var vars = [];
        var p = window.location.href.indexOf('?');
        if (p >= 0)
        {
            var hashes = window.location.href.slice(p + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
              var hash = hashes[i].split('=');
              vars.push(unescape(hash[0]));
              vars[unescape(hash[0])] = unescape(hash[1]);
            }
        }
        return vars;
    }
});



/*
 * Initialization of filter form
 */
jQuery('#<?php echo $form_dom_id ?>').ready(function()
{
    var http = jQuery.getUrlVars();

    var filter_query = '';
    for (var n = 0; n < placesterFilter_fields.length; n++)
    {
        var field = placesterFilter_fields[n];
        var v = http[field];
        if (typeof(v) == 'string' && v.length > 0)
        {
            jQuery('#<?php echo $form_dom_id ?>').find('input[name="' + field + '"]').val(v);
            jQuery('#<?php echo $form_dom_id ?>').find('select[name="' + field + '"]').val(v);
            filter_query += '&' + field + '=' + escape(v);
        }
    }

    if (filter_query.length > 0)
        placesterFilter_refreshDependent(filter_query);
});



/*
 * Handler when filter form is submitted. 
 * Actually lists are asked to refresh with actual filter data.
 */
jQuery('#<?php echo $form_dom_id ?>').submit(function(event)
{
    var filter_query = '';
    for (var n = 0; n < placesterFilter_fields.length; n++)
    {
        var field = placesterFilter_fields[n];
        var v = null;
        if (jQuery(this).find('input[name="' + field + '"]').is('[type="checkbox"]'))
        {
            if (jQuery(this).find('input[name="' + field + '"]').attr('checked'))
                v = 'true';
        }
        else
        {
            v = jQuery(this).find('input[name="' + field + '"]').val();
            if (typeof(v) != 'string')
                v = jQuery(this).find('select[name="' + field + '"]').val();
        }
        if (typeof(v) == 'string' && v.length > 0) {
            filter_query += '&' + field + '=' + escape(v);
        } else if (jQuery.isArray(v)) {
            $.each(v, function(i, val) {
                filter_query += '&' + field + '[]=' + val;
            });
        }

    }
   
    event.preventDefault();
    placesterFilter_refreshDependent(filter_query);
});



/*
 * Asks map/list to refresh with actual filter data.
 *
 * @param string filter_query
 */
function placesterFilter_refreshDependent(filter_query)
{
    if (typeof(placesterMap_setFilter) != 'undefined')
        placesterMap_setFilter(filter_query);
    if (typeof(placesterListLone_setFilter) != 'undefined')
        placesterListLone_setFilter(filter_query);
}

</script>
