=== YouTube White Label Shortcode ===
Contributors: austyfrosty
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XQRHECLPQ46TE
Tags: YouTube, shortcode, White Label, YouTube White Label, embed
Requires at least: 3.0
Tested up to: 3.2
Stable tag: trunk

A simple shortcode to embed white label YouTube videos using the latest iframe HTML5 embed.

== Description ==

This plugin adds the shortcode `[youtube-embed]` and allows you to input any YouTube&trade; video (that allows embeding outsite the YouTube&trade; domain) with control over the logo, player, autostart and more.

A shortcode generator has been added to all public $post_type add and edit screens. You can modify the options and it will output the correct shortcode arguments.

Alternativly you can modify the shortcode yourself, where `1` is true and `0` is false:
`[youtube-embed id="" height="350" width="600" autohide="1" autoplay="0" controls="0" hd="1" rel="0" showinfo="0" /]`

For question please visit my blog @ [http://austinpassy.com](http://austinpassy.com/wordpress-plugins/youtube-white-label-shortcode/)

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

= Version 0.1.1 (5/16/11) =

* Updated `.attr()` to `.prop()` for jQuery 1.6.x.
* Added jQuery version check to fallback on `.attr()` if running old jQuery.

= Version 0.1 (5/16/11) =

* Initial release.

== Upgrade Notice ==

= 0.1.1 =

* Fixed jQuery `.prop()` issues with older jQuery versions (added fallback).