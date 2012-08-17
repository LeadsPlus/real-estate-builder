<style type="text/css">
  #full-search {
    margin-bottom: 30px;
    /*padding: 10px;*/
    /*width: 450px;*/
    /*background: -webkit-gradient(linear, left top, left bottom, from(#e7e7e7), to(#e4e4e4));
    background: -moz-linear-gradient(top, #e7e7e7, #e4e4e4);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e7e7e7', endColorstr='#e4e4e4');*/
    /*border: 1px dotted #d8d8d8;*/
    float: left;
    font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
  }
  #full-search .select-grp {
    /*float: left;*/
    margin-right: 30px;
    margin-bottom: 10px;
  }
  #full-search .select-grp select {
    width: 105px;
    margin: 0px;
  }
  #full-search label {
    display: block;
    margin-bottom: -6px;
  }
  #full-search h6 {
    margin: 0px;
    padding: 0px;
    text-transform: uppercase;
    font-size: 16px;
    margin-bottom: 7px;
    font-weight: bold; 
  }
  #full-search > div {
    /*width: 285px;*/
    float: left;
    margin: 10px 15px 0px 0px;
    /*padding-left: 20px; */
  }
  #full-search .form-grp {
    
  }
  .search_results {
    font-size: 15px; 
    font-weight: bold; 
    float: right; 
    font-family: "Helvetica Neue";
    padding-right: 20px;
  }
  
  /* Styles that we need to override... */
  #content tr.odd td {
    background: white !important;
  }
  #content tr td {
    border: none !important;
  }
  #content table {
    border: none !important;
  }
  div.lu-left img {
    border: none !important;
  }
  .sort_wrapper {
    /*clear: both;*/
  }
  .sort_item {
    float: left;
    padding-right: 15px;
  }
</style>

<section id="full-search">
  <!-- <div> -->
    <div class="form-grp">
      <h6>Location</h6>
    	<div class="select-grp">
      	<label>City</label>
    		[cities]
      </div>
      <div class="select-grp">
    		<label>State</label>
    		[states]
      </div>
      <div class="select-grp">  
    		<label>Zipcode</label>
    		[zips]
      </div>  
    </div>

    <div class="form-grp">
      <h6>Listing Type</h6>
      <div id="purchase_type_container" class="select-grp">
        <label>Transaction Type</label>
        [purchase_types]
      </div>
      <div class="select-grp">
        <label>Property Type</label>
        [property_type]
      </div>
      <div class="select-grp">
        <label>Zoning Type</label>
        [zoning_types]
      </div>
    </div>

    <div class="form-grp">
      <h6>Price Range</h6>
      <div id="min_price_container" class="select-grp">
        <label>Price From</label>
        [min_price]
      </div>
      <div id="max_price_container" class="select-grp">
        <label>Price To</label>
        [max_price]
      </div>
    </div>

  	<div class="form-grp">
  		<h6>Details</h6>
  		<div class="select-grp">
      	<label>Bed(s)</label>
  			[bedrooms]
      </div>
      <div class="select-grp">  
  			<label>Bath(s)</label>
  			[bathrooms]
      </div>  
  	</div>
    	
    <div class="clr"></div>
  <!-- </div> -->
		<!-- 
			<input type="submit" name="submit" value="Search Now!" id="search-button"> 
		-->
</section>
<div class="search_results">
  <span id="pls_num_results"></span> listing match your search
</div>