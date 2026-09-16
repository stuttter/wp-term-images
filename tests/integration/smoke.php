<?php

/**
 * Exercise image persistence and unrelated term-meta queries in WordPress.
 *
 * This file is loaded by the centrally maintained integration runner after the
 * production plugin build has been activated.
 *
 * @package WP_Term_ImagesTests
 */

defined( 'ABSPATH' ) || exit;

$assert = static function ( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
};

$term_ids = array();
$suffix   = strtolower( wp_generate_password( 12, false, false ) );

try {
	$assert( ! is_multisite(), 'WP Term Images must use the single-site integration profile.' );
	$assert( class_exists( 'WP_Term_Images' ), 'The production plugin did not load.' );

	foreach ( array( 20, 10 ) as $rank ) {
		$term = wp_insert_term(
			'Portfolio smoke ' . $rank . ' ' . $suffix,
			'category',
			array( 'slug' => 'portfolio-smoke-' . $rank . '-' . $suffix )
		);
		$assert( ! is_wp_error( $term ), 'WordPress could not create a smoke-test category.' );
		$term_id    = (int) $term['term_id'];
		$term_ids[] = $term_id;
		update_term_meta( $term_id, 'rank', $rank );
	}

	update_term_meta( $term_ids[0], 'image', 123 );
	$updated = wp_update_term( $term_ids[0], 'category', array( 'name' => 'Updated portfolio smoke ' . $suffix ) );
	$assert( ! is_wp_error( $updated ), 'WordPress could not update the smoke-test category.' );
	$assert( 123 === (int) get_term_meta( $term_ids[0], 'image', true ), 'A programmatic term update deleted the existing image.' );

	$response = rest_do_request( new WP_REST_Request( 'GET', '/wp/v2/categories/' . $term_ids[0] ) );
	$assert( 200 === $response->get_status(), 'The category REST request failed.' );
	$data = $response->get_data();
	$assert( 123 === (int) $data['meta']['image'], 'The REST response did not expose the stored image attachment ID.' );

	unregister_meta_key( 'term', 'image' );
	add_filter( 'wp_term_image_show_in_rest', '__return_false' );
	$image_ui = new WP_Term_Images( WP_PLUGIN_DIR . '/wp-term-images/wp-term-images.php' );
	$image_ui->register_meta();
	$response = rest_do_request( new WP_REST_Request( 'GET', '/wp/v2/categories/' . $term_ids[0] ) );
	$assert( 200 === $response->get_status(), 'The filtered category REST request failed.' );
	$data = $response->get_data();
	$assert( ! isset( $data['meta']['image'] ), 'The REST opt-out filter did not hide image metadata.' );
	remove_filter( 'wp_term_image_show_in_rest', '__return_false' );

	$ordered = get_terms(
		'category',
		array(
			'fields'     => 'ids',
			'hide_empty' => false,
			'include'    => $term_ids,
			'meta_key'   => 'rank',
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
		)
	);
	$assert( ! is_wp_error( $ordered ), 'The unrelated term-meta query failed.' );
	$assert( array_reverse( $term_ids ) === array_map( 'intval', $ordered ), 'WP Term Images interfered with unrelated numeric term-meta ordering.' );
} finally {
	foreach ( $term_ids as $term_id ) {
		wp_delete_term( $term_id, 'category' );
	}
}
