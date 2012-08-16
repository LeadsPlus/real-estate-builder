<?php //pls_dump($params) ?>
<div class="featured-listings-wrapper">
	<div class="head">
		<button class="featured-listings button" id="<?php echo $params['value']['id'] ?>" <?php echo isset($params['iterator']) ? 'rel="' . $params["iterator"] . '"' : ''; ?>>Pick featured listings</button>
	</div>
	
	<div class="featured-listings" id="<?php echo $params['option_name'] ?>" ref="<?php echo $params['value']['id'] ?>" <?php echo isset($params['iterator']) ? 'rel="' . $params["iterator"] . '"' : ''; ?> <?php echo ($params['for_slideshow'] == 1) ? 'data-max="1"' : ''; ?> >
		<?php if ( is_array($params['val']) ): ?>
			<ul>
			<?php if ($for_slideshow == 1): ?>
				<?php unset( $params['val']['image'], $params['val']['link'], $params['val']['html'], $params['val']['type'] ) ?>
				<?php if (isset($params['val'][0])): ?>
					<?php unset($params['val'][0]) ?>
				<?php endif ?>
				<?php foreach ($params['val'] as $id => $address): ?>
				<li>
					<div id="pls-featured-text" ref="<?php echo $id ?>"><?php echo $address ?></div>
					<input type="hidden" name="<?php echo $params['option_name'] . '[' . $params['value']['id'] . '][' . $id . ']' ?>=" value="<?php echo $address ?>">
				</li>
				<?php endforeach ?>
			<?php else: ?>
				<?php foreach ($params['val'] as $id => $address): ?>
				<li>
					<div id="pls-featured-text" ref="<?php echo $id ?>"><?php echo $address ?></div>
					<input type="hidden" name="<?php echo $params['option_name'] . '[' . $params['value']['id'] . '][' . $id . ']' ?>=" value="<?php echo $address ?>">
				</li>
				<?php endforeach ?>
			<?php endif ?>
			</ul>	
		<?php else: ?>
			<p>You haven't set any featured listings yet. Currently, a random selection of listings are being displayed until you pick some. If you previously picked listings, and now they are missing, it's because you (or your MLS), has marked them inactive, sold, rented, or they've been deleted.</p>
		<?php endif ?>
	</div>	
</div>
			