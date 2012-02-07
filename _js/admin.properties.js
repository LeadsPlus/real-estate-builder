
var placesterListLone_datatable = null;
var placesterListLone_filter = '';
var placesterListLone_is_mode_list = true;

var placesterListLone_datasource_url = '';



/*
 * Returns HTML of new/featured icon
 *
 * @param bool value
 * @param string icon_filename
 * @return string
 */
function placester_icon_html(value, icon_filename)
{
    if (value)
        return '<img src="' + placesterListLone_base_url + 
            '/images/' + icon_filename + '" />';
    else
        return '';
}



/*
 * Returns HTML of new/featured button
 *
 * @param string property_id
 * @param bool value
 * @param string field
 * @param string name
 * @param string mirror_column
 * @param string mirror_column_image_filename
 * @return string
 */
function placester_flag_html(property_id, value, field, name, mirror_column, 
    mirror_column_image_filename)
{
    return '<a href="#" onclick=\'flag_click(this, "' + property_id + '", "' + 
        field + '", "' + name + '", ' + mirror_column + ', "' + 
        mirror_column_image_filename + '")\'>' + 
        placester_flag_html_inner(value, name) + '</a>';
}



/*
 * Returns inner text of new/featured button
 *
 * @param bool value
 * @param string name
 * @return string
 */
function placester_flag_html_inner(value, name)
{
    if (value)
        return 'Unmark ' + name;
    else
        return 'Mark ' + name;
}



/*
 * Handler for button click. Changes associated flag via ajax.
 *
 * @param object cell
 * @param string property_id
 * @param string field
 * @param string name
 * @param string mirror_column
 * @param string mirror_column_image_filename
 */
function flag_click(cell, property_id, field, name, mirror_column,
    mirror_column_image_filename)
{
    jQuery.ajax(
        { 
           url: placesterListLone_base_url + 
                '/admin/properties_ajax.php?id=' + property_id + 
                '&field=' + field, 
           context: cell, 
           dataType: 'json',
           success: 
               function (data)
               {
                   cell.innerHTML = placester_flag_html_inner(data.new_value, name);
                   cell.parentNode.parentNode.parentNode.parentNode.children[mirror_column].innerHTML = 
                       placester_icon_html(data.new_value, mirror_column_image_filename);
               },
           error: function() { alert('error'); }
        });
}



/*
 * Returns value of DOM object in a format name=value
 *
 * @param string id
 * @param string parameter_name
 * @return string
 */
function placester_add_field(id, parameter_name)
{
    var v = jQuery('#' + id).val();
    var s = '';
    if (typeof(v) == 'string' && v.length > 0)
        s = '&' + parameter_name + '=' + escape(v);

    return s;
}

/*
 * Returns HTML of property featured image
 *
 * @param bool value
 * @param string icon_filename
 * @return string
 */
function placester_featured_small_image(value)
{
    if (value !== 'n/a' )
        return '<img src="' + value + '" width="67" height="50" />';
    else
        return '<img src="' + placesterListLone_base_url + 
            '/images/null/property-73-37.png" width="67" height="50" />';
}



/**
 * Creates or recreates datatable object
 */
function placesterListLone_create()
{
    if (placesterListLone_datatable != null)
    {
        placesterListLone_datatable.fnClearTable(false);
    }
    // placesterListLone_datatable.fnDestroy();
    placesterListLone_datatable = jQuery('#placester_listings_list').dataTable(
        {
            'bFilter': false,
            'bPaginate': true,
            'iDisplayLength': 15,
            'bLengthChange': false,
            'bSort': true,
            'bInfo': true,
            'bAutoWidth': false,
            'sPaginationType': 'full_numbers',
            'oLanguage': 
            {
                'oPaginate': 
                {
                    'sPrevious': '&laquo;',
                    'sNext': '&raquo;'
                }
            },
            'bProcessing': true,
            'bServerSide': true,
            'sDom': '<"dataTables_top"pi>lftpir',
            'bDestroy': true,
            'fnServerData':
                function ( sSource, aoData, fnCallback ) {
                    // aoData.push({ action : 'admin_datatable'});
                    // console.log(aoData);
                    // console.log(aoData[0]);
                    // console.log(aoData[0]['value']);

                    $.ajax( {
                        "dataType": 'json', 
                        "type": "POST", 
                        "url": ajaxurl + '?no_admin_filter=yes&fields=empty,empty,location.address,bathrooms,bedrooms,price,location.city,url,is_new,is_featured,id,list_details,featured_image&sEcho=' + aoData[0]['value'], 
                        "data": {action : 'admin_datatable'},  
                        "success": fnCallback
                    });
                },
            // 'sAjaxSource': placesterListLone_datasource_url + placesterListLone_filter,
            'aoColumns': 
                [
                    {
                        'fnRender': 
                            function(row_data) 
                            { return placester_icon_html(row_data.aData[8], 'property_new.gif') },
                        'bSortable': false
                    },
                    {
                        'fnRender': 
                            function(row_data) 
                            { return placester_icon_html(row_data.aData[9], 'property_featured.png') }, 
                        'bSortable': false
                    },
                    {
                        'fnRender': 
                            function(row_data) 
                            { 
                                var control_div =
                                    '<div class="row-actions">';

                                control_div += 
                                    '<span><a href="' + row_data.aData[7] + 
                                    '" target="_blank">View</a> | </span>';
                                control_div += 
                                    '<span><a href="admin.php?page=placester_properties&id=' + 
                                    row_data.aData[10] + '">Edit</a> | </span>';
                                control_div += 
                                    '<span><a href="admin.php?page=placester_properties&id=' + 
                                    row_data.aData[10] +'" class="pl_listing_delete">Delete</a> | </span>';

                                if (typeof(placesterAdmin_properties_item_menu) != 'undefined')
                                {
                                    var addon_data = placesterAdmin_properties_item_menu(row_data.aData);
                                    control_div += addon_data;
                                }

                                control_div += 
                                    '<span>' +
                                    placester_flag_html(row_data.aData[10], row_data.aData[8], 
                                        'is_new', 'New', 0, 'property_new.gif') +
                                    ' | </span>';
                                control_div += 
                                    '<span>' +
                                    placester_flag_html(row_data.aData[10], row_data.aData[9], 
                                        'is_featured', 'Featured', 1, 
                                        'property_featured.png') +
                                    '</span>';

                                control_div += '</div>';

                                excerpt_div = '';
                                if (!placesterListLone_is_mode_list)
                                    excerpt_div = '<div>' + row_data.aData[11] + '</div>';

                                return row_data.aData[2] + excerpt_div + control_div;
                            }
                    },
                    {'fnRender': function(row_data) { return row_data.aData[3] }},
                    {'fnRender': function(row_data) { return row_data.aData[4] }},
                    {'fnRender': function(row_data) { return row_data.aData[5] }},
                    {'fnRender': function(row_data) { return row_data.aData[6] }},
                    {'fnRender': function(row_data) { return placester_featured_small_image(row_data.aData[12]) }},
                    {'bVisible': false},
                    {'bVisible': false},
                    {'bVisible': false},
                    {'bVisible': false},
                    {'bVisible': false}
                ]
        });
     // placesterListLone_datatable.fnUpdate();
}



/**
 * Initializes list
 */
jQuery(document).ready(function() 
{
    if ( correctApiKey = true ) {
        placesterListLone_datasource_url = placesterListLone_base_url + 
            '/properties_datatable.php?' +
            'no_admin_filter=yes&' +
            'fields=empty,empty,location.address,bathrooms,bedrooms,price,location.city,url,is_new,is_featured,id,list_details,featured_image';
    
        placesterListLone_create();
    }
    // When the delete button from the My Listings table is clicked
    jQuery('.pl_listing_delete').live('click', function(e){
        e.preventDefault();
        var answer = confirm("Are you sure you want to delete this listing?");
                    placesterListLone_datatable.dataTable();
        if (answer) {
            id = $(this).attr('href').split('&id=')[1];
            data = {
                action: 'delete_listing',
                listing_id : id
            };
            $row = $(this).closest('tr');
            $.post(ajaxurl, data, function(response) {
                if (response) {
                    placesterListLone_datatable.fnDeleteRow($row);
                    placesterListLone_datatable.fnDraw();
                    // placesterListLone_datatable.fnUpdate();

                }
            });
        }
    });
    jQuery('#placester_listings_list .manage-column.sorting, #placester_listings_list .manage-column.sorting_asc, #placester_listings_list .manage-column.sorting_desc').each(function(){
        html = jQuery(this).html();
        jQuery(this).html('<span>' + html + '</span>');
    
    });
    jQuery('#filter_button').click(function()
    {
        placesterListLone_filter = 
            placester_add_field('location_city', 'location[city]') +
            placester_add_field('location_state', 'location[state]') +
            placester_add_field('location_zip', 'location[zip]') +
            placester_add_field('min_bathrooms', 'min_bathrooms') +
            placester_add_field('min_bedrooms', 'min_bedrooms');
        placesterListLone_create();
    });

    jQuery("#switch_list").click(function()
    {
        jQuery("#view-switch-list").addClass("current");
        jQuery("#view-switch-excerpt").removeClass("current");
        placesterListLone_is_mode_list = true;
        placesterListLone_create();
    });

    jQuery("#switch_excerpt").click(function()
    {
        jQuery("#view-switch-list").removeClass("current");
        jQuery("#view-switch-excerpt").addClass("current");
        placesterListLone_is_mode_list = false;
        placesterListLone_create();
    });
});
