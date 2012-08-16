jQuery(document).ready(function($) {
    var markers = [];
    var my_fav_datatable = $('#placester_fav_list').dataTable( {
        "bFilter": false,
        "bProcessing": true,
        "bServerSide": true,
        'sPaginationType': 'full_numbers',
        "sServerMethod": "POST",
        "sAjaxSource": info.ajaxurl, //wordpress url thing
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push( { "name": "action", "value" : "pls_listings_fav_ajax"} );
            aoData = my_listings_search_params(aoData);
            $.ajax({
                "dataType" : 'json',
                "type" : "POST",
                "url" : sSource,
                "data" : aoData,
                "success" : function(ajax_response) {
                    if (ajax_response && ajax_response['aaData']) {
                        for (var current_marker in markers) {
                          markers[current_marker].setMap(null);
                        }
                        if (typeof window['google'] != 'undefined') {
                          markers = [];
                          var bounds = new google.maps.LatLngBounds();
                          for (var listing in ajax_response['aaData']) {
                              var listing_json = ajax_response['aaData'][listing][1];
                              marker = new google.maps.Marker({
                                  position: new google.maps.LatLng(listing_json['location']['coords'][0], listing_json['location']['coords'][1]),
                                  map: pls_google_map
                              });
                              marker.setMap(pls_google_map);
                              bounds.extend(marker.getPosition());
                              markers.push(marker);
                          }
                          pls_google_map.fitBounds(bounds);
                        }
                    };

                    //required to load the datatable
                   fnCallback(ajax_response)
                }
            });
        } 
    });

    //save as a reference.
    window.my_fav_datatable = my_fav_datatable;

    function my_listings_search_params (aoData) {
        aoData.push({"name": "context", "value" : $('#context').attr('class')});
        return aoData;
    }

});