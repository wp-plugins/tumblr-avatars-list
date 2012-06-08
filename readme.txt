=== Tumblr Avatars List ===
Contributors: OrignalEXE
Donate link: http://originalexe.com/donate
Tags: tumblr, tumblr avatars, avatar, tumblr list
Requires at least: 3.0.
Tested up to: 3.3.2
Stable tag: 1.0
License: GPLv3

This plugin let's your visitors to submit their tumblr. usernames, whose avatars are then displayed using shortcode.

== Description ==

Plugin add's widget that you can use to let your visitors enter their tumblr usernames into the form. Usernames are then stored in the database, together with the user's avatars. 
You can choose the size of the images you store/display. Plugin also supports shortcode to display the list of avatars from the database.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Set-up the options in the Settings -> TAL Options menu

== Frequently Asked Questions ==

None.

== Screenshots ==

1. This is how frontend looks.
2. This is how the backend looks.

== Changelog ==

= 1.0 =
* First Release
== Upgrade Notice ==

None

== How to use shortcodes ==

TAL plugin supports shortcodes. You can use shortcodes to display list of avatars from the database.

[tal][/tal]

Shortcode supports two parameters, size and limit.
Limit parameter suggests how many latest avatars are displayed from teh database.
Size parameter suggest in what size should avatars be displayed. 

Example: [tal limit="18" size="44"][/tal] will generate 18 thumbnails 44x44px big. 
Note that if in the TAL options you choose smaller size to store in the database, avatars will stretch and look ugly. 
Always choose biggest size you will use anywhere on the website.