<?php

use PHPUnit\Framework\TestCase;

function wp_verify_nonce() { return wpti_test_call( __FUNCTION__, func_get_args() ); }
function wp_unslash( $value ) { return $value; }
function sanitize_text_field( $value ) { return $value; }
function absint( $value ) { return abs( (int) $value ); }

final class TermMetaUiTest extends TestCase {
	private $ui;

	protected function setUp(): void {
		$GLOBALS['wpti_test'] = array();
		$GLOBALS['wpti_test']['returns']['wp_verify_nonce'] = true;
		$_POST                = array();
		$this->ui             = new WPTI_Test_UI();

		$GLOBALS['wpdb'] = (object) array(
			'termmeta' => 'wp_termmeta',
		);
	}

	public function test_programmatic_term_update_does_not_delete_existing_image(): void {
		$this->ui->save_meta( 42, 7, 'category' );

		$this->assertArrayNotHasKey( 'delete_term_meta', $GLOBALS['wpti_test']['calls'] ?? array() );
		$this->assertArrayNotHasKey( 'update_term_meta', $GLOBALS['wpti_test']['calls'] ?? array() );
	}

	public function test_explicitly_empty_image_field_removes_image(): void {
		$_POST['term-image']       = '';
		$_POST['term-image-nonce'] = 'valid';

		$this->ui->save_meta( 42, 7, 'category' );

		$this->assertSame( array( array( 42, 'image' ) ), $GLOBALS['wpti_test']['calls']['delete_term_meta'] );
	}

	public function test_explicit_image_field_updates_image(): void {
		$_POST['term-image']       = '123';
		$_POST['term-image-nonce'] = 'valid';

		$this->ui->save_meta( 42, 7, 'category' );

		$this->assertSame( array( array( 42, 'image', 123 ) ), $GLOBALS['wpti_test']['calls']['update_term_meta'] );
	}

	public function test_invalid_nonce_does_not_change_image(): void {
		$_POST['term-image']       = '123';
		$_POST['term-image-nonce'] = 'invalid';
		$GLOBALS['wpti_test']['returns']['wp_verify_nonce'] = false;

		$this->ui->save_meta( 42, 7, 'category' );

		$this->assertArrayNotHasKey( 'delete_term_meta', $GLOBALS['wpti_test']['calls'] ?? array() );
		$this->assertArrayNotHasKey( 'update_term_meta', $GLOBALS['wpti_test']['calls'] ?? array() );
	}

	public function test_image_meta_is_available_in_the_rest_api(): void {
		$this->ui->register_meta();

		$registration = $GLOBALS['wpti_test']['calls']['register_meta'][0];

		$this->assertSame( 'term', $registration[0] );
		$this->assertSame( 'image', $registration[1] );
		$this->assertTrue( $registration[2]['show_in_rest'] );
		$this->assertTrue( $registration[2]['single'] );
		$this->assertSame( 'integer', $registration[2]['type'] );
	}

	public function test_image_meta_can_be_hidden_from_the_rest_api(): void {
		$GLOBALS['wpti_test']['filters']['wp_term_image_show_in_rest'] = false;

		$this->ui->register_meta();

		$registration = $GLOBALS['wpti_test']['calls']['register_meta'][0];

		$this->assertFalse( $registration[2]['show_in_rest'] );
	}

	public function test_generic_meta_registration_preserves_legacy_defaults(): void {
		$ui = new WPTI_Test_Generic_UI();

		$ui->register_meta();

		$registration = $GLOBALS['wpti_test']['calls']['register_meta'][0];

		$this->assertArrayNotHasKey( 'show_in_rest', $registration[2] );
		$this->assertArrayNotHasKey( 'single', $registration[2] );
		$this->assertArrayNotHasKey( 'type', $registration[2] );
	}

	public function test_visible_taxonomies_are_targeted_by_default(): void {
		$GLOBALS['wpti_test']['taxonomies'] = array( 'category', 'post_tag' );
		$ui = new WPTI_Test_Initialize_UI();

		$ui->initialize();

		$this->assertSame( array( 'category', 'post_tag' ), $ui->taxonomies );
	}

	public function test_target_taxonomies_can_be_restricted(): void {
		$GLOBALS['wpti_test']['taxonomies'] = array( 'category', 'post_tag' );
		$GLOBALS['wpti_test']['filters']['wp_term_image_allowed_taxonomies'] = array( 'category' );
		$ui = new WPTI_Test_Initialize_UI();

		$ui->initialize();

		$this->assertSame( array( 'category' ), $ui->taxonomies );
	}

	public function test_empty_target_taxonomies_skip_registration_and_hooks(): void {
		$GLOBALS['wpti_test']['taxonomies'] = array( 'category', 'post_tag' );
		$GLOBALS['wpti_test']['filters']['wp_term_image_allowed_taxonomies'] = array();
		$ui = new WPTI_Test_Initialize_UI();

		$ui->initialize();

		$this->assertSame( array(), $ui->taxonomies );
		$this->assertSame( 0, $ui->register_meta_calls );
		$this->assertSame( 0, $ui->add_hooks_calls );
	}

	public function test_unrelated_numeric_meta_ordering_is_untouched(): void {
		$clauses = $this->clauses();

		$this->assertSame(
			$clauses,
			$this->ui->terms_clauses( $clauses, array( 'product_cat' ), array( 'orderby' => 'meta_value_num' ) )
		);
	}

	public function test_image_ordering_uses_only_image_term_meta(): void {
		$actual = $this->ui->terms_clauses(
			$this->clauses(),
			array( 'category' ),
			array( 'orderby' => 'image' )
		);

		$this->assertSame( ' INNER JOIN wp_termmeta AS tm ON t.term_id = tm.term_id', $actual['join'] );
		$this->assertSame( " AND tm.meta_key = 'image'", $actual['where'] );
		$this->assertSame( 'ORDER BY tm.meta_value', $actual['orderby'] );
		$this->assertSame( 't.* , tm.*', $actual['fields'] );
	}

	private function clauses(): array {
		return array(
			'fields'  => 't.* ',
			'join'    => '',
			'where'   => '',
			'orderby' => 'ORDER BY t.name',
		);
	}
}
