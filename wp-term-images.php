<?php

/**
 * Plugin Name: WP Term Images
 * Plugin URI:  https://wordpress.org/plugins/wp-term-images/
 * Author:      John James Jacoby
 * Author URI:  https://profiles.wordpress.org/johnjamesjacoby/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Pretty images for categories, tags, and other taxonomy terms
 * Version:     0.3.1
 * Text Domain: wp-term-images
 * Domain Path: /assets/lang/
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Include the required files & dependencies
 *
 * @since 0.1.0
 */
function _wp_term_images() {

	// Setup the main file
	$plugin_path = plugin_dir_path( __FILE__ );

	// Classes
	require_once $plugin_path . '/includes/class-wp-term-meta-ui.php';
	require_once $plugin_path . '/includes/class-wp-term-images.php';
}
add_action( 'plugins_loaded', '_wp_term_images' );

/**
 * Instantiate the main class
 *
 * @since 0.2.0
 */
function _wp_term_images_init() {
	new WP_Term_Images( __FILE__ );
}
add_action( 'init', '_wp_term_images_init', 88 );
