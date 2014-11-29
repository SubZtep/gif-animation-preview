<?php
class GIF_Animation_Preview {

    public $preview_suffix = '-gap'; // Preview image filename suffix

    protected static $plugin;
    public static function load() {
        $class = __CLASS__;
        return ( self::$plugin ? self::$plugin : ( self::$plugin = new $class() ) );
    }

    public function __construct() {
        add_action( 'get_header', array( $this, 'register_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'the_content', array( $this, 'replace_gifs' ) );
        if ( get_option( GAP_METADATA_OPTION_NAME ) )
            add_filter( 'get_post_metadata', array( $this, 'replace_metadata_gifs' ), 10, 3 );
        if ( get_option( GAP_THUMBNAIL_OPTION_NAME ) )
            add_filter( 'post_thumbnail_html', array( $this, 'replace_gifs' ) );
    }

    public function register_scripts() {
        wp_register_style( 'gapplayer', plugins_url( '/gapplayer.min.css', __FILE__ ), array(), '1.8.2' );
        wp_register_script( 'gapplayer', plugins_url( '/gapplayer.min.js', __FILE__ ), array(), '1.10', true ); // jQuery required
        wp_register_script( 'imagesloaded', plugins_url( '/imagesloaded.pkgd.min.js', __FILE__ ), array(), '3.1.8', true );

        if ( get_option( GAP_MOBILE_OPTION_NAME ) == 1 && wp_is_mobile() )
            $auto_load = false;
        else
            switch ( get_option( GAP_TYPE_OPTION_NAME, GAP_TYPE_ALWAYS_PREVIEW ) ) {
                case GAP_TYPE_NEVER_PREVIEW: $auto_load = true; break;
                case GAP_TYPE_LOOP_PREVIEW: $auto_load = is_singular(); break;
                default: $auto_load = false;
            }

        wp_localize_script( 'gapplayer', 'gapParams', array(
            'autoLoad' => $auto_load ? 'yes' : 'no',
            'preLoad' => wp_is_mobile() ? 'yes' : 'no',
            'effect' => get_option( GAP_EFFECT_OPTION_NAME ) ? 'yes' : 'no',
            'hover' => get_option( GAP_HOVER_OPTION_NAME ) && ! wp_is_mobile() ? 'yes' : 'no',
            'metadata' => get_option( GAP_METADATA_OPTION_NAME ) ? 'yes' : 'no'
        ) );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'gapplayer' );
        wp_enqueue_script( 'imagesloaded' );
        wp_enqueue_script( 'gapplayer' );
    }



    /**
     * Find all image tags in the post and send it to process
     */
    public function replace_gifs( $content ) {
        return preg_replace_callback( '/<img[^>]+>/i', array( $this, 'process_img_tag' ), $content );
    }

    /**
     * Send img src to process
     */
    public function process_img_tag( $img_tag ) {
        // If nogap class exists do nothing
        if ( preg_match( '/class=".*nogap.*"/i', $img_tag[0] ) || preg_match( '/class=\'.*nogap.*\'/i', $img_tag[0] ) )
            return $img_tag[0];

        // Update src property
        $patterns = array( '/(src=")([^"]+)(")/i', '/(src=\')([^\']+)(\')/i' );
        $new_img_tag = preg_replace_callback( $patterns, array( $this, 'update_src' ), $img_tag[0] );
        if ( $img_tag[0] == $new_img_tag ) // Not supported gif, do nothing
            return $img_tag[0];
        return $new_img_tag;
    }

    /**
     * Return updatet src and data-gif attribute for img tag, or just src if not processable
     * @param array gif src array
     */
    public function update_src( $src ) {
        // Test only gif
        $gif_src = $this->get_full_size_url( $src[2] );
        $new_gif_src = $this->get_updated_src( $gif_src );
        if ( $gif_src != $new_gif_src )
            return $src[1] . $new_gif_src . $src[3] . ' data-gif=' . $src[3] . $gif_src . $src[3];
        return $src[0]; // nothing changed, return original
    }



    /**
     * Find all image in post metadata and send it to process
     */
    public function replace_metadata_gifs( $metadata, $object_id, $meta_key ) {
        if ( get_the_ID() == $object_id ) {
            global $wpdb;
            $value = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $object_id AND meta_key = '$meta_key' LIMIT 1" );
            if ( ! is_null( $value ) ) {
                $new_gif_src = $this->get_updated_src( $value );
                if ( $value != $new_gif_src )
                    return $new_gif_src;
            }
        }
    }



    /**
     * This function generate preview image, if necessary
     * @param $src string gif filename
     */
    public function get_updated_src( $src ) {
        if ( substr( strtolower( $src ), -4) == '.gif' ) {
            $new_src = $this->get_preview_url( $src );
            if ( ! $new_src )
                $new_src = $this->generate_preview( $src );
            if ( $new_src )
                return $new_src;
        }
        return $src;
    }

    /**
     * Try to find unresized version, wordpress resize kill animation
     */
    public function get_full_size_url( $src ) {
        if ( preg_match( '/(.*)-\d+x\d+(\.gif)/i', $src, $matches ) )
            if ( file_exists( $this->get_blog_img_dir( $matches[1] . $matches[2], true ) .
                              $this->mb_basename( $matches[1] . $matches[2] ) ) )
                return $matches[1] . $matches[2];
        return $src;
    }

    public function get_preview_url( $original_url ) {
        $path = $this->get_blog_img_dir( $original_url, true );
        $preview_filename = $this->get_preview_filename( $original_url );

        if ( file_exists( $path . $preview_filename ) )
            return $this->get_blog_img_dir( $original_url, false ) . $preview_filename;
        return false;
    }

    public function is_local_image( $img_url ) {
        return strpos( $img_url, get_site_url() ) === 0;
    }

    public function get_blog_img_dir( $img_url, $is_path = true ) {
        if ( $this->is_local_image( $img_url ) ) {
            $site_url = get_site_url();
            $url = substr( $img_url, strlen( $site_url ) );
            $pre = $is_path ? realpath( ABSPATH ) : $site_url;
            return pathinfo( $pre . $url, PATHINFO_DIRNAME ) . '/';
        }
        $dir = wp_upload_dir( get_the_date( 'Y/m' ) );
        return $dir[ $is_path ? 'path' : 'url' ] . '/';
    }

    public function get_preview_filename( $img_src ) {
        return substr( $this->mb_basename( $img_src ), 0, -4 ) . $this->preview_suffix . '.jpg';
    }

    public function is_animation( $filename ) {
        if ( ! ( $fh = @fopen( $filename, 'rb' ) ) )
            return false;
        $count = 0;
        while ( ! feof( $fh ) && $count < 2 ) {
            $chunk = fread( $fh, 1024 * 100 );
            $count += preg_match_all( '#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches );
        }
        fclose( $fh );
        return $count > 1;
    }

    public function generate_preview( $img_url ) {
        if ( ! $this->is_animation( $img_url ) )
            return false;

        $img_path = $this->get_blog_img_dir( $img_url, true );
        if ( $this->is_local_image( $img_url ) )
            $image = imagecreatefromgif( $img_path . $this->mb_basename( $img_url ) );
        else
            $image = imagecreatefromgif( $img_url ); //FIXME: curl?
        if ( ! $image )
            return false;

        $w = imagesx( $image );
        $h = imagesy( $image );
        $cut = imagecreatetruecolor( $w, $h );
        imagecopy( $cut, $image, 0, 0, 0, 0, $w, $h );
        $preview_file = $this->get_preview_filename( $img_url );
        $res = imagejpeg( $cut, $img_path . $preview_file, 80 );
        if ( ! $res )
            return false;
        return $this->get_blog_img_dir( $img_url, false ) . $preview_file;
    }

    public function mb_basename( $filepath, $suffix = null ) {
        $splited = preg_split ( '/\//', rtrim ( $filepath, '/ ' ) );
        return substr ( basename ( 'X' . $splited [count ( $splited ) - 1], $suffix ), 1 );
    }
}
?>