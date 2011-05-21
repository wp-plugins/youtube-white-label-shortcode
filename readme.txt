=== YouTube White Label Shortcode ===
Contributors: austyfrosty
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XQRHECLPQ46TE
Tags: YouTube, shortcode, White Label, YouTube White Label, embed
Requires at least: 3.0
Tested up to: 3.2
Stable tag: trunk

A simple shortcode to embed white label YouTube videos using the latest iframe HTML5 embed.

== Description ==

This plugin adds the shortcode `[youtube-white-label]` and allows you to input any YouTube&trade; video (that allows embeding outsite the YouTube&trade; domain) with control over the logo, player, autostart and more.

A shortcode generator has been added to all public $post_type add and edit screens. You can modify the options and it will output the correct shortcode arguments.

Alternativly you can modify the shortcode yourself, where `1` is true and `0` is false:
`[youtube-white-label id="" height="350" width="600" autohide="1" autoplay="0" controls="0" hd="1" rel="0" showinfo="0" autoaize="1" /]`

For question please visit my blog @ [http://austinpassy.com](http://austinpassy.com/wordpress-plugins/youtube-white-label-shortcode/)

ALERT: `[youtube-embed]` is going to be replaced with `[youtube-white-label]`.

== Installation ==

Follow the steps below to install the plugin.

1. Upload the `youtube-white-label` directory to the /wp-content/plugins/ directory. OR click add new plugin in your WordPress admin.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit your post/page edit screen to generate the shortcode.

== Frequently Asked Questions ==

= Nothing yet =

== Screenshots ==

1. The shortcode generator metabox.

2. A YouTube&trade; video without controls.

== Changelog ==

= Version 0.1.6 (5/21/11) =

* Fixed jQuery output, again. This time fixed what I overwrote from `0.1.1` update.

= Version 0.1.5 (5/18/11) =

* Fixed admin jQuery to work on all `post_types`.

= Version 0.1.4 (5/17/11) =

* Fixed jQuery bug.
* Added `.pot` file for translation.

= Version 0.1.3 (5/17/11) =

* Security fixes.
* Added front end jQuery file option `autosize` to shortcode which will &ldquo;autosize&rdquo; the video to the content width.

= Version 0.1.2 (5/17/11) =

* Some code cleanup.

= Version 0.1.1 (5/17/11) =

* Updated `.attr()` to `.prop()` for jQuery 1.6.x.
* Added jQuery version check to fallback on `.attr()` if running old jQuery.

= Version 0.1 (5/16/11) =

* Initial release.

== Upgrade Notice ==

= 0.1.1 =

* Fixed jQuery `.prop()` issues with older jQuery versions (added fallback).

= 0.1.4 =

* Security fixes and new front end jQuery auto-sizer option. ALERT: `[youtube-embed]` is going to be replaced with `[youtube-white-label]`. Fixed jQUery bug.