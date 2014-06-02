<?php
require_once( __DIR__ . '/gifpic.class.php' );

class GIF_Animation_Preview {

    protected $gifpic;

    private static $plugin;
    static function load() {
        $class = __CLASS__;
        return ( self::$plugin ? self::$plugin : ( self::$plugin = new $class() ) );
    }

    private function __construct() {
        add_action( 'the_content', array( $this, 'replace_gifs' ) );
        $this->gifpic = new Gif_Pic();
    }

    public function replace_gifs( $content ) {
        // Find all img tags in the post
        return preg_replace_callback( '/<img[^>]+>/i', array( $this, 'process_img' ), $content );
    }

    function process_img( $img_tag ) {
        $original_src = false; // Original img src
        $new_src = false; // Gif anim preview src

        // Update src property
        $patterns = array( '/(src=")([^"]+)(")/i', '/(src=\')([^\']+)(\')/i' );
        $new_img_tag = preg_replace_callback( $patterns, array( $this, 'update_src' ), $img_tag[0] );
        if ( $img_tag[0] == $new_img_tag ) {
            // Not supported gif, do nothing
            return $img_tag[0];
        }
        return $new_img_tag;
    }

    function update_src( $src ) {
        // Test only gif
        if ( substr( strtolower( $src[2] ), -4) == '.gif' ) {
            $this->gifpic->filename = $src[2];
            if ( $this->gifpic->isAnimation() ) {
                // Get preview link
                $new_src = $this->gifpic->getPreview();
                if ($new_src) {
                    return $src[1] . $new_src . $src[3] . ' data-gif=' . $src[3] . $src[2] . $src[3];
                }
            }
        }
        // Not gif nor animation, do nothing
        return $src[0];
    }
}
?>