<section class="list-unit">

  <section class="lu-left">
  	<?php if (isset($listing['images'][0])): ?>
  		<?php echo PLS_Image::load($listing['images'][0]['url'], array('resize' => array('w' => 149, 'h' => 90, 'method' => 'crop'), 'fancybox' => true, 'as_html' => true)); ?>    	
<?php else: ?>
	<?php echo PLS_Image::load('', array('resize' => array('w' => 149, 'h' => 90, 'method' => 'crop'), 'fancybox' => true, 'as_html' => true)); ?>
  	<?php endif; ?>
    <!-- <a id="fav" href="#"><span>Add to Favorites</span></a> -->
    <a id="info" href="mailto:<?php echo pls_get_option('pls-user-email') ?>"><span>More Information</span></a>
  </section><!--lu-left-->

  <section class="lu-right">
    <div class="lu-title">
      <h4>
        <a href="<?php echo $listing['cur_data']['url']; ?>"><?php echo $listing['location']['address'] . ' ' . $listing['location']['locality'] . ', ' . $listing['location']['region'] ?></a>
      </h4>
      
      <?php if (isset($listing['rets']['mls_id'])) { ?>
    		<p class="mls">MLS #: <?php echo $listing['rets']['mls_id'] ?></p>
    	<?php } ?>

    </div><!--LU-TITLE-->
    <div class="lu-price">
      <p class="price"><?php echo PLS_Format::number($listing['cur_data']['price'], array('add_currency_sign' => true, 'abbreviate' => false)); ?></p>
  		<?php if (isset($listing['cur_data']['lse_trm'])): ?>
  			<p class="month"><?php echo $listing['cur_data']['lse_trm']; ?></p>
  		<?php endif; ?>
    </div><!--LU PRICE-->
    <div class="clearfix"></div>
    <p class="desc"><?php echo substr($listing['cur_data']['desc'], 0, 300); ?></p>
  	<?php if(isset($listing['cur_data']['sqft'])): ?>
    <span class="area"><?php echo $listing['cur_data']['sqft']; ?></span>
  	<?php endif; ?>
    <span class="bed"><?php echo $listing['cur_data']['beds']; ?> Beds</span>
    <span class="bath"><?php echo $listing['cur_data']['baths']; ?> Baths</span>
    <a class="details" href="<?php echo $listing['cur_data']['url']; ?>">See Details</a>
  </section><!--LU-RIGHT-->

  <div class="clearfix"></div>

</section>