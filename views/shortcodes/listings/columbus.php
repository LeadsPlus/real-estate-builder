<style type="text/css">
  section.lu {
    margin: 0px;
    padding: 25px 0px 25px 0px;
    border-bottom: 1px dotted #cccccc;
    width: inherit; 
  }
  div.lu-left {
    float: left;
    width: 170px;
    float: left;
    position: relative; }
  div.lu-left img {
    border: 1px solid #999999;
    position: absolute; }
  div.lu-left p.price {
    position: absolute;
    background: #5bb422;
    color: white;
    padding: 5px 8px 5px 8px;
    top: 85px;
    font-size: 14px;
    margin: -20px 0 0 0; }
    div.lu-left p.price span {
      font-weight: bold; }
  div.lu-left p.mls {
    margin: 100px 0 0 0; }
div.lu-right {
  width: 430px;
  float: right; }
  div.lu-right h4 {
    margin: 0px;
    padding: 0px;
    font-size: 22px; }
    div.lu-right h4 a {
      color: black;
      text-decoration: none; }
      div.lu-right h4 a:visited {
        color: black; }
      div.lu-right h4 a:hover {
        color: #0066ff; }
  div.lu-right p.mls {
    margin: 0px;
    padding: 0px;
    color: #666666;
    font-size: 12px;
    margin-bottom: 10px; }
  div.lu-right p.desc {
    margin: 0px;
    padding: 0px;
    font-size: 12px;
    line-height: 17px;
    height: 50px;
    overflow: hidden; }
  div.lu-right .lu-details {
    margin-top: 10px; }
  div.lu-right ul {
    margin: 0px;
    padding: 0px 0px 0px 4px;
    float: left;
    width: 300px; }
    div.lu-right ul li {
      list-style: none;
      float: left;
      margin-right: 10px;
      font-size: 14px;
      text-transform: uppercase;
      font-weight: bold; }
      div.lu-right ul li span {
        color: #666666;
        font-weight: lighter; }
  div.lu-right a.info {
    background: url(../img/info.png) left center no-repeat;
    padding: 0px 0px 0px 22px;
    text-decoration: none;
    color: #0066ff;
    font-size: 12px; }
  div.lu-right a.fav:hover, div.lu-right a.info:hover {
    color: black; }
div.lu-links {
  float: left;
  width: 330px;
  padding: 10px 0px 0px 0px; }
div.lu-right a.details {
  margin: 6px 0px 0px 0px;
  padding: 0px;
  background: -webkit-gradient(linear, left top, left bottom, from(#75cd2b), to(#48a41b));
  background: -moz-linear-gradient(top, #75cd2b, #48a41b);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#75cd2b', endColorstr='#48a41b');
  border: 1px solid #4aa11f;
  padding: 8px 12px 8px 12px;
  color: white;
  text-decoration: none;
  font-weight: bold;
  float: right;
  text-shadow: 0px 1px black; }
  div.lu-right a.details:hover {
    background: -webkit-gradient(linear, left top, left bottom, from(#48a41b), to(#75cd2b));
    background: -moz-linear-gradient(top, #48a41b, #75cd2b);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#48a41b', endColorstr='#75cd2b'); }
</style>

<section class="lu">
	
	<div class="lu-left">
		[image]
		<p class="price">[price]</span></p>

	</div><!--lu-left-->

	<div class="lu-right">
		<h4><a href="[url]">[address] [locality], [region]</a></h4>

  		<p class="mls">MLS #: [mls_id]</p>
  	
		<p class="desc">[desc]</p>

		<div class="clearfix"></div>

		<a class="details" href="[url]">See Details</a>

		<div class="lu-details">
			<ul>
				<li>[beds]<span> Bed(s)</span></li>
				<li>[baths]<span> Bath(s)</span></li>
				<li>[sqft]<span> Sqft</span></li>
			</ul>
		</div><!--lu-details-->
	</div><!--lu-right-->	

</section><!--LU-->