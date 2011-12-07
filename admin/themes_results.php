<?php

/**
 * Admin interface: Get Themes tab
 * Search results page
 */

global $theme_field_defaults;

$type = isset($_REQUEST['type']) ? stripslashes( $_REQUEST['type'] ) : '';
$term = isset($_REQUEST['s']) ? stripslashes( $_REQUEST['s'] ) : '';
$page = isset($_REQUEST['paged']) ? stripslashes( $_REQUEST['paged'] ) : '1';

$args = array();

switch ($type)
{
    case 'tag':
        $terms = explode(',', $term);
        $terms = array_map('trim', $terms);
        $terms = array_map('sanitize_title_with_dashes', $terms);
        $args['tag'] = $terms;
        break;
    case 'term':
        $args['search'] = $term;
        break;
    case 'author':
        $args['author'] = $term;
        break;
}

$args['page'] = $page;
$args['fields'] = $theme_field_defaults;

if (!empty($_POST['features']))
{
    $terms = $_POST['features'];
    $terms = array_map('trim', $terms);
    $terms = array_map('sanitize_title_with_dashes', $terms);
    $args['tag'] = $terms;
    $_REQUEST['s'] = implode(',', $terms);
    $_REQUEST['type'] = 'tag';
}

if (!isset($args['tag']))
    $args['tag'] = array();
$args['tag'][] = 'placester';   // Hardcoded query

$api = themes_api('query_themes', $args);

if (is_wp_error($api))
    wp_die($api);

?>
<ul class="subsubsub">
  <li><a href='admin.php?page=placester_themes'>Search</a> | </li>
  <li><b>Search results</b></li>
</ul>
<br class="clear" />
<?php

display_themes($api->themes, $api->info['page'], $api->info['pages']);
