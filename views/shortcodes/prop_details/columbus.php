<style type="text/css">
  .map {
    margin-top: 10px;
    border: 1px solid #DBDBDB;
    padding: 10px; 
  }

  .map_wrapper .loading_overlay {
    background-color: #FFF;
    opacity: 0.4; 
  }
  .map_wrapper .loading_overlay div {
    margin: 100px auto;
    font-weight: bold;
    font-size: 16px;
    width: 150px;
    text-align: center; 
  }
  .map_wrapper .empty_overlay {
    background-color: #FFF;
    opacity: 0.4; 
  }
  .map_wrapper .lifestyle_form_wrapper {
    margin-top: 10px; 
  }
  .map_wrapper .lifestyle_form_wrapper .location_wrapper {
    float: left; 
  }
  .map_wrapper .lifestyle_form_wrapper .location_wrapper .location_select_wrapper {
    float: left;
    margin-right: 10px; 
  }
  .map_wrapper .lifestyle_form_wrapper .location_wrapper .location_select {
    float: left;
    margin-right: 10px; 
  }
  .map_wrapper .lifestyle_form_wrapper .checkbox_wrapper {
    float: left;
    margin-top: 10px; 
  }
  .map_wrapper .lifestyle_form_wrapper .checkbox_wrapper .lifestyle_checkbox_item {
    float: left;
    width: 180px;
    margin-right: 10px; 
  }
</style>

<h2>[address] [region] [locality]</h2>

<div class="clearfix"></div>

<div class="details-wrapper grid_4 alpha">[desc]</div>

<div class="details-wrapper grid_4 omega">
    <h3>Basic Details</h3>
    <ul>
        <li><span>Beds </span>[beds]</li>
        <li><span>Baths </span>[baths]</li>
        <li><span>Price </span>[price]</li>
        <li><span>Half Baths </span>[half_baths]</li>
        <li><span>Square Feet </span>[sqft]</li>
        <li><span>MLS Number: </span>[mls_id]</li>
    </ul>
</div>

<div class="map-wrapper grid_8 alpha">
    <h3>Property Map</h3>
    <div class="map">
    	[map]
    </div>
</div>