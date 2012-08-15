<style type="text/css">
  div#full-form {
    background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#dadada));
    background: -moz-linear-gradient(top, #f2f2f2, #dadada);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f2f2f2', endColorstr='#dadada');
    border-radius: 8px;
    -moz-border-radius: 8px;
    border: 1px solid #bfbfbf;
    padding: 20px;
    margin-bottom: 14px; 
    width: inherit;
  }
  div#full-form h6 {
    margin: 0px;
    padding: 0px;
    text-transform: uppercase;
    font-size: 18px;
    margin-bottom: 10px; 
  }
  div#full-form #full-search {
    color: white;
    font-size: 16px;
    background: #f99727;
    background: -webkit-gradient(linear, left top, left bottom, from(#a6d84a), to(#4dab23));
    background: -moz-linear-gradient(top, #a6d84a, #4dab23);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a6d84a', endColorstr='#4dab23');
    border: 1px solid #4eab23;
    padding: 6px 14px 6px 14px;
    font-weight: bold;
    margin: -20px 95px 0px 0px;
    cursor: pointer;
    float: right;
    text-shadow: 1px 1px black; 
  }
  div#full-form #full-search:hover {
    background: -webkit-gradient(linear, left top, left bottom, from(#4dab23), to(#a6d84a));
    background: -moz-linear-gradient(top, #4dab23, #a6d84a);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4dab23', endColorstr='#a6d84a'); 
  }
  div.form-l {
    width: 285px;
    float: left;
    margin: 0px 20px 0px 0px; 
  }
  div.form-m {
    width: 285px;
    float: left;
    margin: 0px 0px 0px 0px; 
  }
  div.form-r {
    width: 285px;
    float: right; 
  }
  div.form-r-l {
    float: left;
    width: 105px;
    margin-left: 20px; 
  }
  div.form-r-r {
    float: right;
    width: 140px;
    margin-left: 0px; 
  }
  div#full-form .slt-full select {
    padding: 4px;
    width: 265px;
    border: 1px solid #a09f9f;
    margin-bottom: 4px; 
  }
  div#full-form .slt-half select {
    padding: 4px;
    width: 195px;
    border: 1px solid #a09f9f;
    margin-bottom: 4px; 
  }
  div#full-form .slt-sma select {
    padding: 4px;
    width: 85px;
    border: 1px solid #a09f9f;
    margin-bottom: 4px; 
  }
  div#full-form label {
    margin: 5px 10px 0px 0px;
    font-size: 12px; 
  }
  div#full-form input.ticks {
    padding: 0px;
    margin: 20px 5px 0px 0px; 
  }
</style>

<div id="full-search">
  <div id="full-form">
    <div class="form-l">
      <h6>Location</h6>
      <label>City</label>
      <div class="slt-full">
        [cities]
      </div>
      <label>State</label>
      <div class="slt-full">
        [states]  
      </div>
      <label>Zip</label>
      <div class="slt-full">
        [zips]  
      </div>
    </div><!--form-l-->

    <div class="form-m">
      <h6>Listing Type</h6>
      <label>Zoning Type</label>
      <div class="slt-full">
        [zoning_types]
      </div>
      <label>Transaction Type</label>
      <div class="slt-full">
        [purchase_types]
      </div>
      <label>Property Type</label>
      <div class="slt-full">
        [property_type]
      </div>
    </div><!--form-m-->

    <div class="form-r">
      <div class="form-r-l">
        <h6>Details</h6>
        <label>Bed(s)</label>
        <div class="slt-sma">
          [bedrooms]  
        </div>   	
        <label>Bath(s)</label>
        <div class="slt-sma">
          [bathrooms]  
        </div>                  	              
      </div><!--form-r-l-->
      <div class="form-r-r">
        <h6>Price Range</h6>
        <label>Price From</label>
        <div class="slt-sma">
          [min_price] 
        </div>
        <label>Price To</label>
        <div class="slt-sma">
          [max_price] 
        </div>
      </div><!--form-r-r-->
      <div class="clr"></div>
    </div><!--form-r-->
    <div class="clearfix"></div>
  </div>
</div>    