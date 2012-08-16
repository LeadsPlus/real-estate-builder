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
  h1.entry-title {
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 10px;
  }
  .property-details-wrapper {
    width: 600px;
    float: left;
    padding-bottom: 20px;
    margin: inherit !important;
    font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
  }
  .property-details-wrapper > div {
    margin-bottom: 24px;
  }
  .property-details-wrapper h3 {
    font-weight: bold;
  }
  .property-details-wrapper .prop-desc {
    font-size: 13px;
  }
  .property-details-wrapper .prop-info h3,ul,li {
    margin-bottom: 5px;
  }
</style>

<div class="property-details-wrapper">
  <h1 class="entry-title">[address] [region] [locality]</h1>

  <div class="clearfix"></div>

  <div class="prop-desc">[desc]</div>

  <div class="prop-info">
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

  <div class="map-wrapper">
      <h3>Property Map</h3>
      <div class="map">
        [map]
      </div>
  </div>
</div>
