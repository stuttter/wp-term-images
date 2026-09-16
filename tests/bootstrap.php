<?php

define( 'ABSPATH', dirname( __DIR__ ) . '/' );

$GLOBALS['wpti_test'] = array();

function wpti_test_call( $name, $arguments = array() ) {
	$GLOBALS['wpti_test']['calls'][ $name ][] = $arguments;

	return isset( $GLOBALS['wpti_test']['returns'][ $name ] )
		? $GLOBALS['wpti_test']['returns'][ $name ]
		: null;
}

function delete_term_meta() { return wpti_test_call( __FUNCTION__, func_get_args() ); }
function update_term_meta() { return wpti_test_call( __FUNCTION__, func_get_args() ); }
function clean_term_cache() { return wpti_test_call( __FUNCTION__, func_get_args() ); }
function register_meta() { return wpti_test_call( __FUNCTION__, func_get_args() ); }
function apply_filters( $hook, $value ) {
	wpti_test_call( __FUNCTION__, func_get_args() );

	return array_key_exists( $hook, $GLOBALS['wpti_test']['filters'] ?? array() )
		? $GLOBALS['wpti_test']['filters'][ $hook ]
		: $value;
}

require_once dirname( __DIR__ ) . '/includes/class-wp-term-meta-ui.php';

class WPTI_Test_UI extends JJJ\WP\Term\Meta\UI {
	public function __construct() {
		$this->meta_key   = 'image';
		$this->taxonomies = array( 'category', 'product_cat' );
	}
}
