<form id="options-filters" method="POST" >
	<div class="featured_listings_options">
		<div class="address big-option">
			<label>Street Address</label>
			<input type="text" name="location[address]">
		</div>
		<div class="featured-listing-form-city option">
			<label>City</label>
			<select name="location[locality]">
				<?php $cities = PLS_Plugin_API::get_location_list('locality');
					foreach ($cities as $key => $v) {
						echo '<option value="' . $key . '">' . $v . '</option>';
					} 
				?>
			</select>
		</div>

		<div class="featured-listing-form-zip option">
			<label>Zip Code</label>
			<select name="location[postal]">
				<?php $zip = PLS_Plugin_API::get_location_list('postal');
					foreach ($zip as $key => $v) {
						echo '<option value="' . $key . '">' . $v . '</option>';
					} 
				?>
			</select>
		</div>

		<div class="featured-listing-form-beds option">
			<label>Beds</label>
			<input type="text" name="metadata[beds]">
		</div>

		<div class="featured-listing-form-beds option">
			<label>Rent/Sale</label>
			<select name="purchase_types[]">
				<?php
					echo '<option value="false">Any</option>';
					echo '<option value="rental">Rent</option>';
					echo '<option value="sale">Buy</option>';
				?>
			</select>
		</div>

		<div class="featured-listing-form-min-price option">
			<label>Min Price</label>
			<input type="text" name="metadata[min_price]">
		</div>

		<div class="featured-listing-form-max-price option">
			<label>Max Price</label>
			<input type="text" name="metadata[max_price]">
		</div>

		<div class="featured-listing-form-max-price option checkboxes">
			<label>Non-MLS Listings</label>
			<input type="checkbox" name="non_import">
		</div>

		<div class="featured-listing-form-max-price option checkboxes">
			<label>My Offices's Listings</label>
			<input type="checkbox" name="agency_only">
		</div>

	</div>
	<input class="button" type="submit" value="Search">
</form>
