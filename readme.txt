=== Remove Wordpress Overhead ===
Contributors: Omnisite
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8ZS4SEBD4EA7W
Tags: wordpress, header, remove widgets, disable widgets, remove, clean, strip, version, disable, rsd link, wp generator, feed link, rss feed, shortlink, next, prev, wp generator, version number, wlwmanifest, emojicons, json api, jquery migrate, jqmigrate, xml rpc, xml-rpc, gutenberg, block, speed, pagespeed
Requires at least: 5.0
Tested up to: 6.4.2
Stable tag: 1.6.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Remove overhead from the <head> HTML, speed up your website and disable widgets you don't use

== Description ==

A standard WP installation contains many links in the head of your HTML (which slow down your site) and has standard widgets you might never use. You can now install this plugin and check the items you want to have removed. The saved options are cached for better performance.

Header items you can remove:
* Remove dashicons CSS from frontend
* Remove RSD / EditURI Link
* Remove WLW Manifest Link
* Remove RSS Feed Links
* Remove Next & Prev Post Links
* Remove Shortlink URL (also from http headers)
* Remove WP Generator Meta
* Remove Version Numbers from Style and Script Links
* Disable WP Emoji / emoticons
* Disable JSON API
* Disable Canonical URL
* Remove WooCommerce Generator Meta
* Remove jQuery Migrate script
* Disable XML-RPC methods that require authentication
* Remove all scripts and styles added by Gutenberg (in case you still use the classic editor)

You can disable the following widgets:
* Archives
* Calendar
* Categories
* Links
* Meta
* Nav Menu
* Pages
* Recent Comments
* Recent Posts
* RSS
* Search
* Tag Cloud
* Text

== Installation ==

Installing "Remove Wordpress Overhead" can be done either by searching for "Remove Wordpress Overhead" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
1. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. screenshot-1: settings screen

== Changelog ==

= 1.6.0 =
* 2024-08-04
* option to remove dashicons css from frontend

= 1.5.6 =
* 2023-12-29
* fix deprecated stuff

= 1.5.5 =
* 2023-08-09
* fix deprecated stuff

= 1.5.4 =
* 2022-10-25
* fix emoji removal
* apply WP coding styles

= 1.5.3 =
* 2022-10-25
* also removed canonical action from wp_head: thanks to Joy Reynolds (https://github.com/joyously)

= 1.5.2 =
* 2022-07-14
* changed tested WP version

= 1.5.1 =
* 2019-12-14
* fix admin load settings script warning

= 1.5.0 =
* 2019-12-14
* remove Gutenberg block scripts and styles

= 1.4.0/1.4.1 =
* 2019-12-11
* contributions by Mathew Callaghan (https://github.com/mathewcallaghan)
* remove jQuery Migrate script
* disable XML-RPC methods that require authentication

= 1.3.1 =
* 2019-12-09
* increased tested WP version

= 1.3.0 =
* 2019-12-09
* contribution by Bill (https://github.com/lefooey) :
* If short links are turned off, this also removes the short link that
* Wordpress puts in the HTTP headers
* Thanks Bill. Sorry for the 2 year delay in merging :-)

= 1.2.0 =
* 2019-12-09
* general code clean up

= 1.1.0 =
* 2016-10-23
* ability to remove canonical url from header
* ability to remove WooCommerce generator tag from header
* add plugin git URL
* add "select all" op settings page
* namespace classes to avoid function name collisions
* add css sliders and checkboxes on settings page
* added info buttons on the settings page

= 1.0.2 =
* 2016-09-23
* Correct author name in php file
* Add donate link in readme
* Better readme text

= 1.0.1 =
* 2016-09-18
* Add license in composer.json

= 1.0.0 =
* 2016-09-18
* Initial release