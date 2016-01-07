<?php

/**
 * Plugin Name: WP Term Images
 * Plugin URI:  https://wordpress.org/plugins/wp-term-images/
 * Author:      John James Jacoby
 * Author URI:  https://profiles.wordpress.org/johnjamesjacoby/
 * Version:     0.2.0
 * Description: Pretty images for categories, tags, and other taxonomy terms
 * License:     GPL v2 or later
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Instantiate the main WordPress Term Images class
 *
 * @since 0.1.0
 */
function _wp_term_images() {

	// Bail if no term meta
	if ( ! function_exists( 'add_term_meta' ) ) {
		return;
	}

	// Setup the main file
	$file = __FILE__;

	// Include the main class
	include dirname( $file ) . '/includes/class-wp-term-meta-ui.php';
	include dirname( $file ) . '/includes/class-wp-term-images.php';

	// Instantiate the main class
	new WP_Term_Images( $file );
}
add_action( 'init', '_wp_term_images', 99 );
