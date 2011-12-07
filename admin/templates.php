<?php
/**
 *  Craiglist templates
 */
if ( isset($_GET['id']) ) {

    $current_template_name = '';

    // if ( isset( $_REQUEST['save_template_as'] ) ) {
        // $template_name = $_REQUEST['save_template_name'];
        // $v = stripslashes( $_REQUEST['save_template_content'] );

        // $post = array(
            // 'post_type' => 'placester_template',
            // 'post_title' => $template_name,
            // 'post_status' => 'publish',
            // 'post_author' => 1,
            // 'post_content' => $v
        // );

        // $post_id = wp_insert_post( $post );
        // if ( $post_id > 0 ) {
            // delete_post_meta( $post_id, 'thumbnail_url' );
            // add_post_meta( $post_id, 'thumbnail_url', $_REQUEST['save_thumbnail_url'] );
        // }

        // placester_success_message( 'Template saved for that listing' );
        // $current_template_name = 'user_' . $post_id;
    // }

    // if ( isset( $_REQUEST['save_template'] ) ) {
        // $template_name = $_REQUEST['save_template_name'];
        // $v = stripslashes( $_REQUEST['save_template_content'] );

        // $post_id = substr( $_REQUEST['current_template_name'], 5 );
        // $post = array(
            // 'ID' => $post_id,
            // 'post_type' => 'placester_template',
            // 'post_content' => $v
        // );

        // wp_update_post( $post );

        // placester_success_message( 'Template saved for that listing' );
        // $current_template_name = 'user_' . $post_id;
    // }

    // if ( isset( $_REQUEST['delete_template'] ) ) {
        // $post_id = substr( $_REQUEST['current_template_name'], 5 );
        // wp_delete_post( $post_id );

        // placester_success_message( 'Template was deleted' );
        // $current_template_name = '';
    // }

    //
    // load templates list
    //

    $templates = placester_get_templates();

    //
    // define active template
    //

    $base_iframe_url = 'admin.php?page=placester_properties';

    $current_name = '';
    $current_iframe_src = $base_iframe_url . '&craigslist_template=1&template_iframe=';

    // if ( strlen( $current_template_name ) > 0 ) {
        // $current_name = $current_template_name;
        // $current_iframe_src = $base_iframe_url .
            // '&template_iframe=' . $current_template_name;
    // }


?>
    <script>
    var placester_theme_url = '<?php bloginfo( 'template_directory' ) ?>';
    </script>

<div class="wrap">
    <?php placester_admin_header( 'placester_properties' ) ?>

    <form method="post" action="admin.php?page=placester_properties&craigslist_template=1">
        <input type="hidden" id="current_template_name" name="current_template_name" 
            value="<?php echo htmlspecialchars( $current_name ) ?>" />
        <div class="template_menu">
            <h3>Templates</h3>
<?php
    foreach ( $templates as $i ) {
?>
                <div>
                    <img src="<?php echo $i['thumbnail_url'] ?>" class="template_item" 
                        system_name="<?php echo $i['system_name'] ?>" 
                        title="<?php echo $i['name'] ?>" />
                </div>
<?php
    }
?>
        </div>

        <div class="template_preview">
            <div>
                <h3>Preview</h3>
                <a class="edit_listing button" href="<?php echo $base_iframe_url . "&id=" . $_GET['id'] ?>" tabindex="4">Edit this listing</a>

                 <div id="copy_craigslist_container">
                   <div id="copy_craigslist">Get code for craigslist</div>
                </div>                   
            </div>
            <iframe src="<?php echo $current_iframe_src ?>" id="preview_iframe"></iframe>
        </div>
    </form>
</div>
<?php
} else {
  placester_error_message( 'Error: Wrong entry point. Please access this page by clicking the "Post to craigslist" link in the plugin <a href="' . admin_url() . 'admin.php?page=placester_properties">My Listings</a> tab.' );  
}
/**
 *  
 */
