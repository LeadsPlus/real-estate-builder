<div style="display:none" class="polygon_controls">
				<h3>Neighborhoods Create Controls</h3>
					<form>
						<div class="form-item">
							<label for="name">Neighborhood Name</label>
							<input type="text" name="name" id="name">	
						</div>
						<div class="form-item">
							<label for="">Border Weight</label>	
							<select name="[border][weight]" id="[border][weight]">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3" selected>3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
							</select>
						</div>
						<div class="form-item">
							<label>Border Opacity</label>
							<select name="[border][opacity]" id="[border][opacity]">
								<option value="0.2">0.2</option>
								<option value="0.3">0.3</option>
								<option value="0.4">0.4</option>
								<option value="0.5">0.5</option>
								<option value="0.6">0.6</option>
								<option value="0.7">0.7</option>
								<option value="0.8">0.8</option>
								<option value="0.9">0.9</option>
								<option value="1" selected>1.0</option>
							</select>	
						</div>
						<div class="form-item" id="colorpicker">
							<label>Border Color</label>
							<div id="polygon_border" class="another_colorpicker">
								<div style="background-color: #FF0000"></div>
							</div>
						</div>
						<div class="form-item">
							<label>Fill Opacity</label>
							<select name="[fill][opacity]" id="[fill][opacity]">
								<option value="0.2">0.2</option>
								<option value="0.3" selected>0.3</option>
								<option value="0.4">0.4</option>
								<option value="0.5" >0.5</option>
								<option value="0.6">0.6</option>
								<option value="0.7">0.7</option>
								<option value="0.8">0.8</option>
								<option value="0.9">0.9</option>
								<option value="1">1.0</option>
							</select>	
						</div>
						<div class="form-item" id="colorpicker">
							<label>Fill Color</label>
							<div id="polygon_fill" class="another_colorpicker">
								<div style="background-color: #FF0000"></div>
							</div>
						</div>
						<div class="form-item">
							<label for="">Polygon Type</label>
							<?php echo PL_Taxonomy_Helper::types_as_selects(); ?>
						</div>
						<div class="form-item">
							<label for="">Associated Taxonomy</label>
							<?php echo PL_Taxonomy_Helper::taxonomies_as_selects(); ?>
						</div>
						<div class="form-item" id="custom_name" style="display: none">
							<label for="name">Custom Taxonomy Name</label>
							<input type="text" name="custom_taxonomy_name" id="custom_taxonomy_name">	
						</div>
						<input type="hidden" id="edit_id" name="id">
						<div class="form-item buttons">
							<a id="polygon_clear_drawing" class="button" href="#">Cancel</a>
							<a id="polygon_edit_drawing" class="button" href="#">Edit Drawing</a>
							<a id="polygon_save_drawing" class="button-primary" href="#">Save as Neighborhood</a>	
						</div>
						
					</form>
				</div>