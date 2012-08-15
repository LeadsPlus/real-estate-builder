<style type="text/css">
  section.content-list {
    padding: 0 10px; 
    width: inherit;
  }
  .blue {
    color: #287aac;
  }
  ul.basic-info {
	margin: 0;
	padding: 0;
	list-style-type: none;
	margin: 0 0 20px 0;
	font-size: 0.9em; 
  }
  div.details-thumb {
	width: 630px;
	margin: 26px 0; 
  }
  section #property-details-main-image {
    margin: 15px 0;
    float: left; 
  }
  section.right-details {
    width: 356px;
    float: left; 
  }
  div.thumbs {
    padding: 5px 5px;
    background-color: white;
    border: 1px solid #e0e0e0; 
  }
  section.desc-info {
    margin: 25px 0 0 0; 
  }
  section.desc-info p {
    font-size: 0.9em;
    line-height: 1.3; 
  }
  .map {
    margin-top: 10px;
    border: 1px solid #DBDBDB;
    padding: 10px; 
    width: inherit;
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

<section class="content-list">

	<h3 class="blue">[full_address]</h3>
	<ul class="basic-info">
		<li>Beds: [beds]</li>
		<li>Baths: [baths]</li>
		<li>Half Baths: [half_baths]</li>
		<li>Price: [price]</li>
		<li>MLS #: [mls_id]</li>
	</ul>
	<div class="details-thumb"><!--start of details-thumb-->

		<div id="property-details-main-image">
			[image]
		</div>

		<section class="right-details">
			<div class="map">
				[map]
			</div>

			<section class="desc-info">
				<h6>Description</h6>
				<p>[desc]</p>
			</section>
		</section>
		
		<div class="clr"></div>

	</div><!--end of details-thumb-->

	<div class="clr"></div>

</section>
