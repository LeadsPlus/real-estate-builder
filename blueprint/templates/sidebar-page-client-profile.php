<?php if (is_user_logged_in()): ?>
	<?php extract(PLS_Plugin_API::get_person_details()); ?>
	<?php //pls_dump(PLS_Plugin_API::get_person_details()); ?>

<aside class="grid_4 omega sidebar">

	<section class="widget widget-pls-recent-posts">

		<div id="edit_profile" style="display:none;">
			<div id="edit_profile_message"></div>
			<form id="edit_profile_form">
				<div>
					<label>Name</label>	
					<input type="text" name="metadata[name]" value="<?php echo @$cur_data['name'] ?>" >
				</div>
				<div>
					<label>Company</label>	
					<input type="text" name="metadata[company]" value="<?php echo @$cur_data['company'] ?>">
				</div>
				<div>
					<label>Phone</label>	
					<input type="text" name="metadata[phone]" value="<?php echo @$cur_data['phone'] ?>">
				</div>
				<div>
					<label>Street</label>	
					<input type="text" name="location[address]" value="<?php echo @$location['address'] ?>">
				</div>
				<div>
					<label>City</label>	
					<input type="text" name="location[locality]" value="<?php echo @$location['locality'] ?>" >
				</div>
				<div>
					<label>State</label>	
					<input type="text" name="location[region]" value="<?php echo @$location['region'] ?>">
				</div>
				<div>
					<label>Zip</label>	
					<input type="text" name="location[postal]" value="<?php echo @$location['postal'] ?>">
				</div>
				<div>
					<label>Country</label>	
					<input type="text" name="location[country]" value="<?php echo @$location['country'] ?>">
				</div>			
			</form>
		</div>

		<div id="client-details">
			<h3>About You:</h3>
			<a style="float: right;" class="edit_profile_button" id="edit_profile_button">Edit Profile</a>
				<?php if (empty($cur_data)): ?>
					<div style="float: left; margin: 60px 30px;">No contact info, add some <a style="float: none; margin: 0px;" id="edit_profile_button">here</a></div>
					<div class="clear"></div>
				<?php else: ?>

					<ul>
						<?php if (@$cur_data['name']): ?>
							<li><span>Name: </span><?php echo @$cur_data['name'] ?></li>	
						<?php endif ?>
						<?php if (@$cur_data['company']): ?>
							<li><span>Company: </span><?php echo @$cur_data['company'] ?></li>	
						<?php endif ?>
						<?php if (@$cur_data['email']): ?>
							<li><span>Email: </span><?php echo @$cur_data['email'] ?></li>	
						<?php endif ?>
						<?php if (@$cur_data['phone']): ?>
							<li><span>Phone: </span><?php echo @$cur_data['phone'] ?></li>	
						<?php endif ?>
						<?php if (@$cur_data['address']): ?>
							<li><span>Address: </span><?php echo @$location['address'] ?><br><?php echo @$location['locality'] . ', ' . @$location['region'] . ' ' . @$location['postal'] ?> <br> <?php echo @$location['country'] ?></li>	
						<?php endif ?>
					</ul>
				<?php endif ?>
		</div>

	</section>


<?php 

	$instance = array("title" => "Have a question?", "number" => 9);
	$args = array();
	$sb = new Placester_Contact_Widget();
	$sb->number = $instance['number'];
	$sb->widget($args,$instance);

 ?>

</aside>

<?php else: ?>

	<aside class="grid_4 omega sidebar">

		<?php if ( is_active_sidebar( 'primary' ) ) : ?>

			<?php dynamic_sidebar( 'primary' ); ?>

		<?php else: ?>

			<?php PLS_Route::handle_default_sidebar(); ?>

		<?php endif; ?>

	</aside>

<?php endif; ?>

