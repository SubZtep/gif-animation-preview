=== Plugin Name ===
Contributors: subztep
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=64V8Y63QZLTTS
Tags: gif, images
Tested up to: 3.9.1
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace GIF animations to a static preview image

== Description ==

This plugin finds every GIF animation in your posts and replace it to a preview image. Doesn't matter the GIF embedded from another website or use WordPress's static thumbnail. It automatically generates the preview image from the first frame of the animation and save it to the post's media directory with `-gap.jpg` suffix

Posts show the pregenareted small size jpeg file first, your visitors don't need to wait for download the huge animation. Play animation after they click on the image or after your whole page has downloaded. It depends on your setting

[Live Demo](http://demo.land/wordpress/?p=4)

On GitHub:
https://github.com/SubZtep/gif-animation-preview

== Installation ==

1. Upload plugin files to the `/wp-content/plugins/gif-animation-preview/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Find Settings > GIF Animation Preview on your admin interface

Preview images generated on the fly, first view of the posts might be slow

== Frequently Asked Questions ==

= Does plugin load gif animation directly with preview? =

Animation loads after clicking at preview on mobile browsers but loads automatically on desktops

= Does it delete the generated preview image after delete the original post or original image? =

No. It can't be sure you use the preview image somewhere else. If you don't need preview anymore you need to delete it by yourself from the media directory

= Does it add any watermark on the preview image? =

No, it only generated clean image with separated play button on the top

= Does this plugin modify anything in my database? =

No, everything is happening on the fly. Once you deactivate, you get back your original posts (fyi it stores your settings in wp_options table, as usual)

== Changelog ==

= 1.8 =
* Able to prevent automatically start animations only on mobile browsers

= 1.7 =
* Preload images on non-mobile browsers
* Faster load (less js, includes at the bottom)
* Upgrade imagesLoaded plugin to v3.1.8

= 1.6.1 =
* Better PHP 5.0 compatibility

= 1.6 =
* Admin interface for preview method
* Started own fork of GifPlayer JavaScript

= 1.5 =
* Handle Wordpress's static preview

= 1.4 =
* Allow images from external source
* Only test image when preview doesn't exists
* Unicode fixes in filenames
* Decrase PHP version requirement from 5.2 to 5.0

= 1.3 =
* Uses gifplayer and imagesLoaded libraries
* Decrase PHP version requirement from 5.4 to 5.2

= 1.2 =
* Add PHP version and GD check

= 1.1 =
* Fix unicode issues

= 1.0.0 =
* Plugin's creation
