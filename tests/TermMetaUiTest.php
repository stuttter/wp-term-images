<?php

use PHPUnit\Framework\TestCase;

final class TermMetaUiTest extends TestCase {
	private $ui;

	protected function setUp(): void {
		$GLOBALS['wpti_test'] = array();
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
		$_POST['term-image'] = '';

		$this->ui->save_meta( 42, 7, 'category' );

		$this->assertSame( array( array( 42, 'image' ) ), $GLOBALS['wpti_test']['calls']['delete_term_meta'] );
	}

	public function test_explicit_image_field_updates_image(): void {
		$_POST['term-image'] = '123';

		$this->ui->save_meta( 42, 7, 'category' );

		$this->assertSame( array( array( 42, 'image', '123' ) ), $GLOBALS['wpti_test']['calls']['update_term_meta'] );
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
