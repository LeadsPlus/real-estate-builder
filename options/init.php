<?php
if ( !function_exists( 'placester_of_init' ) ) {
    define( 'PL_OPTIONS_FRAMEWORK_DIRECTORY', plugins_url( '', __FILE__ ) . '/' );
    define( 'PL_OPTIONS_FRAMEWORK_URL', dirname( __FILE__) );
    require_once ( PL_OPTIONS_FRAMEWORK_URL . '/core.php');
}
