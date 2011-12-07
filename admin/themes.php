<?php

/**
 * Admin interface: Get Themes tab
 * Entry point
 */

include(ABSPATH . 'wp-admin/includes/theme-install.php');

?>
<div class="wrap">
  <?php 

  placester_admin_header('placester_themes');

    $url = "http://api.placester.com/v1.0/themes.xml";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return a string
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $data = curl_exec($ch);
    curl_close($ch);

    // Well form the output
    $data = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $data;

    $data = explode( '<buy-button>', $data );
    $data = implode( '<buybutton><![CDATA[', $data );

    $data = explode( '</buy-button>', $data );
    $data = implode( ']]></buybutton>', $data );

    $error = false; 
    try {
        $xml_themes = new SimpleXMLElement($data, LIBXML_NOCDATA);
    }
    catch (Exception $e) {
        $error_message = $e->getMessage();
        $error = true;
    }
    if ($error) {
        placester_error_message( $error_message ); 
        return;
    }

    $themes_on_line = 3;
    $current_theme_index = 1;
?>
<div id="subscription-message" style="display: none; float: left; margin: 10px 0 10px 0; padding: 0 0 0 10px; font-size: 20px; background-color: #F0F5FA; border:1px solid #1F7DBF; ">
    <div style="float: left; width: 15%; margin: 20px 0 0 0;">Hey there!</div>
    <div style="float: left; width: 65%">
        <p><strong>Don't want to buy just one theme?</strong> Sign up for <a href="https://placester.com/subscriptions/individual/">Placester's Pro Package</a> and get a website and access to every theme placester has - no need to upgrade or buy anything new. <strong>Every time a new theme comes out, you'll automatically get it</strong>. Sign up Today</p>
    </div>
    <div style="float: left; width: 15%; margin: 20px 0 0 15px">
        <a href="https://placester.com/subscriptions/individual/">Learn More</a>
    </div>
    
</div>
<table id="availablethemes" cellspacing="0" cellpadding="0">
            <tbody id="the-list" class="list:themes">
<?php
    if ( $current_theme_index == 1 ) echo "<tr>";
    foreach ($xml_themes as $theme): 
        $price = $theme->price;        
        $free = ( strtoupper( $price ) != "FREE" ) ? false : true;
?>
    <td class="available-theme<?php echo ( $current_theme_index == 1 ) ? ' left' : ( ( $current_theme_index == 3 ) ? ' right' : '' ) ?>">
        <a href="<?php echo $theme->details ?>" class="screenshot">
            <img src="<?php echo $theme->screenshots->screenshot ?>" alt="<?php echo $theme->title; ?> theme screenshot" />
        </a>
        <h3><?php echo $theme->title;?></h3>
        <a href="<?php echo $theme->details ?>">Details</a>
<?php 
        if ( !$free ) { 
?>
        | Price: <?php echo $price;  
        } else {
?>
        | <a href="<?php echo $theme->download ?>">Download</a> | Free
<?php
        } // end if not free 
?>
        <p><?php echo $theme->description ?></p>
    <?php if ( !$free ) echo $theme->buybutton; ?>
    </td>
<?php
        $current_theme_index = ( $current_theme_index == $themes_on_line ) ? 1 : $current_theme_index + 1;
        if ( $current_theme_index == 1 ) echo "</tr>";
    endforeach;
?>
</tbody>
</table>
</div>
<script type="text/javascript" charset="utf-8">
    $('#subscription-message').slideDown(500);
</script>
