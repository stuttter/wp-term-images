<?php

/**
 * Term Images Class
 *
 * @since 0.1.0
 *
 * @package Plugins/Terms/Metadata/Image
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Term_Images' ) ) :
/**
 * Main WP Term Images class
 *
 * @since 0.1.0
 */
final class WP_Term_Images extends WP_Term_Meta_UI {

	/**
	 * @var string Plugin version
	 */
	public $version = '0.3.1';

	/**
	 * @var string Database version
	 */
	public $db_version = 201607130001;

	/**
	 * @var string Metadata key
	 */
	public $meta_key = 'image';

	/**
	 * Hook into queries, admin screens, and more!
	 *
	 * @since 0.1.0
	 */
	public function __construct( $file = '' ) {

		// Setup the labels
		$this->labels = array(
			'singular'    => esc_html__( 'Image',  'wp-term-images' ),
			'plural'      => esc_html__( 'Images', 'wp-term-images' ),
			'description' => esc_html__( 'Assign terms a custom image to visually separate them from each-other.', 'wp-term-images' )
		);

		// Translations
		load_plugin_textdomain( 'wp-term-images' );

		// Call the parent and pass the file
		parent::__construct( $file );
	}

	/** Assets ****************************************************************/

	/**
	 * Enqueue quick-edit JS
	 *
	 * @since 0.1.0
	 */
	public function enqueue_scripts() {

		// Enqueue media
		wp_enqueue_media();

		// Enqueue media handler; includes quick-edit
		wp_enqueue_style( 'wp-term-images',  $this->url . 'assets/css/term-image.css', array(),           $this->db_version       );
		wp_enqueue_script( 'wp-term-images', $this->url . 'assets/js/term-image.js',   array( 'jquery' ), $this->db_version, true );

		// Term ID
		$term_id = ! empty( $_GET['tag_ID'] )
			? (int) $_GET['tag_ID']
			: 0;

		// Localize
		wp_localize_script( 'wp-term-images', 'i10n_WPTermImages', array(
			'insertMediaTitle' => esc_html__( 'Choose an Image', 'wp-user-avatars' ),
			'insertIntoPost'   => esc_html__( 'Set as image',    'wp-user-avatars' ),
			'deleteNonce'      => wp_create_nonce( 'remove_wp_term_images_nonce' ),
			'mediaNonce'       => wp_create_nonce( 'assign_wp_term_images_nonce' ),
			'term_id'          => $term_id,
		) );
	}

	/**
	 * Add help tabs for `image` column
	 *
	 * @since 0.1.2
	 */
	public function help_tabs() {
		get_current_screen()->add_help_tab(array(
			'id'      => 'wp_term_image_help_tab',
			'title'   => __( 'Term Image', 'wp-term-images' ),
			'content' => '<p>' . __( 'Terms can have unique images to help separate them from each other.', 'wp-term-images' ) . '</p>',
		) );
	}

	/**
	 * Return the formatted output for the colomn row
	 *
	 * @since 0.1.2
	 *
	 * @param string $meta
	 */
	protected function format_output( $meta = '' ) {

		// Filter image attributes and add the attachment ID
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'attachment_id_attr' ), 10, 2 );

		// Output the image attachment
		echo wp_get_attachment_image( $meta );

		// Remove our filter
		remove_filter( 'wp_get_attachment_image_attributes', array( $this, 'attachment_id_attr' ), 10, 2 );
	}

	/**
	 * Add attachment ID as data attribute, used by Quick Edit
	 *
	 * @since 0.1.3
	 *
	 * @param array $attr
	 * @param int   $attachment
	 * @param int   $size
	 */
	public static function attachment_id_attr( $attr = array(), $attachment = 0 ) {
		$attr['data-attachment-id'] = $attachment->ID;
		return $attr;
	}

	/**
	 * Output the form field
	 *
	 * @since 0.1.0
	 *
	 * @param  $term
	 */
	protected function form_field( $term = '' ) {

		$term_id = ! empty( $term->term_id )
			? $term->term_id
			: 0;

		// Remove image URL
		$remove_url = add_query_arg( array(
			'action'   => 'remove-wp-term-images',
			'term_id'  => $term_id,
			'_wpnonce' => false,
		) );

		// Get the meta value
		$value  = $this->get_meta( $term_id );
		$hidden = empty( $value )
			? ' style="display: none;"'
			: ''; ?>

		<div>
			<img id="wp-term-images-photo" src="<?php echo esc_url( wp_get_attachment_image_url( $value, 'full' ) ); ?>"<?php echo $hidden; ?> />
			<input type="text" style="display: none;" name="term-<?php echo esc_attr( $this->meta_key ); ?>" id="term-<?php echo esc_attr( $this->meta_key ); ?>" value="<?php echo esc_attr( $value ); ?>" />
		</div>

		<a class="button-secondary wp-term-images-media">
			<?php esc_html_e( 'Choose Image', 'wp-term-images' ); ?>
		</a>

		<a href="<?php echo esc_url( $remove_url ); ?>" class="button wp-term-images-remove"<?php echo $hidden; ?>>
			<?php esc_html_e( 'Remove', 'wp-user-avatars' ); ?>
		</a>

		<?php
	}

	/**
	 * Output the form field
	 *
	 * @since 0.1.0
	 *
	 * @param  $term
	 */
	protected function quick_edit_form_field() {
		?>

		<input type="hidden" name="term-<?php echo esc_attr( $this->meta_key ); ?>" value="">
		<button class="button wp-term-images-media quick">
			<?php esc_html_e( 'Choose Image', 'wp-term-images' ); ?>
		</button>
		<img src="" class="wp-term-images-media quick" style="display: none;" />
		<a href="" class="button wp-term-images-remove quick" style="display: none;">
			<?php esc_html_e( 'Remove', 'wp-user-avatars' ); ?>
		</a>

		<?php
	}
}
endif;
