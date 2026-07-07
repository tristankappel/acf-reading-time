=== ACF Reading Time ===
Contributors: tristankappel
Tags: reading time, acf, advanced custom fields, shortcode, estimated reading time
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display the estimated reading time of a post via a shortcode. Automatically counts the post content and all ACF (Pro) fields.

== Description ==

ACF Reading Time adds a simple `[reading_time]` shortcode that outputs the estimated reading time of the current post.

Unlike most reading time plugins, it counts not only the post content but also **every Advanced Custom Fields value** attached to the post, including repeaters, groups, flexible content and nested sub fields. No field selection is required in the backend, all text fields are counted automatically.

The only settings are:

* Prefix shown before the time (e.g. "Reading time:")
* Postfix shown after the time (e.g. "min read")
* Words per minute used for the calculation (default 200)

== Installation ==

1. Upload the `acf-reading-time` folder to `/wp-content/plugins/`.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to Settings > ACF Reading Time to configure prefix, postfix and words per minute.
4. Add the shortcode `[reading_time]` inside a post or template.

== Frequently Asked Questions ==

= Do I need ACF Pro? =

No. The plugin works on any post using the standard post content. If ACF (free or Pro) is active, all custom field values are counted as well.

= Can I use it in a template file? =

Yes. Use `echo do_shortcode( '[reading_time]' );` or the shortcode directly in the editor.

= Can I override settings per shortcode? =

Yes. The shortcode accepts `post_id`, `prefix`, `postfix` and `wpm` attributes, e.g. `[reading_time wpm="250" postfix="minutes"]`.

== Changelog ==

= 1.0.0 =
* Initial release.
