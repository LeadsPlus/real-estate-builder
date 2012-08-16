<script type="text/javascript">
    jQuery(document).ready(function($) {
        var markers = [];
        var my_listings_datatable = $('#placester_listings_list').dataTable( {
            "bFilter": false,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            'sPaginationType': 'full_numbers',
            "sAjaxSource": info.ajaxurl, //wordpress url thing
            "fnServerData": function ( sSource, aoData, fnCallback ) {
                aoData.push( { "name": "action", "value" : "pls_listings_ajax"} );
                aoData = my_listings_search_params(aoData);
                $.ajax({
                    "dataType" : 'json',
                    "type" : "POST",
                    "url" : sSource,
                    "data" : aoData,
                    "success" : function(ajax_response) {
                        if (ajax_response && ajax_response['aaData']) {

                            custom_total_results(ajax_response);
                            
                            if (typeof pls_google_map !== 'undefined') {
                            pls_clear_markers(pls_google_map);
                            
                              if (typeof window['google'] != 'undefined') {
                                for (var listing in ajax_response['aaData']) {
                                    var listing_json = ajax_response['aaData'][listing][1];
                                    pls_create_listing_marker(listing_json, pls_google_map);
                                }
                              }
                            }
                          };
                        //required to load the datatable
                       fnCallback(ajax_response);
                       update_favorites_through_cache();
                    }
                });
            } 
        });

        //save as a reference.
        window.my_listings_datatable = my_listings_datatable;

        // prevents default on search button
        $('.<?php echo $search_class ?>, #sort_by, #sort_dir').live('change submit', function(event) {
            event.preventDefault();
            my_listings_datatable.fnDraw();
        });

        // parses search form and adds parameters to aoData
        function my_listings_search_params (aoData) {
            $.each($('.<?php echo $search_class ?>, .sort_wrapper').serializeArray(), function(i, field) {
                aoData.push({"name" : field.name, "value" : field.value});
            });
            aoData.push({"name": "context", "value" : $('#context').attr('class')});
            return aoData;
        }

        function custom_total_results (ajax_response) {
            $('#pls_num_results').html(ajax_response.iTotalDisplayRecords);
        }

        function update_favorites_through_cache () {
            $.post(info.ajaxurl, {action: 'get_favorites'}, function(data, textStatus, xhr) {
                if (data) {
                    $('#pl_add_remove_lead_favorites .pl_prop_fav_link').each(function(index) {
                        var flag = false;
                        for (var i = data.length - 1; i >= 0; i--) {
                            //this listing should be a favorite
                            if ($(this).attr('href') == ('#' + data[i].id) ) {
                                if ($(this).attr('id') == 'pl_add_favorite') {
                                    $(this).hide();
                                } else {
                                    $(this).show();
                                };
                                flag = true;
                            } 
                        };
                        //this listing shouldn't be a favorite
                        if (!flag) {
                            if ($(this).attr('id') == 'pl_add_favorite') {
                                $(this).show();
                            } else {
                                $(this).hide();
                            };
                        };
                    });     
                };
            }, 'json');
        }

        //datepicker
        $("input#metadata-max_avail_on_picker, #metadata-min_avail_on_picker").datepicker({
                showOtherMonths: true,
                numberOfMonths: 2,
                selectOtherMonths: true
        });
    });
</script>