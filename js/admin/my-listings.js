/**
 *  Applies Chosen to forms.
 */
$( document ).ready( function() {
    $("select").chosen({allow_single_deselect: true});
});


// For datatable
$(document).ready(function() {
    var my_listings_datatable = $('#placester_listings_list').dataTable( {
            "bFilter": false,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": ajaxurl,
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value" : "datatable_ajax"} );
                aoData = my_listings_search_params(aoData);
            }
        });
    
    // prevents default on search button
    $('#pls_admin_my_listings').live('submit', function(event) {
        event.preventDefault();
        my_listings_datatable.fnDraw();
    });

    // parses search form and adds parameters to aoData
    function my_listings_search_params (aoData) {
        jQuery.each(jQuery('#pls_admin_my_listings').serializeArray(), function(i, field) {
            aoData.push({"name" : field.name, "value" : field.value});
        });
        return aoData;
    }

});



