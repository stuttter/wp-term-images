=== WP Term Images ===
Contributors:      johnjamesjacoby, stuttter
Tags:              taxonomy, term, metadata, image, images
Requires PHP:      7.4
Requires at least: 6.4
Tested up to:      7.1
Stable tag:        2.1.3
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html
Donate link:       https://ko-fi.com/jjj

Assign images to categories, tags, and other taxonomy terms with the WordPress media library.

== Description ==

Images for categories, tags, and other taxonomy terms

WP Term Images allows users to assign images to any visible category, tag, or taxonomy term using the media library, providing a customized look for their taxonomies.

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

No. Not since WordPress 4.4.

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

* Basic: https://wordpress.org/support/plugin/wp-term-images/

= Where can I find documentation? =

https://github.com/stuttter/wp-term-images/wiki

== Changelog ==

= [2.1.3] - 2026-09-16 =
* Require WordPress 6.4 or newer

= [2.1.2] - 2026-09-14 =
* Republish the verified package through corrected WordPress.org distribution automation

= [2.1.1] - 2026-09-14 =
* Refresh release packaging and distribution metadata

= [2.1.0] - 2026-09-13 =
* Preserve existing images when programmatic term updates omit the image field
* Avoid intercepting unrelated term-meta sorting queries
* Require PHP 7.4 and add automated regression coverage

= [2.0.0] - 2019-05-30 =
* Update base class

= [1.0.0] - 2017-01-16 =
* Stability!
* Fix text domains
* Simplify some JavaScript
* Handle more JavaScript edge-cases

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
