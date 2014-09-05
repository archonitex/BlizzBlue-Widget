=== BlizzBlue-Widget ===
Contributors: senatorsfc
Tags: Starcraft, Blue posts, forum posts, Diablo, Warcraft, Blizzard
Requires at least: 4.0
Tested up to: 3.5.1
Stable tag: 4.0


Adds a widget to your wordpress theme that gets the latest blue posts for Starcraft 2, Diablo 3 and WoW. 

== Description ==

I decided to share this WordPress widget that I developed for my personal use on my clan's website.
It gets the latest blue posts (Maximum of 15) for each game available on the Blizzard website, so WoW, Starcraft II and Diablo III.

You can use the widget multiple times if you want to show all of the games. As of right now the the plugin has the option of showing the date of the post and the forum it originated from. Any feedback is welcome!

You can see how it works on my own website (wrclan.com) the widgets are on the right side of the home-page.

Any questions or feedback please email senatorsfc@gmail.com
== Frequently Asked Questions ==

= I've updated the plugin and now I get an error about Offset not contained? =

The update might have included some changes which require you to re-save the widget. Go to your Widget Section, expand your widget, set all the settings you need and click save.

== Changelog ==

= 3.0 =
* Updated to properly parse new Blizzard tracker
* Removed alternate method of pulling tracker which pointed to my own website

= 2.1 =
* Bug Fix : When Battle.net website is down the plugin will access a cached copy of the website stored on my private server. If the plugin cannot access either of the sources, a message will indicate that no blue posts are available at this time.

= 2.0 =
* Widget now queries Battle.net directly for always up-to-date feeds. Requires CURL. In case you do not have CURL, old method of get_file_url is still supported.

= 1.1 =
* Added Support for Europe Server. Widget now has an option of showing either Europe or U.S. posts.


= 1.0 =
* Official Release.


== Upgrade Notice ==

= 4.0 =
* Updated to properly parse new Blizzard tracker
* Removed alternate method of pulling tracker which pointed to my own website

= 3.0 =
* Bug fixes, added other languages

= 2.1 =
* Bug fix for when battle.net website is down. Will not show the strpos error message.

= 2.0 =
* Widget now queries Battle.net directly for always up-to-date feeds.

= 1.1 =
* Added Support for Europe Server. Widget now has an option of showing either Europe or U.S. posts.

== Installation ==

1. Upload the plugin to the '/wp-content/plugins/' directory (or install through Wordpress interface)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to your Widgets section and you should now see the new widget available to you. Drag and drop it to your sidebar

