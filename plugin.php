<?php
/*
Plugin Name: GIF Animation Preview
Description: Replace GIF animations to a single preview image. Click on the image to start animate.
Version: 1.1
Author: Andras Serfozo
Author URI: http://twitter.com/SubZtep
License: GPLv2 or later
*/
require_once(__DIR__.'/gifpic.class.php');

class GifAnimationPreview {

    protected $gifpic;

    private static $plugin;
    static function load() {
        $class = __CLASS__; 
        return ( self::$plugin ? self::$plugin : ( self::$plugin = new $class() ) );
    }

    private function __construct() {
        add_action('the_content', array($this, 'replace_gifs'));
        $this->gifpic = new GifPic();
    }

    public function replace_gifs($content) {
        // Find all img tags in the post
        return preg_replace_callback('/<img[^>]+>/i', function($img_tag) {
            $original_src = false; // Original img src
            $new_src = false; // Gif anim preview src

            // Get src property
            $patterns = array('/(src=")([^"]+)(")/i', '/(src=\')([^\']+)(\')/i');
            $new_img_tag = preg_replace_callback($patterns, function($attr) use (&$original_src, &$new_src) {
                // Test only gif
                if (substr(strtolower($attr[2]), -4) == '.gif') {
                    $this->gifpic->filename = $attr[2];
                    if ($this->gifpic->isAnimation()) {
                        // Get preview link
                        $original_src = $attr[2];
                        $new_src = $this->gifpic->getPreview();
                        return $attr[1].$new_src.$attr[3];
                    }
                }
                // Not gif nor animation, do nothing
                return $attr[0];
            }, $img_tag[0]);

            // Apply modifiers if necessary
            if ($new_src !== false) {
                // overwrite onclick attribute
                $onclick = "if(this.src.indexOf('".$this->gifpic->preview_suffix."')!=-1){this.src='$original_src';}else{this.src='$new_src';}return false;";
                $found = false;
                $patterns = array('/(onclick=")([^"]+)(")/i', '/(onclick=\')([^\']+)(\')/i');
                $img_tag = preg_replace_callback($patterns, function($attr) use ($onclick, &$found) {
                    $found = true;
                    return $attr[1].$attr[2].$onclick.$attr[3];
                }, $new_img_tag);

                if (! $found) {
                    // no onclick attribute, simply insert onclick before src 
                    $patterns = array('/( src="[^"]+")/i', '/( src=\'[^\']+\')/i');
                    $img_tag = preg_replace_callback($patterns, function($attr) use ($onclick) {
                        return $attr[0].' onclick="'.$onclick.'"';
                    }, $new_img_tag);
                }
            }

            if (is_array($img_tag)) {
                return $img_tag[0];
            }
            return $img_tag;

        }, $content);
    }

}

GifAnimationPreview::load();
