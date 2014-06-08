<?php
class GIF_Animation_Preview {

    public $preview_suffix = '-gap'; // Preview image filename suffix

    private static $plugin;
    static function load() {
        $class = __CLASS__;
        return ( self::$plugin ? self::$plugin : ( self::$plugin = new $class() ) );
    }

    private function __construct() {
        add_action( 'the_content', array( $this, 'replace_gifs' ) );
    }

    public function replace_gifs( $content ) {
        // Find all img tags in the post
        return preg_replace_callback( '/<img[^>]+>/i', array( $this, 'process_img' ), $content );
    }

    function process_img( $img_tag ) {
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
            $new_src = $this->get_preview_url( $src[2] );
            if ( ! $new_src ) {
                $new_src = $this->generate_preview( $src[2] );
            }
            if ( $new_src ) {
                return $src[1] . $new_src . $src[3] . ' data-gif=' . $src[3] . $src[2] . $src[3];
            }
        }
        // Not gif nor animation, do nothing
        return $src[0];
    }

    function get_preview_url( $original_url ) {
        $img_path = $this->get_path_from_url( $original_url );
        $preview_filename = pathinfo( $img_path, PATHINFO_FILENAME ) . $this->preview_suffix . '.jpg';
        if ( file_exists( dirname( $img_path ) . '/' . $preview_filename ) ) {
            return pathinfo( $original_url, PATHINFO_DIRNAME ) . '/' . $preview_filename;
        }
        return false;
    }

    public function is_animation( $filename ) {
        if ( ! ( $fh = @fopen( $filename, 'rb' ) ) ) {
            return false;
        }
        $count = 0;
        while ( ! feof($fh) && $count < 2 ) {
            $chunk = fread( $fh, 1024 * 100 );
            $count += preg_match_all( '#\x00\x21\xF9\x04.{4}\x00\x2C#s', $chunk, $matches );
        }
        fclose( $fh );
        return $count > 1;
    }

    public function generate_preview( $filename ) {
        $img_path = $this->get_path_from_url( $filename );
        $preview_file = pathinfo( $img_path, PATHINFO_FILENAME ) . $this->preview_suffix . '.jpg';
        $image = @imagecreatefromgif( $img_path );
        if ( $image === false ) {
            return false;
        }
        $w = imagesx( $image );
        $h = imagesy( $image );
        $cut = imagecreatetruecolor( $w, $h );
        imagecopy( $cut, $image, 0, 0, 0, 0, $w, $h );
        $res = imagejpeg( $cut, dirname( $img_path ) . '/' . $preview_file, 80 );
        if (! $res) {
            return false;
        }
        return pathinfo( $filename, PATHINFO_DIRNAME ) . '/' . $preview_file;
    }

    private function get_path_from_url( $filename ) {
        $site_url = get_site_url();
        if ( strpos( $filename, $site_url ) === 0 ) {
            $url = substr( $filename, strlen( $site_url ) );
            if ( substr( $url, 0, 1 ) != '/' ) {
                $url = '/' . $url;
            }
            return realpath( ABSPATH ) . $url;
        }
        return false;
    }
}
?>