<?php
/*
Plugin Name: GIF Animation Preview
Plugin URI: http://wordpress.org/plugins/gif-animation-preview/
Description: Replace GIF animations to a static preview image
Version: 1.8
Author: Andras Serfozo
Author URI: http://twitter.com/SubZtep
License: GPLv2 or later
*/
register_activation_hook( __FILE__, 'gap_test_env' );

function gap_test_env() {
    $errors = array();
    if ( version_compare( PHP_VERSION, '5.0', '<' ) )
        $errors[] = 'This plugin requires at least PHP version 5.0';
    if ( ! extension_loaded( 'gd' ) || ! function_exists( 'gd_info' ) )
        $errors[] = 'This plugin requires GD support';
    if ( ! empty( $errors ) ) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die( implode( '<br />', $errors ) . '<br /><br />Please contact with your hosting provider',
               'Plugin Activation Error',
               array( 'response' => 200, 'back_link' => true ) );
    }
    return true;
}

define( 'GAP_TYPE_OPTION_NAME', 'gap-type' );
define( 'GAP_TYPE_ALWAYS_PREVIEW', 1 );
define( 'GAP_TYPE_LOOP_PREVIEW', 2 );
define( 'GAP_TYPE_NEVER_PREVIEW', 3 );
define( 'GAP_MOBILE_OPTION_NAME', 'gap-mobile' );

if ( is_admin() ) {
    require_once( dirname( __FILE__ ) . '/settings.class.php' );
    GAP_Settings_Page::load();
} else {
    require_once( dirname( __FILE__ ) . '/gap.class.php' );
    GIF_Animation_Preview::load();
}
?>