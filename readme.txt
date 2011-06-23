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

A shortcode generator has been added to all public `$post_type` edit screens. You can modify the options in the included metabox and it will output the correct shortcode arguments.

Alternativly you can modify the shortcode yourself, where `1` is true and `0` is false:
`[youtube-white-label id="" height="" width="" branding="1" autohide="1" autoplay="0" controls="0" hd="1" rel="0" showinfo="0" autosize="1" border="0" cc="0" disablekb="1" fullscreen="1" /]`

For question please visit my blog @ [http://austinpassy.com](http://austinpassy.com/wordpress-plugins/youtube-white-label-shortcode/)

Additional controls listed [on Google code](http://code.google.com/apis/youtube/player_parameters.html?playerVersion=HTML5) *for the terminology of each shortcode argument*.

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

= Version 0.2.1.1 (6/23/11) =

* Add `remove_youtube_white_label_dashboard` to your options and set to `1` if you'd like the dashboard widget removed.

= Version 0.2.1 (6/21/11) =

* Added `autosize` class to iframe allowing more than one video to be shown with each able to automatically ajust size or not.

= Version 0.2 (6/20/11) =

* Updated `modestbranding` to be the first parameter (so the YouTube logo stays hidden).
* Aded parameter `&title=` when showinfo is active and branding is active to keep the YouTube logo hidden.

= Version 0.1.9 (6/16/11) =

* Spelling error in the readme shortcode.
* All new argument options shown in readme.
* Testing out auto `height` and `width` options if those argument are left empty. Will use `$content_width`.
* [bug] Color codes.

= Version 0.1.8 (6/13/11) =

* Updated for the new [HD preview image](http://youtube-global.blogspot.com/2011/06/next-step-in-embedded-videos-hd-preview.html).
* A few new shortcode values: `border`, `cc_load_policy`, `color1`, `color2`, `disablekb`, `fs`, `branding`.

= Version 0.1.7 (6/1/11) =

* For some reason YouTube&trade; started auto-playing videos whether or not the `autoplay` parameter was set to `0` (false). But if not there it doesn't auto-play. So this update fixes that untill they fix it or change it again.

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