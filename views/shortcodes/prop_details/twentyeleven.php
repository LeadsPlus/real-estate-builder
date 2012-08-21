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
    width: 185px;
    margin-right: 10px; 
  }
  .property-details {
    float: none;
  }
  .property-details-wrapper {
    width: 600px;
    padding-bottom: 20px;
    margin-bottom: 30px; !important;
    margin: 0 auto;
    font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
  }
  .property-details-wrapper * {
    color: #333;
  }
  #content .property-details-wrapper h1 {
    color: black;
    font-size: 28px;
    font-weight: bold;
    line-height: 48px;
    margin-bottom: 20px;
  }
  .property-details-wrapper > div {
    margin-bottom: 24px;
  }
  .property-details-wrapper h3 {
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 3px;
  }
  .property-details-wrapper .prop-desc {
    font-size: 15px;
  }
  .property-details-wrapper .prop-desc * {
    clear: both;
    padding-top: 20px;
    margin-bottom: 30px;
  }
  .property-details-wrapper .prop-info {
    float: left;
    margin-left: 20px;
  }
  .property-details-wrapper .prop-info ul li {
    margin-bottom: 7px;
    font-size: 15px;
  }
  .property-details-wrapper .prop-info li span {
    font-weight: bold;
  }
  .property-details-wrapper .prop-image {
    float: left;
    margin-bottom: 20px;
  }
  .property-bottom-nav {
    padding-top: 25px;
    margin-bottom: 30px !important;
    font-size: 12px;
    clear: both;
  }
  .property-bottom-nav .prev {
    float: left;
    padding-left: 20%;
  }
  .property-bottom-nav .next {
    float: right;
    padding-right: 20%;
  }
  .property-bottom-nav a {
    text-decoration: none;
    font-weight: bold;
    color: #1982D1;
  }
  .property-bottom-nav a:hover, a:active {
    text-decoration: underline;
  }
  .entry-title {
    display: none;
  }
  .entry-meta {
    visibility: hidden;
  }
  #slideshow ul.property-image-gallery {
    list-style-type: none;
    padding-left: 0px;
    margin: 0px;
    clear: both;
  }
  #slideshow ul.property-image-gallery li {
    float: left;
    margin-right: 12px; 
  }
  #content .amenities-section ul {
    /*padding-left: 12px;*/
    margin: 0px;
    /*min-height: 50px;*/
    min-width: 600px;
    list-style-type: none; 
  }
  #content .amenities-section ul li {
    padding: 10px 0px 10px 9px;
    width: 190px;
    height: 27px;
    float: left;
    overflow: hidden;
    font-size: 12.5px;
    line-height: 18px;
  }
  .amenities-section ul li span {
    font-weight: bold;
    padding-right: 3px;
  }
  .pad-top {
    padding-top: 15px;
  }
  .compliance-wrapper {
    font-size: 12px;
  }
</style>

<div class="property-details-wrapper">
  <h1>[address] [locality], [region]</h1>
  
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

  <div>
    [gallery]
  </div>
  
  <div class="prop-desc">
    <p>[desc]</p>
  </div>

  <div>
    <h3>Property Amenities</h3>
    [amenities type="list"]
  </div>
  
  <div>
    <h3 class="pad-top">Neighborhood Amenities</h3>
    [amenities type="ngb"]
  </div>

  <div>
    <h3 class="pad-top">Other Amenities</h3>
    [amenities type="uncur"]
  </div>

  <div class="map-wrapper">
      <h3>Neighborhood</h3>
      <div class="map">
        [map]
      </div>
  </div>

  <div>
    <h3 class="pad-top">Compliance</h3>
    [compliance]
  </div>
</div>
