<?php
class Gif_Pic {

    public $preview_suffix = '-gap'; // Preview image filename suffix
    public $watermark;
    public $filename;

    public function __construct( $filename = null ) {
        $this->watermark = __DIR__ . '/play.png';
        if ( ! is_null( $filename ) ) {
            $this->filename = $filename;
        }
    }

    public function getPreview() {
        $img_path = $this->getPathFromUrl( $this->filename );
        $preview_filename = pathinfo( $img_path, PATHINFO_FILENAME ) . $this->preview_suffix . '.jpg';
        if ( file_exists( dirname( $img_path ) . '/' . $preview_filename ) ) {
            return pathinfo( $this->filename, PATHINFO_DIRNAME ) . '/' . $preview_filename;
        } else {
            return $this->generatePreview();
        }
    }

    public function isAnimation() {
        if ( ! ( $fh = @fopen( $this->filename, 'rb' ) ) ) {
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

    public function generatePreview() {
        $img_path = $this->getPathFromUrl( $this->filename );
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
        return pathinfo( $this->filename, PATHINFO_DIRNAME ) . '/' . $preview_file;
    }

    private function getPathFromUrl( $url ) {
        $site_url = get_site_url();
        if ( strpos( $this->filename, $site_url ) === 0 ) {
            $url = substr( $url, strlen( $site_url ) );
            if ( substr( $url, 0, 1 ) != '/' ) {
                $url = '/' . $url;
            }
            return realpath( ABSPATH ) . $url;
        }
        return false;
    }
}
?>