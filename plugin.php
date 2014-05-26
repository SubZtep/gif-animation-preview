<?php
/*
Plugin Name: GIF Animation Preview
Plugin URI: http://wordpress.org/plugins/gif-animation-preview/
Description: Replace GIF animations to a single preview image. Click on the image to start animate.
Version: 1.2
Author: Andras Serfozo
Author URI: http://twitter.com/SubZtep
License: GPLv2 or later
*/

register_activation_hook(__FILE__, 'gap_test_env');

function gap_test_env() {
    $errors = array();
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        $errors[] = 'This plugin requires at least PHP version 5.4';
    }
    if (! extension_loaded('gd') || ! function_exists('gd_info')) {
        $errors[] = 'This plugin requires GD support';
    }
    if (! empty($errors)) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die(implode('<br />', $errors) . '<br /><br />Please contact with your hosting provider',
               'Plugin Activation Error',
               array( 'response' => 200, 'back_link' => true ) );
    }
    return true;
}

if (! is_admin()) {
    require_once( __DIR__ . '/gap.class.php' );
    GIF_Animation_Preview::load();
}

?>