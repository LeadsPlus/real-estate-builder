<style type="text/css">
  section.list-unit {
    background: #f2f2f2;
    border: 1px solid #d6d6d6;
    border-radius: 8px;
    -moz-border-radius: 8px;
    margin-bottom: 12px; 
    width: inherit;
  }
  section.list-unit .lu-left {
    background: white;
    padding: 15px;
    width: 150px;
    float: left;
    border-right: 1px solid #d6d6d6; 
  }
  section.list-unit .lu-left img {
    margin: 0px 0px 8px 0px; 
  }
  section.list-unit .lu-left a#fav {
    background: #f2f2f2;
    border: 1px solid #d7d4d4;
    display: block;
    color: #105fbf;
    text-decoration: none;
    font-size: 13px;
    padding: 6px 8px;
    margin: 0px 0px 6px 0px; 
  }
  section.list-unit .lu-left a#fav span {
    background: url(../img/favorite.png) left center no-repeat;
    padding-left: 24px; 
  }
  section.list-unit .lu-left a#info {
    background: #f2f2f2;
    border: 1px solid #d7d4d4;
    display: block;
    color: #105fbf;
    text-decoration: none;
    font-size: 13px;
    padding: 6px 8px;
    margin: 0px; 
  }
  section.list-unit .lu-left a#info span {
    background: url(../img/info.png) left center no-repeat;
    padding-left: 24px; 
  }
  section.list-unit a#fav:hover, section.list-unit a#info:hover {
    color: black; 
  }
  section.list-unit .lu-right {
    padding: 15px;
    width: 375px;
    float: right; 
  }
  section.list-unit .lu-right .lu-title {
    float: left;
    width: 260px;
    margin-bottom: 5px; 
  }
  section.list-unit .lu-right .lu-title h4 {
    font-family: "Andada", serif;
    margin: 0px;
    padding: 0px;
    color: #055da9;
    font-size: 20px;
    font-weight: lighter;
    margin: 2px 0px; 
  }
  section.list-unit .lu-right .lu-title p {
    margin: 0px;
    padding: 0px;
    color: #666666;
    font-size: 12px; 
  }
  section.list-unit .lu-right .lu-price {
    background: -webkit-gradient(linear, left top, left bottom, from(#dee9f0), to(#c5d8e5));
    background: -moz-linear-gradient(top, #dee9f0, #c5d8e5);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dee9f0', endColorstr='#c5d8e5');
    border: 1px solid #8fabbe;
    border-radius: 8px;
    -moz-border-radius: 8px;
    float: right;
    text-align: center;
    padding: 6px 10px;
    margin-bottom: 5px; 
  }
  section.list-unit .lu-right .lu-price p {
    margin: 0px;
    padding: 0px;
    text-transform: uppercase;
    line-height: 23px;
    color: #054175; 
  }
  section.list-unit .lu-right .lu-price p.price {
    font-size: 22px;
    font-weight: bold;
    overflow: hidden; 
  }
  section.list-unit .lu-right .lu-price p.month {
    font-size: 11px;
    margin-top: -4px; 
  }
  section.list-unit .lu-right p.desc {
    font-size: 12px;
    line-height: 17px;
    margin-bottom: 15px; 
  }
  section.list-unit .lu-right span.area {
    background: url(../img/area.png) left bottom no-repeat;
    padding: 6px 0px 16px 35px;
    margin: 0px 10px 0px 0px;
    font-size: 12px;
    float: left; 
  }
  section.list-unit .lu-right span.bed {
    background: url(../img/beds.png) left bottom no-repeat;
    padding: 6px 0px 16px 35px;
    margin: 0px 10px 0px 0px;
    font-size: 12px;
    float: left; 
  }
  section.list-unit .lu-right span.bath {
    background: url(../img/baths.png) left bottom no-repeat;
    padding: 6px 0px 16px 35px;
    margin: 0px 8px 0px 0px;
    font-size: 12px;
    float: left; 
  }
  section.list-unit .lu-right a.details {
    background: -webkit-gradient(linear, left top, left bottom, from(#94d342), to(#45a11e));
    background: -moz-linear-gradient(top, #94d342, #45a11e);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#94d342', endColorstr='#45a11e');
    border: 1px solid #3c8619;
    float: right;
    color: white;
    text-decoration: none;
    padding: 7px 10px 8px 10px;
    font-size: 14px; 
  }
  section.list-unit .lu-right a.details:hover {
    background: -webkit-gradient(linear, left top, left bottom, from(#45a11e), to(#94d342));
    background: -moz-linear-gradient(top, #45a11e, #94d342);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#45a11e', endColorstr='#94d342'); 
  }
</style>

<section class="list-unit">

  <section class="lu-left">
  	[image]
    <a id="info" href="[email]"><span>More Information</span></a>
  </section><!--lu-left-->

  <section class="lu-right">
    <div class="lu-title">
      <h4>
        <a href="[url]">[address] [locality], [region]</a>
      </h4>
    	<p class="mls">MLS #: [mls_id]</p>

    </div><!--LU-TITLE-->
    <div class="lu-price">
      <p class="price">[price]</p>
    </div><!--LU PRICE-->
    <div class="clearfix"></div>
    <p class="desc">[desc maxlen=300]</p>
    <span class="area">[sqft] Sq Ft</span>
    <span class="bed">[beds] Bed(s)</span>
    <span class="bath">[baths] Bath(s)</span>
    <a href="[url]">See Details</a>
  </section><!--LU-RIGHT-->

  <div class="clearfix"></div>

</section>