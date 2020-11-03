# WP Term Images

Images for categories, tags, and other taxonomy terms

WP Term Images allows users to assign images to any visible category, tag, or taxonomy term using the media library, providing a customized look for their taxonomy terms.

# Installation

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

# FAQ

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
### Available filters

Throughout the plugin filters are reffered to as ```"filter_name_{$this->meta_key}_definition"``` where the $this->meta is in this plugin "image", creating the filter name of "filter_name_image_definition". This is setup this way so that the WP_Term_Meta_UI can be reused with all meta data adaptions. 

| filter name                                        | Type of data | What it does                                                      |
|----------------------------------------------------|--------------|-------------------------------------------------------------------|
| "wp_term_{ $this -> meta_key }_allowed_taxonomies" | array        | Array of all taxonomies that will have this plugins UI applied to it |
| "wp_term_{ $this -> meta_key }_get_taxonomies"     | array        | Allow filters of affected taxonomies                              |
| "wp_fancy_term_{ $this -> meta_key }"              | bool         | A simple filter to allow for UI variations                        |

### Where can I get support?

* Basic: https://wordpress.org/support/plugin/wp-term-images/
* Priority: https://chat.flox.io/support/channels/wp-term-images/

### Can I contribute?

Yes, please! The number of users needing more robust taxonomy visuals is growing fast. Having an easy-to-use UI and powerful set of functions is critical to managing complex WordPress installations. If this is your thing, please help us out!
