<section id="full-search">
	<h3>Search Listing</h3>
	<div id="full-form">

		<form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>listings" class="pls_search_form_listings">
			
			<div class="form-l">
				<h6>Location</h6>
				<label>City</label>
				[cities]
				<label>State</label>
				[states]
				<label>Zipcode</label>
				[zips]
			</div><!--form-l-->
			
			<div class="form-m">
				<h6>Listing Type</h6>
				<label>Zoning Type</label>
				[zoning_types]
				<div id="purchase_type_container">
				  <label>Transaction Type</label>
				  [purchase_types]
				</div>
				<label>Property Type</label>
				[property_type]
			</div><!--form-m-->
			
			<div class="form-r">
				<div class="form-r-l">
					<h6>Details</h6>
					<label>Bed(s)</label>
					[bedrooms]
					<label>Bath(s)</label>
					[bathrooms]
				</div><!--form-r-l-->
				
				<div class="form-r-r">
					<h6>Price Range</h6>
					<div id="min_price_container">
					  <label>Price From</label>
					  [min_price]
          </div>
			    <div id="max_price_container">
					  <label>Price To</label>
					  [max_price]
			    </div>
				</div><!--form-r-r-->
				
				<div class="clr"></div>
				<div id="available-on">
					<!--<label>Available On</label>
					[available_on] -->
				</div>
			</div><!--form-r-->

			<!-- 
				<input type="submit" name="submit" value="Search Now!" id="search-button"> 
			-->
			
			<div class="clearfix"></div>
		</form>
	</div><!--full-form-->
</section>