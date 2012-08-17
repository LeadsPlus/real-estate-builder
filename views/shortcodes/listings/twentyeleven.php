<style type="text/css">
  section.lu {
    margin-bottom: 0px;
    padding: 0px;
    font-family: Georgia, "Bitstream Charter", serif;
  }
  div.lu-left {
    float: left;
  }
  div.lu-left img {
    /*border: 1px solid #999999;*/
  }
  div.lu-right {
    width: 360px;
    margin-left: 15px;
    float: left;
  }
  #content div.head_add h4 {
    margin: 0px 0px 6px 0px;
    padding: 0px;
    font-size: 18px;
    font-weight: bold; 
    font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
  }
  #content div.head_add h4 a {
    color: black;
    text-decoration: none; 
  }
  #content div.head_add h4 a:visited {
    color: black; 
  }
  #content div.head_add h4 a:hover {
    color: #0066ff; 
  }
  #content div.lu-right p.price {
    /*padding: 5px 8px 5px 8px;*/
    font-size: 14px;
    margin-bottom: 10px;
  }
  #content div.lu-right p.price span {
    font-weight: bold; 
  }
  #content div.lu-right p.mls {
    padding: 0px;
    color: #666666;
    font-size: 12px;
    margin-bottom: 0px;
    clear: both; 
  }
  #content div.lu-right p.desc {
    margin-bottom: 10px;
    padding: 0px;
    font-size: 14px;
    line-height: 17px;
    max-height: 50px;
    clear: both;
    overflow: hidden;
  }
  #content div.lu-right .lu-details {
    margin-top: 0px; 
  }
  #content div.lu-right ul {
    margin: 0px;
    padding: 0px 0px 0px 4px;
    width: 240px; 
  }
  #content div.lu-right ul li {
    list-style: none;
    float: left;
    margin-right: 10px;
    font-size: 14px;
    font-weight: bold;
  }
  #content div.lu-right ul li span {
    color: black;
    font-weight: normal;
  }
  #content div.lu-right .details {
    font-size: 15px;
  }
  
  /*#work_please { margin: 0px !important;}*/
  
  /* Styles that we need to override... */
  #content .sorting_1 {
    padding: 0px 24px 24px 0px;
  }
  .dataTables_info {
    font-family: "Helvetica Neue";
    font-size: 13px;
    margin: -21px 0px 21px 0px;
  }
  .dataTables_paginate {
    padding-bottom: 15px;
  }
  .dataTables_paginate * {
    font-family: "Helvetica Neue";
    font-size: 14px;
    padding-right: 9px;
    color: #888;
    cursor: pointer;
  }
  .dataTables_paginate .previous {
    margin: 0px 18px 0px 180px;
  }
  .dataTables_paginate .next {
    margin: 0px 180px 0px 18px;
  }
  .dataTables_paginate span a {
    padding: 0px 10px 0px 10px;
  }
  .dataTables_paginate span a.paginate_active {
    font-weight: bold;
  }
  #placester_listings_list {
    margin: 0px -1px 0px 0px !important;
  }
</style>

<section class="lu">
	
  <div class="head_add">
    <h4><a href="[url]">[address] [locality], [region]</a></h4>
  </div>

  <div>    
  	<div class="lu-left">
  		[image]
  	</div><!--lu-left-->

  	<div class="lu-right">
      <div class="lu-details">
        <ul>
          <li>[beds]<span> Bed(s)</span></li>
          <li>[baths]<span> Bath(s)</span></li>
          <li>[sqft]<span> Sqft</span></li>
        </ul>

        <p class="mls">MLS #: [mls_id]</p>
      </div><!--lu-details-->
    	
      <p class="price">Price: <span>$500</span></p>

  		<p class="desc">[desc]</p>

  		<div class="clearfix"></div>

  		<a class="details" href="[url]">View Listing Details</a>
  	</div><!--lu-right-->	
  </div>

</section><!--LU-->