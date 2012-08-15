<h3 class="h-address">[full_address]</h3>
<p class="h-price">[price]</p>
<div id="main" role="main">
  
  [image]
			
  <div class="leasing">
  	<h3>Leasing Details</h3>
    <ul class="leasing-01">
      <li>Listing Type <span><?php echo ucwords($listing['purchase_types'][0]); ?></span></li>
      <li>Property Type <span><?php echo PLS_Format::translate_property_type($listing); ?></span>
			</li>
      <?php if (isset($listing['rets']['mls_id'])): ?>
        <li>MLS # <span><?php echo $listing['rets']['mls_id'] ?></span></li>  
      <?php else: ?>
        <li>Ref # <span><?php echo $listing['id'] ?></span></li>  
      <?php endif ?>
      
    </ul><!--LEASING-01-->      
    <ul class="leasing-02">
      <li><span class="ico-bed"><?php echo $listing['cur_data']['beds'] ?> Bedroom(s)</span></li>        
      <li><span class="ico-bath"><?php echo $listing['cur_data']['baths'] ?> Bathroom(s)</span></li>          
      <li><span class="ico-half"><?php echo $listing['cur_data']['half_baths'] ?> Half Bath(s)</span></li>                                                                      
   	</ul><!--LEASING-02-->
    <div class="clearfix"></div>
  </div><!--leasing-->

  <div class="user-generated">
    <h3>Description</h3>
    <?php if ($listing['cur_data']['desc']): ?>
      <p><?php echo $listing['cur_data']['desc'] ?></p>  
    <?php else: ?>
      <p>No description available at this time.</p>
    <?php endif ?>
    
	</div><!--USER-GENERATED-->

	<?php $amenities = PLS_Format::amenities_but(&$listing, array('half_baths', 'beds', 'baths', 'url', 'sqft', 'avail_on', 'price', 'desc')); ?>

	<?php if ( isset($amenities['list']) && $amenities['list'] != null ): ?>
		<div class="user-generated">
			<h3>Property Amenities</h3>
			<ul class="checklist">
				<?php PLS_Format::translate_amenities(&$amenities['list']); ?>
				<?php foreach ($amenities['list'] as $amenity => $value): ?>
					<li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
				<?php endforeach ?>
			</ul>
			<div class="clearfix"></div>
		</div><!--USER-GENERATED-->
	<?php endif ?>

	<?php if (isset($amenities['ngb']) && $amenities['ngb'] != null ): ?>
		<div class="user-generated">
			<h3>Neighborhood Amenities</h3>
			<ul class="checklist">
				<?php PLS_Format::translate_amenities(&$amenities['ngb']); ?>
				<?php foreach ($amenities['ngb'] as $amenity => $value): ?>
					<li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
				<?php endforeach ?>
			</ul>
			<div class="clearfix"></div>
		</div><!--USER-GENERATED-->
	<?php endif ?>

  <?php if ( isset($amenities['uncur']) && $amenities['uncur'] != null ): ?>
		<div class="user-generated">
			<h3>Custom Amenities</h3>
			<ul class="checklist">
				<?php PLS_Format::translate_amenities(&$amenities['uncur']); ?>
				<?php foreach ($amenities['uncur'] as $amenity => $value): ?>
					<li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
				<?php endforeach ?>
			</ul>
			<div class="clearfix"></div>
		</div><!--USER-GENERATED-->
	<?php endif ?>


  <div class="user-generated">
  	<h3>Neighborhood</h3>
      [map]
  </div><!--USER-GENERATED-->
  
  <div class="clearfix"></div>
</div><!--MAIN-->  
  
  
<aside>
  <section id="location-widget">
    [map] 
  </section><!--LOCATION-WIDGET-->	
    
  <?php if ($listing['images']): ?>
  <section id="gallery">
    <h3>Photo Gallery</h3>
    <?php foreach ($listing['images'] as $image): ?>
      <?php echo PLS_Image::load($image['url'], array('resize' => array('w' => 120, 'h' => 95, 'method' => 'crop'), 'fancybox' => true, 'as_html' => false)) ?>  
    <?php endforeach ?>
    <div class="clearfix"></div>
  </section><!--SEARCH-->  
  <?php endif ?>
  
  
  <section id="featured">
  	<h3>Featured Listings</h3>
    <?php echo pls_get_listings( "limit=4&featured_option_id=custom-featured-listings&context=property_details" ) ?>
  </section>
</aside>
