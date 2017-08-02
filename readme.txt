=== IMDB Info Box===

Contributors: sunnyverma1984
Donate link: http://99webtools.com/blog/contribute/
Tags: imdb, movie, shortcode, cinema, film, 
Requires at least: 2.8
Tested up to: 3.9.1
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display movie information from IMDB in wordpress post.

== Description ==
This plugin helps to add movie information (from IMDB) in wordpress post using shortcode [imdb id="imdbmovieid"].
**IMDB Info Box** is using [omdbapi.com](http://www.omdbapi.com) API which provides information from Imdb.

Read more how this plugin works http://99webtools.com/blog/php-get-movie-information-from-imdb/

= Note =
This plugin is not endorsed by or affiliated with IMDb.com

== Installation ==
Using the Plugin Manager

1. Click Plugins
2. Click Add New
3. Search for `imdb-info-box`
4. Click Install
5. Click Install Now
6. Click Activate Plugin

Manually

1. Upload `imdb-info-box` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. IMDB Info box post demo
2. IMDB Info Box settings page


== Frequently Asked Questions ==

= Why cache is necessary? =

Cache is crucial to `IMDB Info Box` plugin. As first imdb searchs are quite time consuming, if you do not want to kill your server but instead want quickest browsing experience, you should use cache.

= How to display full plot on movie? =

To display full plot use `plot` attribute eg
`[imdb id="tt0910970" plot="short"]` for short plot (default)
`[imdb id="tt0910970" plot="full"]` for full plot

= Howto change box theme? =

To change box theme login as admin

1. goto `Settings->IMDB infobox Settings`.
2. Select background and text color
3. Click On `Save Changes`

= How to set cache age? =

To change cache age login as admin

1. goto `Settings->IMDB infobox Settings`.
2. Enter cache age in seconds or -1 for never expire
3. Click On `Save Changes`

== Changelog ==

= 1.2 =
* Replaced all php stort open tags to with long tags
* Remove all readonly attribute from input fields

= 1.1 =
* Moved cache directory to uploads folder
* Using CURL instead of file_get_contents
* Added Border radius for imdb info box

= 1.0 =
* Initial release
