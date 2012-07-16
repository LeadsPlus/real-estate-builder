$(document).ready(function($) {
	//Polygons Start Here
 	var poly;
	var map;
	var neighborhood;
	var markers = [];
	var form_values = {};
	var text;
	var polygon_listings_datatable;
	var other_polygons = [];

	google.maps.event.addDomListener(window, 'load', initialize);
	function initialize() {
	  	var styles = [{stylers: [{ visibility: "simplified" }]}];
		var polygonMapType = new google.maps.StyledMapType(styles,{name: "polygon"});
		var chicago = new google.maps.LatLng(41.879535, -87.624333);
		var myOptions = {
			zoom: 3,
			center: chicago,
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'polygon_map']
		};
		
		map = new google.maps.Map(document.getElementById('polygon_map'), myOptions);
		map.mapTypes.set('polygon', polygonMapType);
		map.setMapTypeId('polygon');

		var polyOptions = {strokeColor: '#000000',strokeOpacity: 1.0,strokeWeight: 3,editable: true}
		poly = new google.maps.Polyline(polyOptions);
		poly.setMap(map);
		google.maps.event.addListener(map, 'click', addLatLng);
	}

	function addLatLng(event) {
	  var path = poly.getPath();
	  path.push(event.latLng);
	  if (poly.getPath().getLength() == 1) {
	  	makePolyMarker({latLng : event.latLng }, path)	
	  };
	}

	function makePolyMarker (data, path) {
		console.log(data);
		// Add a new marker at the new plotted point on the polyline.
		var marker = new google.maps.Marker({ position: data.latLng, title: '#' + path.getLength(), map: map });
		markers.push(marker);
		google.maps.event.addListener(marker, 'click', function() {
			if (confirm("Do you want to close the polygon?")) {
				hide_create_overlay();
				closePolygon();
				removePolyLine();
			} 
		});	
	}

	function removePolyLine () {
		poly.setMap(null);
		for (var i = markers.length - 1; i >= 0; i--) {
			markers[i].setMap(null);
		}
		google.maps.event.clearListeners(map, 'click');
	}

	function closePolygon () {
		var coords = poly.getPath();
		var options = {};
		options['paths'] = coords;
		options['maps'] = map;
		updatePolygonSettings();
		options = $.extend(options, form_values);
		neighborhood = new google.maps.Polygon(options);
		neighborhood.setMap(map);
		$('.polygon_controls').show();
		PolyFormUpdate();
	}

	function clearPolyLine () {
		removePolyLine();
		if (neighborhood) {
			neighborhood.setMap(null);
			neighborhood = null;
			$('.polygon_controls').hide();
		};
		if (text) {
			text.setMap(null);	
		};
		var polyOptions = {strokeColor: '#000000',strokeOpacity: 1.0,strokeWeight: 3,editable: true}
	  	poly = new google.maps.Polyline(polyOptions);
	  	poly.setMap(map);
	  	google.maps.event.addListener(map, 'click', addLatLng);
	}

	function editPolyLine () {
		neighborhood.setMap(null);
		poly.setMap(map);
		var path = poly.getPath();
		makePolyMarker({latLng:poly.getPath().getAt(0)}, path);
	}

	function PolyFormUpdate() {
		if (neighborhood) {
    		updatePolygonSettings();
    		neighborhood.setOptions({
				strokeColor: form_values['[border][color]'],
				strokeOpacity: form_values['[border][opacity]'],
				strokeWeight: form_values['[border][weight]'],
				fillColor: form_values['[fill][color]'],
				fillOpacity: form_values['[fill][opacity]']
			});
			if (text) {
				text.setMap(null);
			};
    		customTxt = "<div>"+form_values['name']+"</div>"
            var bounds = new google.maps.LatLngBounds();
            var polygonCoords = neighborhood.getPath();
            for (i = 0; i < polygonCoords.length; i++) {
			  bounds.extend(polygonCoords.getAt(i));
			}
			var center = bounds.getCenter();
            text = new TxtOverlay(center,customTxt,"customBox",map );
    	}
	}

	function updatePolygonSettings () {
		$.each($('.polygon_controls form').serializeArray(), function(i, field) {
			form_values[field.name] = field.value;
		});
		form_values['[border][color]'] = $('#polygon_border div').css('background-color');
		form_values['[fill][color]'] = $('#polygon_fill div').css('background-color');
		
	}

	$('.polygon_controls form').live('change', function () {
		PolyFormUpdate();
	});

	$('.another_colorpicker').each(function(){
		var that = this; 
		$(this).ColorPicker({
			color: '#0000ff',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$(that).children('div').css('backgroundColor', '#' + hex);
				PolyFormUpdate();
			}
		});
	});

	$('#polygon_clear_drawing').live('click', function (event) {
		event.preventDefault();
		clearPolyLine();
	});

	$('#polygon_edit_drawing').live('click', function (event) {
		event.preventDefault();
		editPolyLine();
	});

	setTaxonomyDropdown();
	$('#poly_taxonomies').live('change', function () {
		setTaxonomyDropdown();
	});


	$('#polygon_save_drawing').live('click', function (event) {
		event.preventDefault();
		savePolygon();
	});

	function savePolygon() {
		var polygon_info = {};
		console.log(form_values);
		if (form_values['name'] == '') {
			alert('Neighborhood must have a name');
			return;
		};
		if (form_values[form_values['poly_taxonomies']] == 'false' ) {
			alert('Neighborhood must be assigned to an area');
			return;
		};
		if (form_values['id'] == '') {
			polygon_info['action'] = 'save_polygon';	
		} else {
			polygon_info['action'] = 'update_polygon';	
			polygon_info['id'] = form_values['id'];	
		};
		polygon_info['name'] = form_values['name'];
		polygon_info['tax'] = form_values['poly_taxonomies'];
		polygon_info['slug'] = form_values[form_values['poly_taxonomies']];
		polygon_info['settings'] = {}, polygon_info['settings']['border'] = {}, polygon_info['settings']['fill'] = {};
		polygon_info['settings']['border']['color'] = form_values['[border][color]'];
		polygon_info['settings']['border']['weight'] = form_values['[border][weight]'];
		polygon_info['settings']['border']['opacity'] = form_values['[border][opacity]'];
		polygon_info['settings']['fill']['color'] = form_values['[fill][color]'];
		polygon_info['settings']['fill']['opacity'] = form_values['[fill][opacity]'];
		polygon_info['vertices'] = [];
		for (var i = neighborhood.getPath().length - 1; i >= 0; i--) {
			polygon_info['vertices'][i] = {};
			polygon_info['vertices'][i]['lat'] = neighborhood.getPath().getAt(i).lat();
			polygon_info['vertices'][i]['lng'] = neighborhood.getPath().getAt(i).lng();
		};
		$.post(ajaxurl, polygon_info, function(data, textStatus, xhr) {
			if (data && data.message) {
				$('#polygon_ajax_messages').html(data.message);
				clearPolyLine();
				polygon_listings_datatable.fnDraw();
				show_neighborhood_areas();
				setTimeout(function () {
					$('#polygon_ajax_messages').html('');
				}, 700);
			} else {
				$('#polygon_ajax_messages').html(data.message);
			};
		},'json');	
	}

	$('#remove_item').live('click', function (event) {
		event.preventDefault();
		if (!confirm('Are you sure you want to remove this neighborhood area?')) {
			return;
		};
		var polygon = {};
		polygon['id'] = $(this).parent().find('input#id').val();
		polygon['action'] = 'delete_polygon';
		$.post(ajaxurl, polygon, function(data, textStatus, xhr) {
		  console.log(data)
		},'json');
		$(this).parents('tr').remove();
	});

	$('#edit_item').live('click', function (event) {
		event.preventDefault();
		var polygon = {};
		console.log($(this).parent().find('input#id'));
		polygon['id'] = $(this).parent().find('input#id').val();
		polygon['action'] = 'get_polygon';
		$.post(ajaxurl, polygon, function(data, textStatus, xhr) {
			if (data && data.result) {
				clearPolyLine();
				var path = poly.getPath();
				for (var i = data.polygon.vertices.length - 1; i >= 0; i--) {
					path.push(new google.maps.LatLng(data.polygon.vertices[i].lat, data.polygon.vertices[i].lng));
				};
				$('.polygon_controls form #name').val(data.polygon.name);
				$('.polygon_controls form #[border][weight]').val(data.polygon.settings.border.weight);
				$('.polygon_controls form #[border][opacity]').val(data.polygon.settings.border.opacity);
				$('.polygon_controls form #polygon_border div').val(data.polygon.settings.border.color);
				$('.polygon_controls form #[fill][opacity]').val(data.polygon.settings.fill.opacity);
				$('.polygon_controls form #polygon_fill div').val(data.polygon.settings.fill.color);
				$('.polygon_controls form #poly_taxonomies').val(data.polygon.tax);
				$('.polygon_controls form .poly_taxonmy_values#' + data.polygon.tax).val(data.polygon.slug);
				$('#edit_id').val(data.polygon.id);
				closePolygon();
				removePolyLine();
			} else {
				$('#polygon_ajax_messages').html(data.message);
			};
		}, 'json');
	});

	function setTaxonomyDropdown () {
		var value = $('#poly_taxonomies').val();
		$('.poly_taxonmy_values').hide();
		$('.poly_taxonmy_values#' + value).show();
	}


	var polygon_listings_datatable = $('#polygon_listings_list').dataTable( {
            "bFilter": false,
            "bProcessing": true,
            // "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": ajaxurl, 
            "iDisplayLength" : 10,
            "aoColumns" : [
                { sWidth: '90px' },    //name
                { sWidth: '90px' },    //type
                { sWidth: '100px' },     //neighborhood
                { sWidth: '50px' },    //edit
                { sWidth: '60px' }    //remove
            ], 
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value" : "get_polygons_datatable"} );
            }
        });

	var all_polygon_coords = new google.maps.LatLngBounds();
	$('.show_neighborhood_areas form').live('change', function () {
		all_polygon_coords = new google.maps.LatLngBounds();
		show_neighborhood_areas();
	});

	
	function show_neighborhood_areas () {
		var form_values = {};
		form_values['action'] = 'get_polygons_by_type';
		$.each($('.show_neighborhood_areas form').serializeArray(), function(i, field) {
			form_values[field.name] = field.value;
		});
		$.post(ajaxurl, form_values, function(data, textStatus, xhr) {
			if (data) {
				for (var j = other_polygons.length - 1; j >= 0; j--) {
					other_polygons[j].setMap(null);
				};
				for (item in data) {
					var coords = [];
					for (var k = data[item].vertices.length - 1; k >= 0; k--) {
						coords.push(new google.maps.LatLng(data[item].vertices[k].lat, data[item].vertices[k].lng));
					};
					var polygon = new google.maps.Polygon({
					    paths: coords,
					    strokeColor: data[item].settings.border.color,
					    strokeOpacity: data[item].settings.border.opacity,
					    strokeWeight: data[item].settings.border.weight,
					    fillColor: data[item].settings.fill.color,
					    fillOpacity: data[item].settings.fill.opacity
					  });
					polygon.setMap(map);
					customTxt = data[item].name;
					var bounds = new google.maps.LatLngBounds();
		            var polygonCoords = polygon.getPath();
		            for (p = 0; p < polygonCoords.length; p++) {
					  	bounds.extend(polygonCoords.getAt(p));
					  	all_polygon_coords.extend(polygonCoords.getAt(p));
					}
					map.fitBounds(all_polygon_coords);
		            other_text = new TxtOverlay(bounds.getCenter(),customTxt,"polygon_text_area",map );
					other_polygons.push(polygon);
					other_polygons.push(other_text);
				};
			};
		}, 'json');
	}

	$('#clear_created_neighborhoods').live('click', function (event) {
		event.preventDefault();
		for (var j = other_polygons.length - 1; j >= 0; j--) {
					other_polygons[j].setMap(null);
				};
	});

	$('#start_map_address_search').live('click', function (event) {
		event.preventDefault();
		geocoder = new google.maps.Geocoder();
		var address = document.getElementById("map_address_input").value;
	    geocoder.geocode( { 'address': address}, function(results, status) {
		      if (status == google.maps.GeocoderStatus.OK) {
		        map.setCenter(results[0].geometry.location);
		        map.setZoom(13);
		      } else {
		        alert("Geocode was not successful for the following reason: " + status);
		      }
		    });
		});

	$('#create_new_polygon').live('click', function(event) {
		event.preventDefault();
		show_create_overlay();
	});
	$('#close_create_overlay').live('click', function(event) {
		event.preventDefault();
		hide_create_overlay();
	});

	function show_create_overlay () {
		$('#create_prevent_overlay').show();
		$('#create_new_polygon').hide();
	}

	function hide_create_overlay () {
		$('#create_prevent_overlay').hide();
		$('#create_new_polygon').show();
	}
});