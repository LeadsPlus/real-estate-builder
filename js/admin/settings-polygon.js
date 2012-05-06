$(document).ready(function($) {
	//Polygons Start Here
 	var poly;
	var map;
	var neighborhood;
	var markers = [];

	google.maps.event.addDomListener(window, 'load', initialize);
	function initialize() {
	  var chicago = new google.maps.LatLng(41.879535, -87.624333);
	  var myOptions = {
	    zoom: 7,
	    center: chicago,
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  };

	  map = new google.maps.Map(document.getElementById('polygon_map'), myOptions);

	  var polyOptions = {
	    strokeColor: '#000000',
	    strokeOpacity: 1.0,
	    strokeWeight: 3,
	    editable: true
	  }
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
		neighborhood = new google.maps.Polygon({paths: coords,strokeColor: "#FF0000",strokeOpacity: 0.8,strokeWeight: 2,fillColor: "#FF0000",fillOpacity: 0.35});
		neighborhood.setMap(map);
		$('.polygon_controls').show();
	}

	function clearPolyLine () {
		removePolyLine();
		if (neighborhood) {
			neighborhood.setMap(null);
			neighborhood = null;
			$('.polygon_controls').hide();
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
			var form_values = {};
			$.each($('.polygon_controls form').serializeArray(), function(i, field) {
				form_values[field.name] = field.value;
    		});
    		form_values['polygon[border][color]'] = $('#polygon_border div').css('background-color');
    		form_values['polygon[fill][color]'] = $('#polygon_fill div').css('background-color');
    		neighborhood.setOptions({
    			strokeColor: form_values['polygon[border][color]'],
    			strokeOpacity: form_values['polygon[border][opacity]'],
    			strokeWeight: form_values['polygon[border][weight]'],
    			fillColor: form_values['polygon[fill][color]'],
    			fillOpacity: form_values['polygon[fill][opacity]']
    		});
    		customTxt = "<div>"+form_values['polygon[name]']+"</div>"
            var bounds = new google.maps.LatLngBounds();
            var polygonCoords = neighborhood.getPath();
            for (i = 0; i < polygonCoords.length; i++) {
			  bounds.extend(polygonCoords.getAt(i));
			}
			var center = bounds.getCenter();
            txt = new TxtOverlay(center,customTxt,"customBox",map );
    	}
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

	function TxtOverlay(pos, txt, cls, map){

	    // Now initialize all properties.
	    this.pos = pos;
	    this.txt_ = txt;
	    this.cls_ = cls;
	    this.map_ = map;

	    // We define a property to hold the image's
	    // div. We'll actually create this div
	    // upon receipt of the add() method so we'll
	    // leave it null for now.
	    this.div_ = null;

	    // Explicitly call setMap() on this overlay
	    this.setMap(map);
	}

	TxtOverlay.prototype = new google.maps.OverlayView();

	TxtOverlay.prototype.onAdd = function(){

	    // Note: an overlay's receipt of onAdd() indicates that
	    // the map's panes are now available for attaching
	    // the overlay to the map via the DOM.

	    // Create the DIV and set some basic attributes.
	    var div = document.createElement('DIV');
	    div.className = this.cls_;

	    div.innerHTML = this.txt_;

	    // Set the overlay's div_ property to this DIV
	    this.div_ = div;
	    var overlayProjection = this.getProjection();
	    var position = overlayProjection.fromLatLngToDivPixel(this.pos);
	    div.style.left = position.x + 'px';
	    div.style.top = position.y + 'px';
	    // We add an overlay to a map via one of the map's panes.

	    var panes = this.getPanes();
	    panes.floatPane.appendChild(div);
	}
	TxtOverlay.prototype.draw = function(){


	    var overlayProjection = this.getProjection();

	    // Retrieve the southwest and northeast coordinates of this overlay
	    // in latlngs and convert them to pixels coordinates.
	    // We'll use these coordinates to resize the DIV.
	    var position = overlayProjection.fromLatLngToDivPixel(this.pos);


	    var div = this.div_;
	    div.style.left = position.x + 'px';
	    div.style.top = position.y + 'px';



	}
	//Optional: helper methods for removing and toggling the text overlay.  
	TxtOverlay.prototype.onRemove = function(){
	    this.div_.parentNode.removeChild(this.div_);
	    this.div_ = null;
	}
	TxtOverlay.prototype.hide = function(){
	    if (this.div_) {
	        this.div_.style.visibility = "hidden";
	    }
	}

	TxtOverlay.prototype.show = function(){
	    if (this.div_) {
	        this.div_.style.visibility = "visible";
	    }
	}

	TxtOverlay.prototype.toggle = function(){
	    if (this.div_) {
	        if (this.div_.style.visibility == "hidden") {
	            this.show();
	        }
	        else {
	            this.hide();
	        }
	    }
	}

	TxtOverlay.prototype.toggleDOM = function(){
	    if (this.getMap()) {
	        this.setMap(null);
	    }
	    else {
	        this.setMap(this.map_);
	    }
	}
});