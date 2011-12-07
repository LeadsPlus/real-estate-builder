<?php 
$property_id = $_GET['property_id'];
$property = placester_property_get($property_id);
$purchase_type =  !empty( $property->purchase_types ) ? $property->purchase_types[0] : '';
?>
<div id="template_container" style="display:none">
<table bgcolor="#eaeaea" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%"> </td>
		<td>
			<table width="913" bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#eaeaea">
				  <td colspan="3"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="34" /></td>
				</tr>
				<tr>
                <td width="49"><img src="<?php echo placester_get_template_url(); ?>bg1.png" width="48" height="94" hspace="0" vspace="0" align="left" /></td>
				  <td width="815" bgcolor="#fafafa">
					<table border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td width="18"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="18" height="1" /></td>
					  <td width="104">
				<table border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="5" style="border-style:solid !important;border-color:#f1f1f1;border-width:1px;">
				  <tr><td><img src="<?php echo placester_get_user_details()->logo_url; ?>" width="92" height="75" /></td></tr>
				</table>
			  </td>
			  <td width="22"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="22" height="1" /></td>
			  <td width="250">
				<font style="font-size:18px;line-height:24px;" size="3" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->first_name, 'first name' ) . ' ' . placester_get_placeholder_if_empty( placester_get_user_details()->last_name, 'last name' ); ?></font>
				<br>
                <font style="font-size:18px;line-height:22px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->phone, 'phone' ) ?></font>
				<br>
				<a href="#" style="text-decoration:underline;"><font style="font-size:14px;line-height:22px;" size="3" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo placester_get_user_details()->email; ?></font></a>
			  </td>
              <td width="421" align="right"><font style="font-size:36px;line-height:40px;" size="6" color="#ee7d03" face="Arial, Helvetica, sans-serif"><?php bloginfo('name'); ?></font>
				<br>
                <font style="font-size:14px;line-height:18px;" size="2" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo get_bloginfo('description'); ?></font>			  </td>
              <td width="18"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="18" height="22" /></td>
			</tr>
				</table>
				  </td>
				  <td width="49"><img src="<?php echo placester_get_template_url(); ?>bg2.png" width="49" height="94" hspace="0" vspace="0" align="left" /></td>
				</tr>
				<tr>
				  <td colspan="3" width="913"><img src="<?php echo placester_get_template_url(); ?>bg3.png" width="913" height="35" /></td>
				</tr>
				<tr>
				  <td colspan="3">
					<table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td><img src="<?php echo placester_get_template_url(); ?>bg4.png" width="40" height="303" /></td>
                        <td><img src="<?php echo placester_get_template_url(); ?>img1.png" width="401" /></td>
                        <td><img src="<?php echo placester_get_template_url(); ?>bg5.png" width="29" height="303" /></td>
                        <td><img src="<?php echo placester_get_template_url(); ?>img1.png" width="401" /></td>
                        <td><img src="<?php echo placester_get_template_url(); ?>bg6.png" width="42" height="303" /></td>
					  </tr>
				</table>
				  </td>
				</tr>
				<tr>
		<td colspan="3" width="913"><img src="<?php echo placester_get_template_url(); ?>bg7.png" width="913" height="35" /></td>
	  </tr>
	  <tr>
		<td colspan="3">
		  <table border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td><img src="<?php echo placester_get_template_url(); ?>bg8.png" width="137" height="64" /></td>
              <td><a href="<?php echo site_url( '/listing/' . $property_id . '/' ); ?>"><img src="<?php echo placester_get_template_url(); ?>btn-more-details.png" width="218" height="64" border="0" /></a></td>
			  <td><img src="<?php echo placester_get_template_url(); ?>bg9.png" width="201" height="64" /></td>
              <td><a href="<?php echo site_url(); ?>" style="text-decoration:none;"><img src="<?php echo placester_get_template_url(); ?>btn-more-listings.png" width="218" height="64" border="0" /></a></td>
			  <td><img src="<?php echo placester_get_template_url(); ?>bg10.png" width="139" height="64" /></td>
			</tr>
		  </table>
		</td>
	  </tr>
	  <tr>
		<td colspan="3">
		  <table border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td><img src="<?php echo placester_get_template_url(); ?>bg11.png" width="137" height="59" /></td>
			  <td><img src="<?php echo placester_get_template_url(); ?>shadow-more-details.png" width="218" height="59" /></td>
			  <td><img src="<?php echo placester_get_template_url(); ?>bg12.png" width="201" height="59" /></td>
			  <td><img src="<?php echo placester_get_template_url(); ?>shadow-more-listings.png" width="218" height="59" /></td>
			  <td><img src="<?php echo placester_get_template_url(); ?>bg13.png" width="139" height="59" /></td>
			</tr>
		  </table>
		</td>
	  </tr>
	  <tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="42" /></td></tr>
	  <tr id="more-details">
		<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="40" height="1" /></td>
		<td>
		  <table border="0" cellspacing="0" cellpadding="0">
			<tr valign="top">
			  <!-- Sidebar start -->
			  <td width="355">
				<h2 style="margin:0 0 10px;"><font style="font-size:18px;line-height:22px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif">Photos</font></h2>
				<table border="0" cellspacing="0" cellpadding="0">
                  <tr>
<?php 
$column = 1;
$row = 1;
foreach ( $property->images as $index => $img ) {
	// Column 1
    if ( $column == 1 ) {
        echo '<tr>';
        echo '<td>' . "\n" . '<img src="' . $img->url . '" width="172" />' . "\n" . '</td>';
        echo '<td>' . "\n" . '<img src="' . placester_get_template_url() . 'none.gif" width="10" height="1" />' . "\n" . '</td>';
        $column++;
	// Column 2
    } else if ( $column == 2 ) {
        echo '<td>' . "\n" . '<img src="' . $img->url . '" width="172" />' . "\n" . '</td>';
        echo '</tr>';

        $column = 1;
        $row++;
		// Display
        if ( !( $row % 2 ) ) {
            echo '<tr>' . "\n" . '<td><img src="' . placester_get_template_url() . 'none.gif" width="1" height="10"></td>' . "\n" . '</tr>';
            $row++;
        } 
    }
}
?>
				</table>
				<h2 style="margin:0;"><font style="font-size:18px;line-height:22px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif">Location</font></h2>
                <address style="margin:0 0 10px;"><font style="font-size:14px;line-height:22px;font-style:normal;" size="3" color="#666666" face="'Lucida Sans Unicode', 'Lucida Grande', sans-serif"><b><?php echo $property->location->full_address; ?></b></font></address>
            
                <img src="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace( ' ', '+', $property->location->full_address ); ?>&zoom=14&size=355x241&maptype=roadmap&markers=color:blue|label:S|<?php echo $property->location->coords->latitude; ?>,<?php echo $property->location->coords->longitude; ?>&sensor=false" />
			  </td>
			  <!-- Sidebar end -->
			  <td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="26" height="1" /></td>
			  <!-- Content start -->
			  <td style="font-size:14px;line-height:18px;" width="434">
				<h2 style="margin:0 0 10px;"><font style="font-size:18px;line-height:22px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif"> Details</font></h2>
				<table border="0" cellspacing="0" cellpadding="0" style="font-size:14px;line-height:22px;">
                  <tr>
					<td width="6"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="6" height="1" /></td>
					<td width="201"><b>Property type:</b></td>
                    <td width="243"><?php echo ucfirst(strtolower(str_replace('_', ' ', $property->property_type))); ?></td>
				  </tr>
				  <tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="2" /></td></tr>          <tr bgcolor="#f4f4f4">
					<td width="6"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="6" height="1" /></td>
					<td width="201"><b>Price:</b></td>
                    <td width="243"><?php echo '$' . $property->price; if ( $purchase_type == "rental" ) echo " per month"; ?> </td>
				  </tr>
				  <tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="2" /></td></tr>
				  <tr>
					<td width="6"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="6" height="1" /></td>
					<td width="201"><b>Bathroom(s):</b></td>
                    <td width="243"><?php echo $property->bathrooms; ?></td>
				  </tr>
				  <tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="2" /></td></tr>
				  <tr bgcolor="#f4f4f4">
					<td width="6"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="6" height="1" /></td>
					<td width="201"><b>Bedroom(s):</b></td>
					<td width="243"><?php echo $property->bedrooms; ?></td>
				  </tr>
				  <tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="2" /></td></tr>
                  <?php if ( !empty($property->zoning_types) ) { ?>
				  <tr>
					<td width="6"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="6" height="1" /></td>
					<td width="201"><b>Zoning Type:</b></td>
					<td width="243"><?php echo ucfirst(strtolower($property->zoning_types[0])); ?></td>
				  </tr>
                  <?php } ?>
                </table>
<?php 
if ( !empty($property->amenities) ) {
?>
				<h2 style="margin:0 0 10px;"><font style="font-size:18px;line-height:22px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif">Features</font></h2>
				<table border="0" cellspacing="0" cellpadding="0" style="font-size:14px;line-height:22px;">
                <?php foreach( $property->amenities as $index => $feature ) { ?>
                <tr<?php if ( $index % 2 ) echo ' bgcolor="#f4f4f4"'; ?>>
					<td width="6"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="6" height="1" /></td>
                    <td width="201"><b><?php echo ucfirst(strtolower($feature)); ?>:</b></td>
					<td width="243">Yes</td>
				  </tr>
				  <tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="2" /></td></tr>
                <?php } // end foreach ?>
				</table>
<?php 
}
if ( !empty($property->description) ) {
?>
				<h2 style="margin:0 0 10px;"><font style="font-size:18px;line-height:22px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif">Description</font></h2>
                <p style="margin:0 0 30px;"><?php echo $property->description; ?></p>
<?php 
}
?>
			    </td>
			  <!-- Content end -->
			</tr>
			<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="15" /></td></tr>
			<tr>
			  <!-- contact information start -->
			  <td colspan="3">
				<table border="0" cellspacing="0" cellpadding="0">
				  <tr valign="top">
					<td>
					  <table border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="5" style="border-style:solid !important;border-color:#f1f1f1;border-width:1px;">
						<tr><td><img src="<?php echo placester_get_user_details()->logo_url; ?>" width="92" /></td></tr>
					  </table>
					</td>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="22" height="1" /></td>
					<td width="220">
					  <h2 style="margin:0 0 3px;"><font style="font-size:18px;line-height:22px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif">Contact:</font></h2>
					  <font style="font-size:16px;line-height:18px;" size="3" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->first_name, 'first name' ) . ' ' . placester_get_placeholder_if_empty( placester_get_user_details()->last_name, 'last name' ); ?></font>
					  <br>
					  <font style="font-size:16px;line-height:18px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->phone, 'phone' ) ?></font>
					  <br>
					  <a href="#" style="text-decoration:underline;"><font style="font-size:16px;line-height:18px;" size="3" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo placester_get_user_details()->email; ?></font></a>
					</td>
					<td>
                      <font style="font-size:14px;line-height:18px;" size="2" color="#666666" face="Arial, Helvetica, sans-serif"><strong>More about <?php echo placester_get_placeholder_if_empty( placester_get_user_details()->first_name, 'first name' ) ?>:</strong><br>
					<?php echo placester_get_placeholder_if_empty( placester_get_user_details()->description, 'description' ) ?></font>
					</td>
				  </tr>
                  <tr><td colspan="4"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="15" /></td>
                  </tr>
				</table>
			  </td>
			  <!-- contact information end -->
			</tr>
		  </table>
		</td>
		<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="40" height="1" /></td>
	  </tr>
	  <tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="3" /></td></tr>
	  <tr bgcolor="#1f1919">
		<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="40" height="1" /></td>
		<!-- footer start -->
		<td>
		  <table border="0" cellspacing="5" cellpadding="5">
			  <td width="350"><a style="text-decoration:underline;" href="http://placester.com/tools/craigslist/"><font style="font-size:14px;line-height:18px;" size="2" color="#999999" face="Arial, Helvetica, sans-serif"><b>Powered by Placester</b></font></a></td>
			  <td width="430" align="right">
				<font style="font-size:36px;line-height:40px;" size="6" color="#555555" face="Arial, Helvetica, sans-serif"><?php bloginfo('name'); ?></font>
				<br>
				<font style="font-size:14px;line-height:18px;" size="2" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo get_bloginfo('description'); ?></font>			  </td>
			</tr>
		  </table>
		</td>
		<!-- footer end -->
		<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="40" height="1" /></td>
          </tr>
			</table>
		</td>
		<td width="50%"></td>
	</tr>
    <tr bgcolor="#eaeaea">
      <td colspan="3"><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="34" /></td>
    </tr>
</table>
</div>
