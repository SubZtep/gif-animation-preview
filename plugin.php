<?php
/*
Plugin Name: GIF Animation Preview
Description: Replace GIF animations to a single preview image. Click on the image to start animate.
Version: 1.0.0
Author: Andras Serfozo
Author URI: http://twitter.com/SubZtep
License: GPLv2 or later
*/

class GifAnimationPreview {

    public $preview_suffix = '-gifpreview'; // Preview image filename suffix
    public $watermark;

    private static $plugin;
    static function load() {
        $class = __CLASS__; 
        return ( self::$plugin ? self::$plugin : ( self::$plugin = new $class() ) );
    }

    private function __construct() {
        $this->watermark = __DIR__.'/play.png';
        add_action('the_content', array($this, 'replace_gifs'));
    }

    public function replace_gifs($content) {
        //die(mb_detect_encoding($content));
        $updated = false;
        $dom = new DOMDocument('1.0', 'utf-8');
        @$dom->loadHTML($content);
        $imgs = $dom->getElementsByTagName('img');
        for ($i = 0; $i < $imgs->length; $i++) {
            $img = $imgs->item($i);
            $src = $img->getAttribute('src');
            if (substr(strtolower($src), -4) == '.gif') {
                $new_src = $this->getPreview($src);
                if ($new_src !== false) {
                    $img->setAttribute('src', $new_src);
                    $img->setAttribute('onclick', "if(this.src.indexOf('{$this->preview_suffix}')!=-1){this.src='';this.src='$src';}else this.src='$new_src';return false;");
                    $updated = true;
                }
            }
        }
        if ($updated) {
            $html = $dom->saveHTML();
            $html = substr($html, strpos($html, '<body>') + 6);
            $html = substr($html, 0, strpos($html, '</body>'));
            return $html;
        }
        return $content;
    }

    private function getPreview($img_url) {
        $img_path = $this->getPathFromUrl($img_url);
        $preview_filename = pathinfo($img_path, PATHINFO_FILENAME) . $this->preview_suffix . '.jpg';
        if (file_exists(dirname($img_path) .'/'. $preview_filename)) {
            return pathinfo($img_url, PATHINFO_DIRNAME) .'/'. $preview_filename;
        } else {
            return $this->generatePreview($img_url);
        }
    }

    private function generatePreview($img_url) {
        $img_path = $this->getPathFromUrl($img_url);
        if (! $this->is_ani($img_path)) {
            return false;
        }

        $image = @imagecreatefromgif($img_path);
        if ($image === false) {
            return false;
        }

        $water_size = getimagesize($this->watermark);
        switch ($water_size['mime']) {
            case 'image/png': $watermark = @imagecreatefrompng($this->watermark); break;
            case 'image/gif': $watermark = @imagecreatefromgif($this->watermark); break;
            case 'image/jpg': 
            case 'image/jpeg': $watermark = @imagecreatefromjpeg($this->watermark); break;
            default: return false;
        }

        $w = imagesx($image);
        $h = imagesy($image);
        $ww = imagesx($watermark);
        $wh = imagesy($watermark);
        $cut = imagecreatetruecolor($w, $h);
        imagecopy($cut, $image, 0, 0, 0, 0, $w, $h);
        imagecopy($cut, $watermark, (($w/2)-($ww/2)), (($h/2)-($wh/2)), 0, 0, $ww, $wh);

        $preview_filename = pathinfo($img_path, PATHINFO_FILENAME) . $this->preview_suffix . '.jpg';
        $res = imagepng($cut, dirname($img_path) .'/'. $preview_filename);
        imagedestroy($image);
        imagedestroy($watermark);
        imagedestroy($cut);
        if (! $res) {
            return false;
        }
        return pathinfo($img_url, PATHINFO_DIRNAME) .'/'. $preview_filename;
    }

    private function getPathFromUrl($url) {
        $site_url = get_site_url();
        if (strpos($url, $site_url) === 0) {
            $url = substr($url, strlen($site_url));
            if (substr($url, 0, 1) != '/') {
                $url = '/'.$url;
            }
            return realpath(ABSPATH) . $url;
        }
        return false;
    }

    private function is_ani($filename) {
        if(!($fh = @fopen($filename, 'rb')))
            return false;
        $count = 0;
        //an animated gif contains multiple "frames", with each frame having a
        //header made up of:
        // * a static 4-byte sequence (\x00\x21\xF9\x04)
        // * 4 variable bytes
        // * a static 2-byte sequence (\x00\x2C)

        // We read through the file til we reach the end of the file, or we've found
        // at least 2 frame headers
        while(!feof($fh) && $count < 2) {
            $chunk = fread($fh, 1024 * 100); //read 100kb at a time
            $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00\x2C#s', $chunk, $matches);
        }

        fclose($fh);
        return $count > 1;
    }
}

GifAnimationPreview::load();
