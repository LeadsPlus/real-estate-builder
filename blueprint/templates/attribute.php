<?php 
global $query_string;
$args = wp_parse_args($query_string, array('','state' => false, 'city' => false, 'neighborhood' => false, 'zip' => false, 'street' => false, 'image_limit' => 20));
$taxonomy = PLS_Taxonomy::get($args);
?>

<div id="main_content"class="grid_8 alpha" role="main">
	<div class="wrapper">
		<div class="title-information">
			<h1>Neighborhood Information for <?php echo $taxonomy['name'] ?></h1>
			<?php if (isset($taxonomy['one-sentance'])): ?>
				<h3><?php echo $taxonomy['one-sentance'] ?></h3>		
			<?php endif ?>

			<h3><?php echo $taxonomy['another'] ?></h3>
		</div>
		<div class="map polygon-too">
			<?php echo PLS_Map::neighborhood($taxonomy['listings_raw'], array('width' => 590, 'height' => 250, 'zoom' => 16), array(), $taxonomy['polygon']) ?>
		</div>
		<div class="all-listings">
			<?php echo $taxonomy['listings'] ?>
		</div>
		<div class="attached-photos">
		<?php if (isset($taxonomy['one-sentance'])): ?>
				<h3><?php echo $taxonomy['one-sentance'] ?></h3>		
			<?php endif ?>		
		</div>
		
		<div class="tagged-posts">
			
		</div>
		<div class="schools">
			
		</div>
	</div>
</div>
<aside id="sidebar-primary" class="grid_4 omega sidebar">
		<section>
        	<h2>Areas in <?php echo $taxonomy['name'] ?></h2>
        	<ul>
        		<?php foreach ($taxonomy['areas'] as $area_name => $area): ?>
        			<?php if (!empty($area)): ?>
	        			<h3><?php echo $area_name ?></h3>
	        			<?php foreach ($area as $area_value): ?>
	        				<li><a href="<?php echo $area_value['permalink'] ?>"><?php echo $area_value['name'] ?></a></li>	
	        			<?php endforeach ?>	
        			<?php endif ?>
        		<?php endforeach ?>
        	</ul>
        </section>
        <section>
        	<h2>Neighborhood Photos</h2>
	        <?php foreach ($taxonomy['listing_photos'] as $image): ?>
				<section class="image-photo" style="float: left; margin: 10px">
					<a href="<?php echo $image['listing_url'] ?>" title="<?php echo $image['full_address'] ?>"><img src="<?php echo $image['image_url'] ?> " alt="" width=50 height=50></a>		
				</section>
			<?php endforeach ?>	
        </section>
</aside>