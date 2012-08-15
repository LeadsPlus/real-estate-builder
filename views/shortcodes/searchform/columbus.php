<style type="text/css">
  #search-form-area {
    background-color: white; 
  }
  #full-search {
    margin: 20px;
    width: inherit;
    background: -webkit-gradient(linear, left top, left bottom, from(#e7e7e7), to(#e4e4e4));
    background: -moz-linear-gradient(top, #e7e7e7, #e4e4e4);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e7e7e7', endColorstr='#e4e4e4');
    border: 1px solid #d8d8d8;
    float: left;
    padding-bottom: 20px; }
  #full-search h3 {
    background: #4782bd;
    background: -webkit-gradient(linear, left top, left bottom, from(#4782bd), to(#5d98d3));
    background: -moz-linear-gradient(top, #4782bd, #5d98d3);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4782bd', endColorstr='#5d98d3');
    border: 1px solid #3d6995;
    color: white;
    text-shadow: 1px 1px black; 
  }
  #full-search label {
    display: block;
    margin-top: 5px; 
  }
  #full-search h6 {
    margin: 0px;
    padding: 0px;
    text-transform: uppercase;
    font-size: 16px;
    margin-bottom: 10px;
    text-shadow: 1px 1px white;
    font-weight: bold; 
  }
  #full-search .form-l {
    width: 285px;
    float: left;
    margin: 0px 20px 0px 0px;
    padding-left: 20px; 
  }
  #full-search .form-l, #full-search .form-m {
    padding-top: 20px; 
  }
  #full-search .form-l .chzn-container, #full-search .form-m .chzn-container {
    width: 200px !important; 
  }
  #full-search .form-l .chzn-drop, #full-search .form-m .chzn-drop {
    width: 198px !important; 
  }
  #full-search .form-l .chzn-drop .chzn-search input, #full-search .form-m .chzn-drop .chzn-search input {
    width: 163px !important; 
  }
  #full-search .form-m {
    width: 285px;
    float: left;
    margin: 0px 0px 0px 0px; 
  }
  #full-search .form-r {
    padding-top: 20px;
    width: 285px;
    float: right; 
  }
  #full-search .form-r .chzn-container {
    width: 100px !important; 
  }
  #full-search .form-r .chzn-drop {
    width: 98px !important; 
  }
  #full-search .form-r .chzn-drop .chzn-search input {
    width: 63px !important; 
  }
  #full-search .form-r-l {
    float: left;
    width: 90px; 
  }
  #full-search .form-r-r {
    float: right;
    width: 155px; 
  }
  #full-search #available-on {
    float: left;
    margin-top: 5px; 
  }
  #full-search #available-on .chzn-container {
    width: 230px !important; 
  }
  #full-search #available-on .chzn-drop {
    width: 228px !important; 
  }
  #full-search #available-on .chzn-drop .chzn-search input {
    width: 193px !important; 
  }
</style>

<section id="full-search">
  <h3>Search Listing</h3>
  <div id="full-form">
		
	<div class="form-l">
		<h6>Location</h6>
		<label>City</label>
		[cities]
		<label>State</label>
		[states]
		<label>Zipcode</label>
		[zips]
	</div><!--form-l-->
	
	<div class="form-m">
		<h6>Listing Type</h6>
		<label>Zoning Type</label>
		[zoning_types]
		<div id="purchase_type_container">
		  <label>Transaction Type</label>
		  [purchase_types]
		</div>
		<label>Property Type</label>
		[property_type]
	</div><!--form-m-->
	
	<div class="form-r">
		<div class="form-r-l">
			<h6>Details</h6>
			<label>Bed(s)</label>
			[bedrooms]
			<label>Bath(s)</label>
			[bathrooms]
		</div><!--form-r-l-->
		
		<div class="form-r-r">
			<h6>Price Range</h6>
			<div id="min_price_container">
			  <label>Price From</label>
			  [min_price]
  			</div>
	    	<div id="max_price_container">
			  <label>Price To</label>
			  [max_price]
	        </div>
		</div><!--form-r-r-->
		
		<div class="clr"></div>
		<div id="available-on">
			<!--<label>Available On</label>
			[available_on] -->
		</div>
	</div><!--form-r-->

		<!-- 
			<input type="submit" name="submit" value="Search Now!" id="search-button"> 
		-->
</div>