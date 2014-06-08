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

    protected function process_img( $img_tag ) {
        // Update src property
        $patterns = array( '/(src=")([^"]+)(")/i', '/(src=\')([^\']+)(\')/i' );
        $new_img_tag = preg_replace_callback( $patterns, array( $this, 'update_src' ), $img_tag[0] );
        if ( $img_tag[0] == $new_img_tag ) {
            // Not supported gif, do nothing
            return $img_tag[0];
        }
        return $new_img_tag;
    }

    protected function update_src( $src ) {
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

    protected function get_preview_url( $original_url ) {
        $path = $this->get_blog_img_dir( $original_url, true );
        $preview_filename = $this->get_preview_filename( $original_url );

        if ( file_exists( $path . $preview_filename ) ) {
            $url = $this->get_blog_img_dir( $original_url, false );
            return $url . '/' . $preview_filename;
        }
        return false;
    }

    protected function is_local_image( $img_url ) {
        return strpos( $img_url, get_site_url() ) === 0;
    }

    protected function get_blog_img_dir( $img_url, $is_path = true ) {
        if ( $this->is_local_image( $img_url ) ) {
            $site_url = get_site_url();
            $url = substr( $img_url, strlen( $site_url ) );
            $pre = $is_path ? realpath( ABSPATH ) : $site_url;
            return pathinfo( $pre . $url, PATHINFO_DIRNAME ) . '/';
        }
        $dir = wp_upload_dir( get_the_date( 'Y/m' ) );
        return $dir[ $is_path ? 'path' : 'url' ] . '/';
    }

    protected function get_preview_filename( $img_src ) {
        return substr( $this->mb_basename( $img_src ), 0, -4 ) . $this->preview_suffix . '.jpg';
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

    protected function generate_preview( $img_url ) {
        if ( ! $this->is_animation( $img_url ) ) {
            return false;
        }

        $img_path = $this->get_blog_img_dir( $img_url, true );
        if ( $this->is_local_image( $img_url ) ) {
            $image = imagecreatefromgif( $img_path . $this->mb_basename( $img_url ) );
        } else {
            //FIXME: curl?
            $image = imagecreatefromgif( $img_url );
        }
        if ( ! $image ) {
            return false;
        }

        $preview_file = $this->get_preview_filename( $img_url );
        $w = imagesx( $image );
        $h = imagesy( $image );
        $cut = imagecreatetruecolor( $w, $h );
        imagecopy( $cut, $image, 0, 0, 0, 0, $w, $h );
        $res = imagejpeg( $cut, $img_path . $preview_file, 80 );
        if ( ! $res ) {
            return false;
        }
        return $this->get_blog_img_dir( $img_url, false ) . $preview_file;
    }

    function mb_basename($filepath, $suffix = NULL) {
        $splited = preg_split ( '/\//', rtrim ( $filepath, '/ ' ) );
        return substr ( basename ( 'X' . $splited [count ( $splited ) - 1], $suffix ), 1 );
    }
}
?>