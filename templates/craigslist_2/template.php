<?php 
$property_id = $_GET['property_id'];
$property = placester_property_get($property_id);
$purchase_type =  !empty( $property->purchase_types ) ? $property->purchase_types[0] : '';
$property_type = ucfirst(strtolower(str_replace('_', ' ', $property->property_type)));
?>
	<style type="text/css">
		a {text-decoration:none;}
		a:hover {text-decoration:underline;}
		body {color: #666666; font-family: Arial, Helvetica, sans-serif;}
	</style>

<div id="template_container" style="display:none">
    <table border="0" width="969" bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" style="border-width: 0 1px; border-color: #4c4c4c; border-style: solid;">
        <!-- header -->
        <tr>
            <td>
                <table width="969" border="0" cellspacing="0" cellpadding="0" background="<?php echo placester_get_template_url(); ?>bg-header.gif">
                    <tr>
                        <td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="8" /></td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="13" height="1"></td>
                        <td>
                <table border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="5">
                <tr><td><img src="<?php echo placester_get_user_details()->logo_url; ?>" width="90" vspace="0" hspace="0" align="left"></td></tr>
                </table>
            </td>
            <td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="12" height="1"></td>
            <td width="650">
                <font style="font-size:24px;line-height:24px;font-weight: bold;" size="3" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->first_name, 'first name' ) . ' ' . placester_get_placeholder_if_empty( placester_get_user_details()->last_name, 'last name' ); ?></font>
                <br>
                <font style="font-size:12px;line-height:14px;" size="3" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo placester_get_placeholder_if_empty( placester_get_user_details()->phone, 'phone' ) ?><br>Email:
                <a href="#"><font style="font-size:12px;line-height:14px;" size="3" color="#287aac" face="Arial, Helvetica, sans-serif"><?php echo placester_get_user_details()->email; ?></font></a>
                 </font>
            </td>
            <td>
                <a href="#" style="text-decoration: none;"><font style="font-size:36px;line-height:40px;" size="6" color="#ee7d03" face="Arial, Helvetica, sans-serif"><?php bloginfo('name'); ?></font></a>
                <br>
                <font style="font-size:14px;line-height:18px;" size="2" color="#666666" face="Arial, Helvetica, sans-serif"><?php echo bloginfo('description'); ?></font>
            </td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="18" /></td>
                    </tr>
                </table>
		</td>
	</tr>
	<!-- /header -->
	<!-- intro-block -->
	<tr>
		<td>
			<table	width="969" border="0" cellspacing="0" cellpadding="0" bgcolor="#264b72" background="<?php echo placester_get_template_url(); ?>bg-intro.gif">
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="22"></td>
				</tr>
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="13" height="1"></td>
					<td width="532">
					<table border="0" cellspacing="0" cellpadding="8" bgcolor="#ffffff">
				<tr>
					<td><img src="<?php echo $property->images[0]->url; ?>" width="516" height="370" vspace="0" hspace="0" align="left"></td>
				</tr>
				</table>
					</td>
					<td>
					<img src="<?php echo placester_get_template_url(); ?>none.gif" width="42" height="1">
					</td>
					<td>
					<table border="0" cellspacing="0" cellpadding="0" width="370">
				<tr>
					<td><font style="font-size:24px;line-height:28px;font-weight:bold;" size="3" color="#ffffff" face="Arial, Helvetica, sans-serif"><?php echo $property->bedrooms; ?> Bedroom, <?php echo $property->bathrooms; ?> Bathroom<br> <?php echo $property_type; ?></font></td>
				</tr>
				<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="14" /></td></tr>
				<tr>
					<td>
							<table bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="5">
								<tr>
									<td>
									<table width="358" border="0" cellspacing="0" cellpadding="11">
											<tr bgcolor="#EAEAEA">
					<td width="105"><font style="font-size:14px;line-height:14px;" size="3" color="#1d1d1d" face="Arial, Helvetica, sans-serif"><b>Property Type:</b></font></td>
					<td><font style="font-size:14px;line-height:14px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><?php echo $property_type; ?></font></td>
					</tr>
					<tr>
					<td width="105"><font style="font-size:14px;line-height:14px;" size="3" color="#1d1d1d" face="Arial, Helvetica, sans-serif"><b>Bedrooms:</b></font></td>
					<td><font style="font-size:14px;line-height:14px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><?php echo $property->bedrooms; ?></font></td>
					</tr>
					<tr bgcolor="#EAEAEA">
					<td width="105"><font style="font-size:14px;line-height:14px;" size="3" color="#1d1d1d" face="Arial, Helvetica, sans-serif"><b>Bathrooms:</b></font></td>
					<td><font style="font-size:14px;line-height:14px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><?php echo $property->bathrooms; ?></font></td>
					</tr>
					<tr>
                    <td width="105"><font style="font-size:14px;line-height:14px;" size="3" color="#1d1d1d" face="Arial, Helvetica, sans-serif"><b>Price:</b></font></td>
					<td><font style="font-size:14px;line-height:14px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><?php echo '$' . $property->price; if ( $purchase_type == "rental" ) echo " per month"; ?></font></td>          
					</tr>

                       <?php if ( !empty($property->zoning_types) ) { ?>     
					<tr bgcolor="#EAEAEA">
					<td width="105"><font style="font-size:14px;line-height:14px;" size="3" color="#1d1d1d" face="Arial, Helvetica, sans-serif"><b>Zoning Type:</b></font></td>
					<td><font style="font-size:14px;line-height:14px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><?php echo ucfirst(strtolower($property->zoning_types[0])); ?></font></td>  
					</tr>
                        <?php } ?>   

					
					
									</table>
									</td>
								</tr>
								</table>
					</td>
				</tr>
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="18"></td>
				</tr>
				<tr>
					<td>
					<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td><a href="<?php echo site_url( '/listing/' . $property_id . '/' ); ?>"><img src="<?php echo placester_get_template_url(); ?>btn-learn-more.gif" border="0" width="148" height="51" vspace="0" hspace="0" align="left"></a></td>
									<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="9" height="1"></td>
									<td><a href="<?php echo site_url(); ?>"><img src="<?php echo placester_get_template_url(); ?>btn-more-listings.gif" border="0" width="171" height="51" vspace="0" hspace="0" align="left"></a></td>
								</tr>
							</table>
					
					
					
					</td>
				</tr>
				</table>
					</td>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="12" height="1"></td>
				</tr>
				<tr>
					<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="17"></td>
				</tr>
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
			<td><img src="<?php echo placester_get_template_url(); ?>none.gif" height="1" width="13"></td>
			<!-- content -->
			<td>
	<h2 style="margin: 0 0 20px;"><font style="font-size:24px;line-height:28px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><b>Description</b></font></h2>
	<p style="margin: 0 0 20px; font-size:14px;line-height:18px;"><?php echo placester_get_placeholder_if_empty( $property->description, 'description' ); ?></p>
	<h2 style="margin: 0 0 20px;"><font style="font-size:24px;line-height:28px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><b>Photos</b></font></h2>
	<table border="0" cellspacing="0" cellpadding="0">
     <?php 
$column = 1;
$row = 1;
foreach ( $property->images as $index => $img ) {
    if ( $column == 1 ) {
        echo '<tr>';
        echo 
            '<td>' . "\n" . 
                '<table border="0" bgcolor="#EFEFEF" cellspacing="0" cellpadding="4">' . "\n" .
                    '<tr>' . "\n" .
                        '<td>' . "\n" .
                            '<img src="' . $img->url . '" width="249" />' . "\n" .
                            '</td>' . "\n" .
                        '</tr>' . "\n" .
                '</table>' . "\n" .
            '</td>';
        echo '<td>' . "\n" . '<img src="' . placester_get_template_url() . 'none.gif" width="10" height="1" />' . "\n" . '</td>';
        $column++;
    } else if ( $column == 2 ) {
         echo 
            '<td>' . "\n" . 
                '<table border="0" bgcolor="#EFEFEF" cellspacing="0" cellpadding="4">' . "\n" .
                    '<tr>' . "\n" .
                        '<td>' . "\n" .
                            '<img src="' . $img->url . '" width="249" />' . "\n" .
                            '</td>' . "\n" .
                        '</tr>' . "\n" .
                '</table>' . "\n" .
            '</td>';          
        echo '</tr>';
        $column = 1;
        $row++;
        if ( !( $row % 2 ) ) {
            echo '<tr>' . "\n" . '<td><img src="' . placester_get_template_url() . 'none.gif" width="1" height="10"></td>' . "\n" . '</tr>';
            $row++;
        } 
    }
}
?>    
		<tr>
		<td>
			<table border="0" bgcolor="#EFEFEF" cellspacing="0" cellpadding="4">
			<tr>
				<td><a href="#"><img src="img3.jpg" width="249" height="222" border="0" vspace="0" hspace="0" align="left"></a></td>
			</tr>
			</table>
		</td>
		<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="9" height="1" /></td>
		<td>
			<table border="0" bgcolor="#EFEFEF" cellspacing="0" cellpadding="4">
			<tr>
				<td><a href="#"><img src="img4.jpg" width="249" height="222" border="0" vspace="0" hspace="0" align="left"></a></td>
			</tr>
			</table>
		</td>
		</tr>
		<tr>
		<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="5" /></td>
		</tr>
	  
	</table>
			</td>
	<!-- /content -->
	<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="42" height="1"></td>
	<!-- sidebar -->
	<td>
	<table width="368" border="0" cellspacing="0" cellpadding="0">
          <?php 
if ( !empty($property->amenities) ) {
?>   
		<tr>
		<td><font style="font-size:24px;line-height:28px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><b>Features</b></font></td>
		</tr>
		<tr><td><img src="#" width="1" height="11"></td></tr>
         <?php foreach( $property->amenities as $index => $feature ) { ?>  
        <tr><td><font style="font-size:14px;line-height:29px;" size="3" face="Arial, Helvetica, sans-serif"><?php echo ucfirst(strtolower($feature)); ?></font></td></tr>
        <?php 
            if ( ( $index + 1 ) < count( $property->amenities ) ) {
        ?>
		<tr><td bgcolor="#dee9f0"><img src="<?php echo placester_get_template_url(); ?>none.gif" height="1" width="1"></td></tr>
         <?php 
            }
        } // end foreach ?> 
		<tr><td><img src="#" width="1" height="11"></td></tr>
<?php 
}       
?>
		<tr>
		<td><font style="font-size:24px;line-height:28px;" size="3" color="#828181" face="Arial, Helvetica, sans-serif"><b>Location</b></font></td>
		</tr>
		<tr><td><img src="#" width="1" height="11"></td></tr>
         <tr>
            <td>
                  <address style="margin:0 0 10px;"><font style="font-size:14px;line-height:22px;font-style:normal;" size="3" face="Arial, Helvetica, sans-serif"><b><?php echo $property->location->full_address; ?></b></font></address>       
            </td>
        </tr>      
		<tr>
		<td>
			<table bgcolor="#ececec" border="0" cellspacing="0" cellpadding="4">
			<tr>
                <td>
                    <img src="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace( ' ', '+', $property->location->full_address ); ?>&zoom=14&size=355x241&maptype=roadmap&markers=color:blue|label:S|<?php echo $property->location->coords->latitude; ?>,<?php echo $property->location->coords->longitude; ?>&sensor=false" />
                </td>
			</tr>
			</table>
		</td>
		</tr>
	</table>
	</td>
	<!-- /sidebar -->
	<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="13" height="1"></td>
		</tr>
	</table>
		<!-- /main area -->
		</td>
	</tr>
	<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="25" /></td></tr>
	<!-- footer -->
	<tr bgcolor="#1a1a1a" background="<?php echo placester_get_template_url(); ?>bg-footer.gif">
<td>
<table width="969" border="0" cellspacing="0" cellpadding="0">
<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="10"></td></tr>
<tr valign="top">
	<td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="12" height="1"></td>
	<td width="790"><a style="text-decoration:underline;" href="http://placester.com/tools/craigslist/"><font style="font-size:12px;line-height:14px;" size="2" color="#DCDCDC" face="Arial, Helvetica, sans-serif"><b>Powered by Placester</b></font></a></td>
	<td>
	<a href="#" style="text-decoration: none;"><font style="font-size:36px;line-height:40px;" size="6" color="#e07b04" face="Arial, Helvetica, sans-serif"><?php bloginfo('name'); ?></font></a>
	<br>
	<font style="font-size:14px;line-height:18px;" size="2" color="#CECECE" face="Arial, Helvetica, sans-serif"><?php echo get_bloginfo('description'); ?></font>
	</td>
</tr>
<tr><td><img src="<?php echo placester_get_template_url(); ?>none.gif" width="1" height="10"></td></tr>
</table>
</td>
	</tr>
<!-- /footer -->
</table>
<style type="text/css">
	a {text-decoration:none;}
	a:hover {text-decoration:underline;}
	body {color: #666666; font-family: Arial, Helvetica, sans-serif;}
</style>
</div>
