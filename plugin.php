<?php
/*
Plugin Name: GIF Animation Preview
Description: Replace GIF animations to a single preview image. Click on the image to start animate.
Version: 1.1
Author: Andras Serfozo
Author URI: http://twitter.com/SubZtep
License: GPLv2 or later
*/

if (! is_admin()) {
    require_once( __DIR__ . '/gap.class.php' );
    GIF_Animation_Preview::load();
}

?>