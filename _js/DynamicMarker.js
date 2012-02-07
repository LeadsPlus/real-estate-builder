/*
 * Namespace
 */
dynamicMarker = {};

markerUniqueId = 0;

/*
 * MarkerFactory class.
 * creates marker object based on data provided
 *
 * @param array options
 */
dynamicMarker.MarkerFactory = 
    function(options)
    {
        this._getMarkerClassFunctor = options["getMarkerClassFunctor"];
        this._getWebServiceUrlFunctor = options["getWebServiceUrlFunctor"];
        this._getLoadingHtmlFunctor = options["getLoadingHtmlFunctor"];
        this._width = options["markerWidth"];
        this._height = options["markerHeight"];
        this.markerClickFunctor = options["markerClickFunctor"];
        this.markerHoverFunctor = options["markerHoverFunctor"];
        this.reloadMarkersFunctor = options["reloadMarkersFunctor"];
    };



/*
 * Creates marker based on marker data and returns it.
 *
 * @param string id
 * @param array markerData
 * @return object
 */
dynamicMarker.MarkerFactory.prototype.createMarker =
    function(id, markerData)
    {
        var p = new google.maps.LatLng(
            markerData.location.coords.latitude, 
            markerData.location.coords.longitude);

        var html = 
            "<div class='" + this._getMarkerClassFunctor(markerData) + 
            "'>&nbsp;</div>";

        return new com.redfin.FastMarker(id, p,
            html, null, "auto", -this._width / 2, -this._height);
    };




/*
 * Returns "map tile loading" html.
 *
 * @return string
 */
dynamicMarker.MarkerFactory.prototype.getLoadingHtml = 
    function()
    {
        return this._getLoadingHtmlFunctor();
    };



/*
 * Sends request to webservice and calls onSuccessFunctor once data received.
 *
 * @param int zoom
 * @param object pLT
 * @param object pRB
 * @param object onSuccessFunctor
 */
dynamicMarker.MarkerFactory.prototype.sendMarkerDataRequest =
    function(zoom, pLT, pRB, onSuccessFunctor)
    {
        var min_lat = pRB.lat();
        var min_lng = pLT.lng();
        var max_lat = pLT.lat();
        var max_lng = pRB.lng();
  
        // only local url, or you'll get "access deined"
        var url = this._getWebServiceUrlFunctor(zoom, min_lat, min_lng, max_lat, max_lng);
        $.ajax(
            { 
               url: url, 
               context: this, 
               dataType: "json",
               success: onSuccessFunctor,
               error: function() { alert('error'); }
            });
    }



/*
 * Tile class.
 *
 * @param object markerFactory
 * @param int zoom
 * @param object coord
 */
dynamicMarker.Tile = 
    function(markerFactory, zoom, coord)
    {
        this._markerFactory = markerFactory;
        this._zoom = zoom;
        this._coord = coord;
        this._markers = null;
        this._markersVisible = false;
        this._htmlObject = null;

        var scaleFactor = Math.pow(2, this._zoom) / 256;
        this._worldPointLT = new google.maps.Point(this._coord.x /  scaleFactor, 
            this._coord.y / scaleFactor);
        this._worldPointRB = new google.maps.Point((this._coord.x + 1) / scaleFactor,
            (this._coord.y + 1) / scaleFactor);
    };



/*
 * Creates (if not exists yet) and returns view object of this tile
 *
 * @param object map
 * @param object ownerDocument
 * @return object
 */
dynamicMarker.Tile.prototype.createView =
    function(map, ownerDocument)
    {
        if (this._htmlObject == null)
            this._createHtmlObject(ownerDocument);

        return this._htmlObject;
    };



/*
 * Returns if markers of this tile are visible now
 *
 * @return bool
 */
dynamicMarker.Tile.prototype.isMarkersVisible =
    function()
    {
        return this._markersVisible;
    }



/*
 * Returns if tile is visible for passed world coordinates
 *
 * @param int zoom
 * @param object worldLT
 * @param object worldRB
 * @return bool
 */
dynamicMarker.Tile.prototype.isVisible =
    function(zoom, worldLT, worldRB)
    {
        if (this._zoom == zoom)
            if ((this._worldPointRB.x >= worldLT.x && 
                 this._worldPointLT.x <= worldRB.x) &&
                (this._worldPointRB.y >= worldLT.y && 
                 this._worldPointLT.y <= worldRB.y))
                return true;

        return false;
    };



/*
 * Returns if passed coordinate if left-top of this tile
 *
 * @param object coord
 * @param int zoom
 * @return bool
 */
dynamicMarker.Tile.prototype.isMyCoordinate = 
    function(coord, zoom)
    {
        return (this._coord.x == coord.x && 
            this._coord.y == coord.y && 
            this._zoom == zoom);
    };



/*
 * Creates HTML object of tile
 *
 * @param object ownerDocument
 * @return object
 */
dynamicMarker.Tile.prototype._createHtmlObject =
    function(ownerDocument)
    {
        var div = ownerDocument.createElement("div");
        div.style.width = "256px";
        div.style.height = "256px";
        
        this._htmlObject = div;
    };



/*
 * Invalidates all tile's cached data
 */
dynamicMarker.Tile.prototype.invalidate =
    function()
    {
        this._markers = null;
        this._markersVisible = false;
    };



/*
 * Asks to load markers to this tile.
 * Request to webservice is made and once response is received markers are created
 *
 * @param object map
 * @param object mapOverlay
 */
dynamicMarker.Tile.prototype.queryMarkersVisible =
    function(map, mapOverlay)
    {
        this._markersVisible = true;

        if (this._markers != null)
        {
            return;
        }

        var loadingHtml = this._markerFactory.getLoadingHtml();
        var div = document.createElement("div");
        div.style.width = "254px";
        div.style.height = "254px";
        div.style.margin = "-8px 0 0 0";

        div.innerHTML = 
            "<div style='vertical-align: middle; display: table-cell; height: 254px; text-align: center; width: 254px'>" + 
            loadingHtml + "</div>";
        this._loadingObject = div;
        this._htmlObject.appendChild(div);

        var scaleFactor = Math.pow(2, this._zoom) / 256;
        var pLT = map.getProjection().fromPointToLatLng(this._worldPointLT);
        var pRB = map.getProjection().fromPointToLatLng(this._worldPointRB);

        var thisObj = this;
        this._markerFactory.sendMarkerDataRequest(this._zoom, pLT, pRB, 
            function(data)
            {
                thisObj._markers = data;
                mapOverlay.onMarkersAvailable();
            });
    };



/*
 * Returns markers of this tile
 *
 * @param object map
 */
dynamicMarker.Tile.prototype.getMarkers =
    function(map)
    {
        // create markers

        var markers = [];
        if (this._markers != null)
        {
            if (this._htmlObject != null && this._loadingObject != null)
            {
                try
                {
                    this._htmlObject.removeChild(this._loadingObject);
                }
                catch (e)
                {}
                this._loadingObject = null;
            }

            for (var n = 0; n < this._markers.length; n++)
            {
                var o = this._markers[n];
                markerId = "marker_" + markerUniqueId;
                markerUniqueId++;
                var marker = this._markerFactory.createMarker(markerId, o);
                marker.customData = o;

                markers.push(marker);
            }
        }

        return markers;
    };



/*
 * Returns marker data of this tile
 *
 * @param object map
 */
dynamicMarker.Tile.prototype.getMarkersData =
    function(map)
    {
        return this._markers;
    };



/*
 * Switches markers off.
 */
dynamicMarker.Tile.prototype.setMarkersInvisible = 
    function()
    {
        this._markersVisible = false;
    };



/*
 * MarkersOverlay class. Contains and manages all the tiles and 
 * enables/disables them based on map position.
 *
 * @param array options
 */
dynamicMarker.MarkersOverlay = 
    function (options)
    {
        this.tileSize = new google.maps.Size(256, 256);

        this._maxCacheSize = 100;
        this._tilesCache = [];
        this._map = options["map"];
        this._markerFactory = options["markerFactory"];

        this._map.overlayMapTypes.insertAt(0, this);

        this._markersLayer = new com.redfin.FastMarkerOverlay(this._map, []);
        this._markersLayer.markerClickFunctor = this._markerFactory.markerClickFunctor;
        this._markersLayer.markerHoverFunctor = this._markerFactory.markerHoverFunctor;

        var thisObj = this;
        var initialized = false;
        var dragging = false;
        google.maps.event.addListener(this._map, "zoom_changed", function() 
            { 
                initialized = false; 
            });
        google.maps.event.addListener(this._map, "dragstart", function()
            {    
                dragging = true;
                initialized = true;
            });
        google.maps.event.addListener(this._map, "dragend", function()
            {
                dragging = false;
                thisObj._startLoadingMarkers();
            });
        google.maps.event.addListener(this._map, "center_changed", function()
            {
                if (!dragging)
                    initialized = false;
            });
        google.maps.event.addListener(this._map, "tilesloaded", function()
            {
                if (!initialized)
                {
                    initialized = true;
                    thisObj._startLoadingMarkers();
                }
            });
    };



/*
 * Returns projection object
 *
 * @return object
 */
dynamicMarker.MarkersOverlay.prototype.getProjection = function()
    {
        return this._markersLayer.getProjection();
    };



/*
 * Returns markers data of visible tiles
 *
 * @return array
 */
dynamicMarker.MarkersOverlay.prototype.getMarkersData = function()
    {
        markersData = []
        for (var n = 0; n < this._tilesCache.length; n++)
        {
            var tile = this._tilesCache[n];
            if (tile._markersVisible)
                markersData = markersData.concat(tile.getMarkersData());
        }

        return markersData;
    };



/*
 * Refreshes map.
 *
 * @param bool invalidateCache
 */
dynamicMarker.MarkersOverlay.prototype.refresh = function(invalidateCache)
    {
        if (invalidateCache)
            for (var n = 0; n < this._tilesCache.length; n++)
            {
                var tile = this._tilesCache[n];
                if (tile._markersVisible)
                    tile.invalidate();
            }

        this.reloadMarkersLayer();
        this._startLoadingMarkers();
    };



/*
 * Returns tile object by specified coordinate
 *
 * @param object coord
 * @param int zoom
 * @param object ownerDocument
 * @return object
 */
dynamicMarker.MarkersOverlay.prototype.getTile = 
    function(coord, zoom, ownerDocument) 
    {
        for (var n = 0; n < this._tilesCache.length; n++)
        {
            var tile = this._tilesCache[n];
            if (tile.isMyCoordinate(coord, zoom))
                return tile.createView(this._map, ownerDocument);
        }

        var tile = new dynamicMarker.Tile(this._markerFactory, zoom, coord);
        this._tilesCache.push(tile);
        this._clearTilesCache();
        return tile.createView(this._map, ownerDocument);
    };



/*
 * Called by map when tile is not visible
 *
 * @param object removed
 */
dynamicMarker.MarkersOverlay.prototype.releaseTile =
    function(removed)
    {};



/*
 * Handler called by tile when it gets marker data from webservice.
 * Map is refreshed in that case.
 * With delay because many tiles usually query data simultaneously,
 * but refresh operation is costly.
 */
dynamicMarker.MarkersOverlay.prototype.onMarkersAvailable =
    function()
    {
        if (this.countdown != null)
            clearTimeout(this.countdown);
        var thisObj = this;
        this.countdown = setTimeout(function()
            {
                thisObj.reloadMarkersLayer();
            }, 500);
        
    };



/*
 * Reloads layer with actually available markers
 */
dynamicMarker.MarkersOverlay.prototype.reloadMarkersLayer = function()
    {
        markers = []
        markersData = []
        for (var n = 0; n < this._tilesCache.length; n++)
        {
            var tile = this._tilesCache[n];
            if (tile._markersVisible)
            {
                markers = markers.concat(tile.getMarkers());
                markersData = markersData.concat(tile.getMarkersData());
            }
        }

        this._markersLayer._markers = markers;
        this._markersLayer.draw();
        this._markerFactory.reloadMarkersFunctor(markersData);
    };



/*
 * Asks tiles to start loading of markers
 */
dynamicMarker.MarkersOverlay.prototype._startLoadingMarkers =
    function()
    {
        var zoom = this._map.getZoom();
        var bounds = this._map.getBounds();
        var pMin = bounds.getSouthWest()
        var pMax = bounds.getNorthEast();
        var worldMin = this._map.getProjection().fromLatLngToPoint(pMin);
        var worldMax = this._map.getProjection().fromLatLngToPoint(pMax);

        var worldLT = new google.maps.Point(worldMin.x, worldMax.y);
        var worldRB = new google.maps.Point(worldMax.x, worldMin.y);

        changed = false;

        for (var n = 0; n < this._tilesCache.length; n++)
        {
            var tile = this._tilesCache[n];
            isVisible = tile.isVisible(zoom, worldLT, worldRB);
            if (isVisible != tile.isMarkersVisible())
            {
                if (isVisible)
                    tile.queryMarkersVisible(this._map, this);
                else
                    tile.setMarkersInvisible();
                changed = true;
            }
        }

        if (changed)
            this.reloadMarkersLayer();
        else
            this._markerFactory.reloadMarkersFunctor(null);
    };



/*
 * Clears cache
 */
dynamicMarker.MarkersOverlay.prototype._clearTilesCache =
    function()
    {
        if (this._tilesCache.length <= this._maxCacheSize)
            return;

        // delete oldest unused
        for (var n = 0; n < this._tilesCache.length; )
        {
            var tile = this._tilesCache[n];
            if (!tile.isMarkersVisible())
            {
                this._tilesCache.splice(n, 1);
                if (this._tilesCache.length <= this._maxCacheSize * 0.9)
                    break;
            }
            else
                n++;
        }
    };

