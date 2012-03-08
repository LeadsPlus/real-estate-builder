<div id="submitdiv" class="postbox ">
	<div class="handlediv" title="Click to toggle">
		<br></div>
	<h3 class="hndle">
		<span>Publish</span>
	</h3>
	<div class="inside">
		<div class="submitbox" id="submitpost">
			<div id="minor-publishing">
				<div style="display:none;">
					<p class="submit">
						<input type="submit" name="save" id="save" class="button" value="Save">
					</p>
				</div>
				<div id="misc-publishing-actions">
					<div class="misc-pub-section">
						<label for="post_status">Status:</label>
						<span id="post-status-display">Draft</span>
					</div>
					<div class="misc-pub-section " id="visibility">
						Visibility:
						<span id="post-visibility-display">Public</span>
					</div>
					<div class="misc-pub-section curtime misc-pub-section-last">
						<span id="timestamp">
							Publish <b>immediately</b>
						</span>
					</div>
				</div>
				<div class="clear"></div>
			</div>

			<div id="major-publishing-actions">
				<div id="delete-action">
					<a class="submitdelete deletion" href="admin.php?page=placester_properties">Cancel</a>
				</div>

				<div id="publishing-action">
					<img src="http://wpsingle.com/wp-admin/images/wpspin_light.gif" class="ajax-loading" id="ajax-loading" alt="">
					<input name="original_publish" type="hidden" id="original_publish" value="Publish">
					<input type="submit" name="publish" id="add_listing_publish" class="button-primary" value="Publish" tabindex="5" accesskey="p"></div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>