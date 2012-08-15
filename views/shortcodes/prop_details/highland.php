<style type="text/css">
  div#main {
    width: inherit;
    float: left; 
  }
  h3.h-address {
    float: left; 
    width: 100%;
    margin-top: 5px;
  }
  p.h-price {
    margin: 0px;
    padding: 0px;
    float: right;
    color: #378a0b;
    font-size: 26px;
    font-weight: lighter; 
  }
  div.leasing {
    background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#dadada));
    background: -moz-linear-gradient(top, #f2f2f2, #dadada);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f2f2f2', endColorstr='#dadada');
    border-radius: 8px;
    -moz-border-radius: 8px;
    border: 1px solid #bfbfbf;
    padding: 12px 15px 12px 18px;
    margin: 15px 5px 14px 0; 
  }
  div.leasing h3 {
    float: left;
    margin: 0px 15px 0px 0px;
    padding-top: 18px; 
  }
  div.leasing ul.leasing-01 {
    margin: 0px;
    padding: 6px 0px 0px 12px;
    width: 220px;
    float: left; 
  }
  div.leasing ul.leasing-01 li {
    list-style: none;
    font-size: 12px;
    margin: 0px 10px 5px 0px;
    font-weight: bold; 
  }
  div.leasing ul.leasing-01 li span {
    float: right;
    text-align: left;
    font-weight: lighter; 
  }
  div.leasing ul.leasing-02 {
    margin: 0px;
    padding: 6px 0px 0px 12px;
    width: 120px;
    float: right; 
  }
  div.leasing ul.leasing-02 li {
    list-style: none;
    font-size: 12px;
    margin: 0px 10px 6px 0px; 
  }
  div.leasing ul.leasing-02 li span.ico-bed {
    background: url(../img/bed-ico.png) left center no-repeat;
    padding: 0px 0px 0px 22px; 
  }
  div.leasing ul.leasing-02 li span.ico-bath {
    background: url(../img/bath-ico.png) left center no-repeat;
    padding: 0px 0px 0px 22px; 
  }
  div.leasing ul.leasing-02 li span.ico-half {
    background: url(../img/half-ico.png) left center no-repeat;
    padding: 0px 0px 0px 22px; 
  }
</style>

<h3 class="h-address">[full_address]</h3>
<p class="h-price">[price]</p>
<div id="main" role="main">
  
  [image]
			
  <div class="leasing">
  	<h3>Leasing Details</h3>
    <ul class="leasing-01">
      <li>Property Type <span>[property_type]</span>
			</li>
      <li>MLS # <span>[mls_id]</span></li>
    </ul><!--LEASING-01-->      
    <ul class="leasing-02">
      <li><span class="ico-bed">[beds] Bedroom(s)</span></li>        
      <li><span class="ico-bath">[baths] Bathroom(s)</span></li>          
      <li><span class="ico-half">[half_baths] Half Bath(s)</span></li>                                                                      
   	</ul><!--LEASING-02-->
    <div class="clearfix"></div>
  </div><!--leasing-->

  <div class="user-generated">
    <h3>Description</h3>
    <p>[desc]</p>
	</div>
  
  <div class="clearfix"></div>
</div>
