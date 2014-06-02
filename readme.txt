=== Plugin Name ===
Contributors: subztep
Tags: gif
Tested up to: 3.9.1
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace GIF animations to a single preview image.

== Description ==

Replace GIF animations to a single preview image. Click on the image to start animate, click on again for stop it.

In this early stage of the plugin there is no additional settings. Good for prevent downloading unwanted large files.

What this plugin does is:

*   Find self hosted gif animations
*   Generate jpeg image to the same folder
*   Update image tag attributes in the post

On GitHub:
https://github.com/SubZtep/gif-animation-preview

== Installation ==

1. Upload plugin files to the `/wp-content/plugins/gif-animation-preview/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.3 =
* Decrase PHP version requirement from 5.4 to 5.0
* Uses gifplayer and imagesLoaded libraries

= 1.2 =
* Add PHP version and GD check

= 1.1 =
* Fix unicode issues

= 1.0.0 =
* Plugin's creation
