<?php
class GifPic {

    public $preview_suffix = '-gifpreview'; // Preview image filename suffix
    public $watermark;
    public $filename;

    public function __construct($filename = null) {
        if (! is_null($filename)) {
            $this->filename = $filename;
        }
    }

    public function getPreview() {
        $img_path = $this->getPathFromUrl($this->filename);
        $preview_filename = pathinfo($img_path, PATHINFO_FILENAME) . $this->preview_suffix . '.jpg';
        if (file_exists(dirname($img_path) .'/'. $preview_filename)) {
            return pathinfo($this->filename, PATHINFO_DIRNAME) .'/'. $preview_filename;
        } else {
            return $this->generatePreview();
        }
    }

    public function isAnimation() {
        if(!($fh = @fopen($this->filename, 'rb')))
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

    public function generatePreview() {
        $img_path = $this->getPathFromUrl($this->filename);

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
        return pathinfo($this->filename, PATHINFO_DIRNAME) .'/'. $preview_filename;
    }

    private function getPathFromUrl($url) {
        $site_url = get_site_url();
        if (strpos($this->filename, $site_url) === 0) {
            $url = substr($url, strlen($site_url));
            if (substr($url, 0, 1) != '/') {
                $url = '/'.$url;
            }
            return realpath(ABSPATH) . $url;
        }
        return false;
    }
}