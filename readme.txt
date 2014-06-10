=== Plugin Name ===
Contributors: subztep
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=64V8Y63QZLTTS
Tags: gif, images
Tested up to: 3.9.1
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace GIF animations to a single preview image

== Description ==

Replace GIF animations to a single preview image. Click on the image to start animate, click on again for stop it

In this early stage of the plugin there is no additional settings. Good for prevent downloading unwanted large files

What this plugin does is:

*   Find gif animations in posts
*   Generate jpeg image
*   Update image tag attribute

[Live Demo](http://demo.land/wordpress/?p=4)

On GitHub:
https://github.com/SubZtep/gif-animation-preview

== Installation ==

1. Upload plugin files to the `/wp-content/plugins/gif-animation-preview/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Preview images generated on the fly, first view of the posts might be slow

== Changelog ==

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
