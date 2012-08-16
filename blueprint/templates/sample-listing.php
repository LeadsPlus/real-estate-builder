<?php 
 global $PLS_API_DEFAULT_LISTING;
$listing_data = $PLS_API_DEFAULT_LISTING['listings'][0];

ob_start();
 ?>
<h2><?php echo $listing_data['location']['address'] . ' ' . $listing_data['location']['region'] . ' ' . $listing_data['location']['locality']; ?></h2>

		<span class="listing_type"> <?php echo @$listing_data['zoning_types'][0] . ' ' . @$listing_data['purchase_types'][0] ?></span>

		<div class="clearfix"></div>

			<?php if ($listing_data['images']): ?>
				<div class="theme-default property-details-slideshow">
					<?php echo PLS_Image::load($listing_data['images'][0]['url'], array('resize' => array('w' => 590, 'h' => 300), 'fancybox' => false, 'as_html' => true)) ?>
					<?php // echo PLS_Slideshow::slideshow( array( 'anim_speed' => 1000, 'pause_time' => 15000, 'control_nav' => true, 'width' => 620, 'height' => 300, 'context' => 'home', 'data' => PLS_Slideshow::prepare_single_listing($listing_data) ) ); ?>
				</div>

				<div class="details-wrapper grid_8 alpha">

					<div id="slideshow" class="clearfix theme-default left bottomborder">
						<h3>Image Gallery</h3>
						<div class="grid_8 alpha">
							<ul class='property-image-gallery grid_8 alpha'>
								<?php foreach ($listing_data['images'] as $images): ?>
									<li><?php echo PLS_Image::load($images['url'], array('resize' => array('w' => 100, 'h' => 75), 'fancybox' => true)) ?></li>
								<?php endforeach ?>
							</ul>
						</div>

					</div>

				</div>
				<?php endif ?>
    

    <div class="details-wrapper grid_4 alpha">
        <h3>Property Description</h3>
        <?php if (!empty($listing_data['cur_data']['desc'])): ?>
            <p> <?php echo $listing_data['cur_data']['desc']; ?> </p>
        <?php else: ?>
            <p> No description available </p>
        <?php endif ?>
    </div>

    <div class="details-wrapper grid_4 omega">
        <h3>Basic Details</h3>
        <ul>
            <li><span>Beds </span><?php echo $listing_data['cur_data']['beds'] ?></li>
            <li><span>Baths </span><?php echo $listing_data['cur_data']['baths'] ?></li>
            <li><span>Price </span><?php echo $listing_data['cur_data']['price'] ?></li>
            <li><span>Half Baths </span><?php echo $listing_data['cur_data']['half_baths'] ?></li>
            <li><span>Available </span><?php echo @$listing_data['cur_data']['avail_on'] ?></li>
            <li><span>Square Feet </span><?php echo $listing_data['cur_data']['sqft'] ?></li>
            <?php if (isset($listing_data['rets']) && isset($listing_data['rets']['mls_id'])): ?>
            	<li><span>MLS Number: </span><?php echo $listing_data['rets']['mls_id'] ?></li>	
            <?php endif ?>
        </ul>
    </div>

    <?php $amenities = PLS_Format::amenities_but(&$listing_data, array('half_baths', 'beds', 'baths', 'url', 'sqft', 'avail_on', 'price')); ?>
   
    <?php if (isset($amenities['list'])): ?>
        <div class="amenities grid_8 alpha">
            <h3>Listing Amenities</h3>
        	<?php PLS_Format::translate_amenities(&$amenities['list']); ?>
            <?php foreach ($amenities['list'] as $amenity => $value): ?>
                <li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
            <?php endforeach ?>
        </div>	
    <?php endif ?>
    
    <?php if (isset($amenities['ngb'])): ?>
        <div class="amenities grid_8 alpha">
            <h3>Local Amenities</h3>
        	<?php PLS_Format::translate_amenities(&$amenities['ngb']); ?>
            <?php foreach ($amenities['ngb'] as $amenity => $value): ?>
                <li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
            <?php endforeach ?>
        </div>	
    <?php endif ?>
    
	<?php if (isset($amenities['uncur'])): ?>
        <div class="amenities grid_8 alpha">
            <h3>Local Amenities</h3>
        	<?php PLS_Format::translate_amenities(&$amenities['uncur']); ?>
            <?php foreach ($amenities['uncur'] as $amenity => $value): ?>
                <li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
            <?php endforeach ?>
        </div>	
    <?php endif ?>

    <div class="map-wrapper grid_8 alpha">
        <h3>Property Map</h3>
        <div class="map">
            <?php echo PLS_Map::dynamic($listing_data, array('lat'=>$listing_data['location']['coords'][0], 'lng'=>$listing_data['location']['coords'][1], 'width' => 590, 'height' => 250, 'zoom' => 16)); ?>
        </div>
    </div>
	<?php PLS_Listing_Helper::get_compliance(array('context' => 'listings', 'agent_name' => $listing_data['rets']['aname'] , 'office_name' => $listing_data['rets']['oname'])); ?>
	
<?php
$html = ob_get_clean();
echo apply_filters('property_details_filter',$html, $listing_data);