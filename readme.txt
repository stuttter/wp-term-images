=== WP Term Images ===
Contributors: johnjamesjacoby, stuttter
Tags: taxonomy, term, meta, metadata, image, images
Requires at least: 4.4
Tested up to: 4.6
Stable tag: 0.3.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9Q4F4EL5YJ62J

== Description ==

Images for categories, tags, and other taxonomy terms

WP Term Images allows users to assign images to any visible category, tag, or taxonomy term using the media library, providing a customized look for their taxonomies.

= Dependencies =

Not since WordPress 4.4.

Install the [WP Term Meta](https://wordpress.org/plugins/wp-term-meta/ "Metadata, for taxonomy terms.") plugin if you're on an earlier version.

= Also checkout =

* [WP Chosen](https://wordpress.org/plugins/wp-chosen/ "Make long, unwieldy select boxes much more user-friendly.")
* [WP Pretty Filters](https://wordpress.org/plugins/wp-pretty-filters/ "Makes post filters better match what's already in Media & Attachments.")
* [WP Event Calendar](https://wordpress.org/plugins/wp-event-calendar/ "The best way to manage events in WordPress.")
* [WP Media Categories](https://wordpress.org/plugins/wp-media-categories/ "Add categories to media & attachments.")
* [WP Term Order](https://wordpress.org/plugins/wp-term-order/ "Sort taxonomy terms, your way.")
* [WP Term Authors](https://wordpress.org/plugins/wp-term-authors/ "Authors for categories, tags, and other taxonomy terms.")
* [WP Term Colors](https://wordpress.org/plugins/wp-term-colors/ "Pretty colors for categories, tags, and other taxonomy terms.")
* [WP Term Icons](https://wordpress.org/plugins/wp-term-icons/ "Pretty icons for categories, tags, and other taxonomy terms.")
* [WP Term Visibility](https://wordpress.org/plugins/wp-term-visibility/ "Visibilities for categories, tags, and other taxonomy terms.")
* [WP User Activity](https://wordpress.org/plugins/wp-user-activity/ "The best way to log activity in WordPress.")
* [WP User Avatars](https://wordpress.org/plugins/wp-user-avatars/ "Allow users to upload avatars or choose them from your media library.")
* [WP User Groups](https://wordpress.org/plugins/wp-user-groups/ "Group users together with taxonomies & terms.")
* [WP User Profiles](https://wordpress.org/plugins/wp-user-profiles/ "A sophisticated way to edit users in WordPress.")

== Screenshots ==

1. Category Images

== Installation ==

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

== Frequently Asked Questions ==

= Does this plugin depend on any others? =

Not since WordPress 4.4.

Install the [WP Term Meta](https://wordpress.org/plugins/wp-term-meta/ "Metadata, for taxonomy terms.") plugin if you're on an earlier version.

= Does this create new database tables? =

No. There are no new database tables with this plugin.

= Does this modify existing database tables? =

No. All of WordPress's core database tables remain untouched.

= How do I get the image for a term? =

With WordPress's `get_term_meta()` function

`
// image id is stored as term meta
$image_id = get_term_meta( 7, 'image', true );

// image data stored in array, second argument is which image size to retrieve
$image_data = wp_get_attachment_image_src( $image_id, 'full' );

// image url is the first item in the array (aka 0)
$image = $image_data[0];

if ( ! empty( $image ) ) {
    echo '<img src="' . esc_url( $image ) . '" />';
}
`

= Where can I get support? =

The WordPress support forums: https://wordpress.org/support/plugin/wp-term-images/

= Where can I find documentation? =

http://github.com/stuttter/wp-term-images/

== Changelog ==

= [0.3.1] - 2016-07-13 =
* Fix regular edits

= [0.3.0] - 2016-05-27 =
* Fix quick-edits
* Update WP Term Meta UI dependency to 0.1.9

= [0.2.0] - 2016-01-07 =
* Fix new-term form action hi-jacking
* Update WP Term Meta UI dependency to 0.1.4

= [0.1.0] - 2015-11-09 =
* Initial release
