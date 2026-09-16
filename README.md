# WP Term Images

Images for categories, tags, and other taxonomy terms

WP Term Images allows users to assign images to any visible category, tag, or taxonomy term using the media library, providing a customized look for their taxonomy terms.

## Installation

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

## FAQ

### Does this plugin depend on any others?

Not since WordPress 4.4.

### Does this create new database tables?

No. There are no new database tables with this plugin.

### Does this modify existing database tables?

No. All of WordPress's core database tables remain untouched.

### How do I get the image for a term?

With WordPress's `get_term_meta()` function

```
// image id is stored as term meta
$image_id = get_term_meta( 7, 'image', true );

// image data stored in array, second argument is which image size to retrieve
$image_data = wp_get_attachment_image_src( $image_id, 'full' );

// image url is the first item in the array (aka 0)
$image = $image_data[0];

if ( ! empty( $image ) ) {
	echo '<img src="' . esc_url( $image ) . '" />';
}
```

The attachment ID is also exposed as the term's `image` metadata in the
WordPress REST API. To keep it out of REST responses, return `false` from the
`wp_term_image_show_in_rest` filter.

### Can I limit the taxonomies that use term images?

Yes. Filter the visible taxonomies after discovery and return only the ones
that should use the image interface:

```
add_filter( 'wp_term_image_allowed_taxonomies', function( $taxonomies ) {
	return array( 'category', 'post_tag' );
} );
```

### Where can I get support?

* Basic: https://wordpress.org/support/plugin/wp-term-images/

### Can I contribute?

Yes. Start with [CONTRIBUTING.md](CONTRIBUTING.md), and keep each pull request
focused with regression coverage for behavior changes.
