 <?php 
$property_id = $_GET['property_id'];
$property = placester_property_get($property_id);
$purchase_type =  !empty( $property->purchase_types ) ? $property->purchase_types[0] : '';
$property_type = ucfirst(strtolower(str_replace('_', ' ', $property->property_type)));      
?>
<div id="template_container" style="display:none">  
<table border="0" width="969" bgcolor="#100C09" background="pattern.png" align="center" cellpadding="0" cellspacing="0" style="border-width: 0 1px; border-color: #352a21; border-style: solid;">
	<!-- header -->
	<tr>
		<td>
			<table width="969" border="0" cellspacing="0" cellpadding="0" background="<?php echo placester_get_template_url(); ?>bg-header.png">
				<tr>
					<td colspan="4"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="8"></td>
					<td rowspan="2"><a href="#"><img src="<?php echo placester_get_template_url(); ?>logo.png" width="198" height="89" border="0" vspace="0" hspace="0" align="left"></a></td>
					<td rowspan="2"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td>
				</tr>
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="13" height="1"></td>
					<td>
						<table border="0" bgcolor="#4e4d4c" cellspacing="0" cellpadding="3">
							<tr><td><img src="<?php echo placester_get_user_details()->logo_url; ?>" width="76" height="60" vspace="0" hspace="0" align="left"></td></tr>
						</table>
					</td>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td>
					<td width="685">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td><img src="<?php echo placester_get_template_url(); ?>ico1.png" width="10" height="11" vspace="0" hspace="0" align="left"></td>
											<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="7" height="1"></td>
											<td><font style="font-size:18px;line-height:24px;" size="3" color="#555555" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->first_name, 'first name' ) . ' ' . placester_get_placeholder_if_empty( placester_get_user_details()->last_name, 'last name' ); ?></font></td>
										</tr>
										<tr>
											<td><img src="<?php echo placester_get_template_url(); ?>ico2.png" width="11" height="11" vspace="0" hspace="0" align="left"></td>
											<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="7" height="1"></td>
											<td><font style="font-size:18px;line-height:24px;" size="3" color="#ed7103" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->phone, 'phone' ) ?></font></td>
										</tr>
										<tr>
											<td><img src="<?php echo placester_get_template_url(); ?>ico3.png" width="11" height="8" vspace="0" hspace="0" align="left"></td>
											<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="7" height="1"></td>
											<td><a href="#"><font style="font-size:14px;line-height:20px;" size="3" color="#555555" face="Arial, Helvetica, sans-serif"><?php echo placester_get_user_details()->email; ?></font></a></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					
				</tr>
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="12"></td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- /header -->
	<!-- intro-block -->
	<tr>
		<td>
			<table width="969" border="0" cellspacing="0" cellpadding="0" background="<?php echo placester_get_template_url(); ?>bg-intro.png">
				<tr>
					<td>
						<img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="30">
					</td>
				</tr>
				<tr valign="top">
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="29" height="1"></td>
					<td width="327">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font style="font-size:22px;line-height:27px;" size="3" color="#ed7103" face="Arial, Helvetica, sans-serif"><?php echo $property->bedrooms; ?> Bedroom, <?php echo $property->bathrooms; ?> Bathroom<br> <?php echo $property_type; ?></font></td>
							</tr>
							<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="20"></td></tr>
							<tr>
								<td>
									<table width="327" border="0" cellspacing="0" cellpadding="0">
										<tr bgcolor="#000000"><td width="10"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td><td><font style="font-size:15px;line-height:28px;" size="2" color="#A4A4A4" face="Arial, Helvetica, sans-serif">Price: </font><font style="font-size:15px;line-height:28px;" size="2" color="#FFFFFF" face="Arial, Helvetica, sans-serif"><?php echo '$' . $property->price; if ( $purchase_type == "rental" ) echo " per month"; ?></font></td></tr>
										<tr bgcolor="#121212"><td width="10"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td><td><font style="font-size:15px;line-height:28px;" size="2" color="#A4A4A4" face="Arial, Helvetica, sans-serif">Property type: </font><font style="font-size:15px;line-height:28px;" size="2" color="#FFFFFF" face="Arial, Helvetica, sans-serif"><?php echo $property_type; ?></font></td></tr>
										<tr bgcolor="#000000"><td width="10"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td><td><font style="font-size:15px;line-height:28px;" size="2" color="#A4A4A4" face="Arial, Helvetica, sans-serif">Bathroom(s): </font><font style="font-size:15px;line-height:28px;" size="2" color="#FFFFFF" face="Arial, Helvetica, sans-serif"><?php echo $property->bathrooms; ?></font></td></tr>
										<tr bgcolor="#121212"><td width="10"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td><td><font style="font-size:15px;line-height:28px;" size="2" color="#A4A4A4" face="Arial, Helvetica, sans-serif">Bedrooms: </font><font style="font-size:15px;line-height:28px;" size="2" color="#FFFFFF" face="Arial, Helvetica, sans-serif"><?php echo $property->bedrooms; ?></font></td></tr>
                               <?php if ( !empty($property->zoning_types) ) { ?> 
										<tr bgcolor="#000000"><td width="10"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td><td><font style="font-size:15px;line-height:28px;" size="2" color="#A4A4A4" face="Arial, Helvetica, sans-serif">Zoning types: </font><font style="font-size:15px;line-height:28px;" size="2" color="#FFFFFF" face="Arial, Helvetica, sans-serif"><?php echo ucfirst(strtolower($property->zoning_types[0])); ?></font></td></tr>
                                <?php } ?>   
									</table>
								</td>
							</tr>
							<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="22"></td></tr>
							<tr>
								<td>
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td><a href="#"><img src="<?php echo placester_get_template_url(); ?>btn-more-details.png" border="0" width="153" height="50" vspace="0" hspace="0" align="left"></a></td>
											<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="14" height="1" vspace="0" hspace="0" align="left"></td>
											<td><a href="#"><img src="<?php echo placester_get_template_url(); ?>btn-more-listings.png" border="0" width="160" height="50" vspace="0" hspace="0" align="left"></a></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="44" height="1"></td>
					<td>
						<table bgcolor="#191919" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid #2d2d2d;">
							<tr><td><img src="<?php echo $property->images[0]->url; ?>" width="518" height="355" vspace="0" hspace="0" align="left"></td></tr>
						</table>
					</td>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="31" height=""></td>
				</tr>
				<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="37"></td></tr>
			</table>
		</td>
	</tr>
	<!-- /intro-block -->
	<tr>
		<td>
			<!-- main area -->
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" height="22" width="1"></td>
				</tr>
				<tr valign="top">
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" height="1" width="29"></td>
					<!-- content -->
					<td>
						<h2 style="margin: 0 0 25px;"><font style="font-size:24px;line-height:28px; font-weight:normal;" size="3" color="#ffe8b0" face="Arial, Helvetica, sans-serif">Property Description</font></h2>
                        <p style="margin: 0 0 40px; font: 15px/28px Arial, Helvetica, sans-serif; color:#ffffff;"><?php echo placester_get_placeholder_if_empty( $property->description, 'description' ); ?></p>
<?php 
                        if ( !empty($property->amenities) ) {
?>      
						<h2 style="margin: 0 0 25px;"><font style="font-size:24px;line-height:28px; font-weight:normal;" size="3" color="#ffe8b0" face="Arial, Helvetica, sans-serif">Property Features</font></h2>
						<table width="555" border="0" cellspacing="0" cellpadding="0" style="margin: 0 0 40px;">
                         <?php foreach( $property->amenities as $index => $feature ) { ?> 
                         <tr bgcolor="<?php $color = ( $index % 2 ) ? '#121212' : '#000000'; echo $color;?>">
								<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="10" height="1"></td>
                                <td><font style="font-size:13px;line-height:28px;" size="2" color="#FFFFFF" face="Arial, Helvetica, sans-serif"><?php echo ucfirst(strtolower($feature)); ?></font></td>
								<td><font style="font-size:13px;line-height:28px;" size="2" color="#FFFFFF" face="Arial, Helvetica, sans-serif">Yes</font></td>
							</tr>
                             <?php } // end foreach ?>      
						</table>
                    <?php } ?>
						<h2 style="margin: 0 0 6px;"><font style="font-size:24px;line-height:28px; font-weight:normal;" size="3" color="#ffe8b0" face="Arial, Helvetica, sans-serif">Location Map</font></h2>
						<h3 style="margin: 0 0 15px;"><font style="font-size:15px;line-height:28px; font-weight:normal;" size="3" color="#fc7802" face="Arial, Helvetica, sans-serif"><i><?php echo $property->location->full_address; ?></i></font></h3>
						<table bgcolor="#373737" border="0" cellspacing="0" cellpadding="5">
                            <tr><td>
    <img src="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace( ' ', '+', $property->location->full_address ); ?>&zoom=14&size=545x250&maptype=roadmap&markers=color:blue|label:S|<?php echo $property->location->coords->latitude; ?>,<?php echo $property->location->coords->longitude; ?>&sensor=false" /> 
						</table>
					</td>
					<!-- /content -->
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="47" height="1"></td>
					<!-- sidebar -->
					<td>
						<table width="308" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font style="font-size:24px;line-height:28px; font-weight:normal;" size="3" color="#ffe8b0" face="Arial, Helvetica, sans-serif">Additional Photos</font></td>
							</tr>
							<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="16"></td></tr>
<?php 
$column = 1;
$row = 1;
foreach ( $property->images as $index => $img ) {
  
        echo '<tr>';
        echo 
            '<td>' . "\n" . 
                '<table border="0" bgcolor="#4E4D4C" cellspacing="0" cellpadding="3">' . "\n" .
                    '<tr>' . "\n" .
                        '<td>' . "\n" .
                            '<img src="' . $img->url . '" width="302" />' . "\n" .
                        '</td>' . "\n" .
                    '</tr>' . "\n" .
                '</table>' . "\n" .
            '</td>';
        echo '<tr><td>' . "\n" . '<img src="' . placester_get_template_url() . 'none.gif" width="1" height="10" />' . "\n" . '</td></tr>';
        echo '</tr>';
}
?>   
						</table>
					</td>
					<!-- /sidebar -->
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="30" height="1"></td>
				</tr>
			</table>
			<!-- /main area -->
		</td>
	</tr>
	<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="41"></td></tr>
	<!-- footer -->
	<tr bgcolor="#1a1a1a" background="<?php echo placester_get_template_url(); ?>bg-footer.gif">
		<td>
			<table width="969" border="0" cellspacing="0" cellpadding="0">
				<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="10"></td></tr>
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="12" height="1"></td>
					<td width="790"><font style="font-size:16px;line-height:20px;" size="2" color="#484747" face="Arial, Helvetica, sans-serif">Powered by </font><a href="http://placester.com/tools/craigslist/"><font style="font-size:16px;line-height:20px;" size="2" color="#ADACAC" face="Arial, Helvetica, sans-serif">Placester</font></a></td>
					<td>
						<a href="#" style="text-decoration: none;"><font style="font-size:36px;line-height:40px;" size="6" color="#FFFFFF" face="Arial, Helvetica, sans-serif"><?php bloginfo('name'); ?></font></a>
						<br>
						<font style="font-size:10px;line-height:14px;" size="1" color="#ffe8b0" face="Arial, Helvetica, sans-serif"><?php echo get_bloginfo('description'); ?></font>
					</td>
				</tr>
				<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="10"></td></tr>
			</table>
		</td>
	</tr>
	<!-- /footer -->
</table>
</div>
