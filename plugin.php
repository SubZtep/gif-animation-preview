<?php
/*
Plugin Name: GIF Animation Preview
Plugin URI: http://wordpress.org/plugins/gif-animation-preview/
Description: Replace GIF animations to a single preview image. Click on the image to start animate.
Version: 1.3
Author: Andras Serfozo
Author URI: http://twitter.com/SubZtep
License: GPLv2 or later
*/
register_activation_hook(__FILE__, 'gap_test_env');

function gap_test_env() {
    $errors = array();
    if (version_compare(PHP_VERSION, '5.0', '<')) {
        $errors[] = 'This plugin requires at least PHP version 5.0';
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

function gif_animation_preview_init() {
    wp_register_script( 'gifplayer', plugins_url( '/jquery.gifplayer.min.js', __FILE__ ), array( 'jquery' ), '0.1.4' );
    wp_register_script( 'imagesloaded', plugins_url( '/imagesloaded.pkgd.min.js', __FILE__ ), array(), '0.1.4' );
    wp_register_script( 'gif_animation_preview', plugins_url( '/plugin.js', __FILE__ ), array('jquery', 'gifplayer', 'imagesloaded') );
    wp_register_style( 'gifplayer', plugins_url( '/gifplayer.min.css', __FILE__ ), array(), '0.1.4' );
}

function gif_animation_preview_enqueue_scripts() {
    wp_enqueue_script( 'gifplayer' );
    wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script( 'gif_animation_preview' );
    wp_enqueue_style( 'gifplayer' );
}

if (! is_admin()) {
    add_action('init', 'gif_animation_preview_init');
    add_action('wp_enqueue_scripts', 'gif_animation_preview_enqueue_scripts');

    require_once( __DIR__ . '/gap.class.php' );
    GIF_Animation_Preview::load();
}

?>