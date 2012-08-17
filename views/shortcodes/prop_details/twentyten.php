<style type="text/css">
  .map {
    margin-top: 10px;
    border: 1px solid #DBDBDB;
    padding: 10px; 
    width: 590px;
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
  .entry-content input {
    margin: 0px !important;
  }
  .entry-title, .entry-meta {
    display: none !important;
  }
  .entry-utility {
    visibility: hidden;
  }
  .property-details {
    float: left;
  }
  .property-details-wrapper {
    width: 600px;
    padding-bottom: 20px;
    margin: inherit !important;
    font-family: Georgia, "Bitstream Charter", serif;
  }
  .property-details-wrapper * {
    color: #333;
  }
  #content .property-details-wrapper .prop-title {
    font-weight: bold;
    font-size: 21px;
    font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
    margin-bottom: 20px;
    color: black;
  }
  .property-details-wrapper > div {
    margin-bottom: 24px;
  }
  #content .property-details-wrapper h3 {
    font-weight: bold;
    font-size: 15px;
    font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
    margin-bottom: 7px;
  }
  .property-details-wrapper .prop-desc {
    font-size: 14px;
  }
  #content .property-details-wrapper .prop-desc * {
    clear: both;
    margin-bottom: 35px;
  }
  .property-details-wrapper .prop-info {
    float: left;
    margin-left: 20px;
  }
  .property-details-wrapper .prop-info ul li {
    margin-bottom: 7px;
    font-size: 14px;
  }
  .property-details-wrapper .prop-info li span {
    font-weight: bold;
  }
  .property-details-wrapper .prop-image {
    float: left;
    margin-bottom: 15px;
  }
  /*.property-bottom-nav {
    padding-top: 25px;
    margin-bottom: 30px !important;
    font-family: 'Helvetica Neue', Arial, Helvetica, 'Nimbus Sans L', sans-serif;
    font-size: 12px;
    color: #888;
    clear: both;
  }
  .property-bottom-nav .prev {
    float: left;
  }
  .property-bottom-nav .next {
    float: right;
  }
  .property-bottom-nav a {
    text-decoration: none;
    color: #888;
  }
  .property-bottom-nav a:hover, a:active {
    color: #FF4B33;
  }*/
</style>

<div class="property-details-wrapper">
  <h1 class="prop-title">[address] [locality], [region]</h1>
  
  <div class="prop-image">[image width="390" height="260"]</div>

  <div class="prop-info">
      <h3>Basic Details</h3>
      <ul>
          <li>Bed(s): <span>[beds]</span></li>
          <li>Bath(s): <span>[baths]</span></li>
          <li>Half Bath(s): <span>[half_baths]</span></li>
          <li>Price: <span>[price]</span></li>
          <li>Square Feet: <span>[sqft]</span></li>
          <!-- <li>Type: <span>[listing_type]</span></li> -->
          <li>MLS #: <span>[mls_id]</span></li>
      </ul>
  </div>
  
  <div class="prop-desc">
    <p>[desc]</p>
  </div>
  
  <div class="map-wrapper">
      <h3>Property Map</h3>
      <div class="map">
        [map]
      </div>
  </div>
</div>
