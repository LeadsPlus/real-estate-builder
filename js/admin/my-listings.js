/**
 *  Applies Chosen to forms.
 */
// $( document ).ready( function() {
//     $("select").chosen({allow_single_deselect: true});
// });


// For datatable
$(document).ready(function($) {

    // Filter Filters
    handle_custom_filter_choices();

    $('#pls_admin_my_listings_filters input').live('change', function () {
        handle_custom_filter_choices($(this).attr('id'), $(this).is(":checked"));   
    });
    
    function handle_custom_filter_choices(filter, value) {
        var params = {action : 'filter_options'};
        if (typeof filter == 'undefined' || typeof value == 'undefined') {
            params['get'] = true;
        } else  {
            params['filter'] = filter;
            params['value'] = value;
        }
        $.post(ajaxurl,params, function(data) {
            $.each(data, function (index, value) {
                $('input#' + index).prop("checked", value === 'true');
                if (value === 'true') {
                    $('#pls_search_form section#' + index).slideDown();
                } else {
                    $('#pls_search_form section#' + index).slideUp();
                }
            });
        }, "json");
    }    

    //datepicker
    $("input#metadata-max_avail_on_picker, #metadata-min_avail_on_picker").datepicker({
            showOtherMonths: true,
            numberOfMonths: 2,
            selectOtherMonths: true
        });

    var my_listings_datatable = $('#placester_listings_list').dataTable( {
            "bFilter": false,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            'sPaginationType': 'full_numbers',
            'sDom': '<"dataTables_top"pi>lftpir',
            "sAjaxSource": ajaxurl, //wordpress url thing
            "aoColumns" : [
                { sWidth: '100px' },    //images
                { sWidth: '300px' },    //address
                { sWidth: '70px' },     //zip
                { sWidth: '100px' },     //type
                { sWidth: '70px' },     //listing
                { sWidth: '90px' },     //property
                { sWidth: '60px' },     //beds
                { sWidth: '60px' },     //baths
                { sWidth: '70px' },     //price
                { sWidth: '100px' },    //sqft
                { sWidth: '100px' }     //available
            ], 
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value" : "datatable_ajax"} );
                aoData = my_listings_search_params(aoData);
            }
        });
    
    // prevents default on search button
    $('#pls_admin_my_listings').live('change', function(event) {
        event.preventDefault();
        my_listings_datatable.fnDraw();
    });

    // prevents default on search button
    $('tr.odd, tr.even').live('mouseover', function(event) {
        $(this).find(".row_actions").show();
    });
    // prevents default on search button
    $('tr.odd, tr.even').live('mouseout', function(event) {
        $(this).find(".row_actions").hide();
    });
    // parses search form and adds parameters to aoData
    function my_listings_search_params (aoData) {
        $.each($('#pls_admin_my_listings:visible').serializeArray(), function(i, field) {
            aoData.push({"name" : field.name, "value" : field.value});
        });
        return aoData;
    }



});



