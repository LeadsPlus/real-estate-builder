<style type="text/css">
  section.lu {
    margin: 0px;
    padding: 25px 0px 25px 0px;
    border-bottom: 1px dotted #cccccc; 
    font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
  }
  div.lu-left {
    float: left;
  }
  div.lu-left img {
    border: 1px solid #999999;
  }
  div.lu-left p.price {
    /*padding: 5px 8px 5px 8px;*/
    font-size: 14px;
  }
  div.lu-left p.price span {
    font-weight: bold; 
  }
  div.lu-left p.mls {
    margin: 100px 0 0 0; 
  }
  div.lu-right {
    width: 360px;
    margin-left: 15px;
    float: left;
  }
  div.lu-right h4 {
    margin: 0px;
    padding: 0px;
    font-size: 20px; 
  }
  div.lu-right h4 a {
    color: black;
    text-decoration: none; 
  }
  div.lu-right h4 a:visited {
    color: black; 
  }
  div.lu-right h4 a:hover {
    color: #0066ff; 
  }
  div.lu-right p.mls {
    margin: 0px;
    padding: 0px;
    color: #666666;
    font-size: 12px;
    margin-bottom: 10px; 
  }
  div.lu-right p.desc {
    margin: 0px;
    padding: 0px;
    font-size: 12px;
    line-height: 17px;
    height: 50px;
    overflow: hidden; 
  }
  div.lu-right .lu-details {
    margin-top: 10px; 
  }
  div.lu-right ul {
    margin: 0px;
    padding: 0px 0px 0px 4px;
    float: left;
    width: 240px; 
  }
  div.lu-right ul li {
    list-style: none;
    float: left;
    margin-right: 10px;
    font-size: 14px;
    text-transform: uppercase;
    font-weight: bold; 
  }
  div.lu-right ul li span {
    color: #666666;
    font-weight: lighter; 
  }

  div.lu-right .details {
    font-size: 14px;
  }
</style>

<section class="lu">
	
	<div class="lu-left">
		[image]
		<p class="price"><span>[price]</span></p>
	</div><!--lu-left-->

	<div class="lu-right">
		<h4><a href="[url]">[address] [locality], [region]</a></h4>

    <div class="lu-details">
      <ul>
        <li>[beds]<span> Bed(s)</span></li>
        <li>[baths]<span> Bath(s)</span></li>
        <li>[sqft]<span> Sqft</span></li>
      </ul>
    </div><!--lu-details-->

  	<p class="mls">MLS #: [mls_id]</p>
  	
		<p class="desc">[desc]</p>

		<div class="clearfix"></div>

		<a class="details" href="[url]">View Listing Details</a>
	</div><!--lu-right-->	

</section><!--LU-->