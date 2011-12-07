<?php

$name = $_REQUEST['template_iframe'];
$name = preg_replace('/[^0-9A-Za-z_]/', '', $name);

if ( strlen( $name ) <= 0)
{
?>
<style type="text/css">
html {
    background-color: #eee;
}
#default_template_text {
    text-align: center;
    display: block;
    font-size: 14px;
    font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
    margin-top: 225px;
    color: #444;
}  
</style>
    <div id="default_template_text">
        Please select a template from the list of templates at the right to 
        view the preview.
    </div>
    <?php
    return;
}



// load template
list( $content, $thumbnail_url ) = placester_get_template_content( $name );

if ( ! isset( $_REQUEST['mode'] ) ) {
    echo $content;
}
else {
    ?>
    <input type="hidden" id="thumbnail_url" value="<?php echo htmlspecialchars($thumbnail_url); ?>" />
    <textarea id="textarea_content" 
        style="width: 100%; height: 380px"><?php echo htmlspecialchars($content); ?></textarea>
    <?php
}
