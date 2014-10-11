=== Plugin Name ===
Contributors: subztep
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=64V8Y63QZLTTS
Tags: gif, images
Tested up to: 4.0
Stable tag: 1.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace GIF animations to a static preview image

== Description ==

This plugin finds every GIF animation in your posts and replace it to a preview image. Doesn't matter the GIF embedded from another website or use WordPress's static thumbnail. It automatically generates the preview image from the first frame of the animation and save it to the post's media directory with `-gap.jpg` suffix

Posts show the pregenareted small size jpeg file first, your visitors don't need to wait for download the huge animation. Play animation after they click on the image or after your whole page has downloaded. It depends on your setting:

* You are able to stop animations everywhere
* Or start animation automatically only inside a post
* Or start the moves everywhere by default, use this plugin for lazyload

There are additional settings:

* Pretty smooth effect between your preview and animation
* Start animations with your mouse
* Work with metadata and preview

Don't worry, delete this plugin will remove all settings from your database. Generated preview images gonna stay there, you can use them everywhere until you delete them manually

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

Animation loads after clicking at preview on mobile browsers but loads automatically on desktops.

= Does it delete the generated preview image after delete the original post or original image? =

No. It can't be sure you use the preview image somewhere else. If you don't need preview anymore you need to delete it by yourself from the media directory.

= Does it add any watermark on the preview image? =

No, it only generated clean image with separated play button on the top.

= Does this plugin modify anything in my database? =

No, everything is happening on the fly. Once you deactivate, you get back your original posts (fyi it stores your settings in wp_options table, as usual).

= Can I use it with Infinite scroll plugin? =

Yes, you need to add `gapStart();` to the callback area on admin.

= Can I use it with Aruna template? =
Yes, open `wp-content/themes/Aruna/functions.php` with a text editor and add `$teo_nolazy = true;` line after `<?php` (second line) for turn off template's lazy load function. Go to plugin's admin and turn on metadata overwrite.

== Screenshots ==

1. This is the admin interface at the moment, you will find something similar
2. Blog preview but your content

== Changelog ==

= 1.10 =
* Handle gifs in metadata
* Handle post thumbnail html (thanks for Akis)

= 1.9 =
* Smooth animation switch
* Start animation with mouse event
* Install plugin create default settings
* Delete plugin remove every settings from database
* Retina ready banner and some text by @Starlin_

= 1.8.2 =
* Support infinite scroll with callback

= 1.8.1 =
* Better GIF animation detection

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
