<?php
/*
Plugin Name: GIF Animation Preview
Plugin URI: http://wordpress.org/plugins/gif-animation-preview/
Description: This plugin finds all of your posted animated GIF images to generate a simple preview and animate it as of your wish
Version: 1.10.3
Author: Andras Serfozo
Author URI: http://twitter.com/SubZtep
License: GPLv2 or later
*/
define( 'GAP_TYPE_OPTION_NAME', 'gap-type' );
define( 'GAP_TYPE_ALWAYS_PREVIEW', 1 );
define( 'GAP_TYPE_LOOP_PREVIEW', 2 );
define( 'GAP_TYPE_NEVER_PREVIEW', 3 );
define( 'GAP_MOBILE_OPTION_NAME', 'gap-mobile' );
define( 'GAP_EFFECT_OPTION_NAME', 'gap-effect' );
define( 'GAP_HOVER_OPTION_NAME', 'gap-hover' );
define( 'GAP_METADATA_OPTION_NAME', 'gap-metadata' );
define( 'GAP_THUMBNAIL_OPTION_NAME', 'gap-thumbnail' );

register_activation_hook( __FILE__, 'gap_test_env' );
register_uninstall_hook( __FILE__, 'gap_del_options' );

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

    add_option( GAP_TYPE_OPTION_NAME, GAP_TYPE_LOOP_PREVIEW );
    add_option( GAP_MOBILE_OPTION_NAME, 1 );
    add_option( GAP_EFFECT_OPTION_NAME, 1 );
    add_option( GAP_HOVER_OPTION_NAME, 1 );
    add_option( GAP_HOVER_METADATA_NAME, 0 );
    add_option( GAP_THUMBNAIL_OPTION_NAME, 1 );

    return true;
}

function gap_del_options() {
    delete_option( GAP_TYPE_OPTION_NAME );
    delete_option( GAP_MOBILE_OPTION_NAME );
    delete_option( GAP_EFFECT_OPTION_NAME );
    delete_option( GAP_HOVER_OPTION_NAME );
    delete_option( GAP_METADATA_OPTION_NAME );
    delete_option( GAP_THUMBNAIL_OPTION_NAME );
}


if ( is_admin() ) {
    require_once( dirname( __FILE__ ) . '/settings.class.php' );
    GAP_Settings_Page::load();
} else {
    require_once( dirname( __FILE__ ) . '/gap.class.php' );
    GIF_Animation_Preview::load();
}
?>