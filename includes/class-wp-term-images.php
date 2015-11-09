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
	public $version = '0.1.0';

	/**
	 * @var string Database version
	 */
	public $db_version = 201509070001;

	/**
	 * @var string Database version
	 */
	public $db_version_key = 'wpdb_term_image_version';

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

		// Ajax actions
		add_action( 'wp_ajax_assign_wp_term_images_media', array( $this, 'ajax_assign_media'     ) );
		add_action( 'wp_ajax_remove_wp_term_images',       array( $this, 'action_remove_avatars' ) );
		add_action( 'admin_action_remove-wp-term-images',  array( $this, 'action_remove_avatars' ) );

		// Setup the labels
		$this->labels = array(
			'singular'    => esc_html__( 'Image',  'wp-term-images' ),
			'plural'      => esc_html__( 'Images', 'wp-term-images' ),
			'description' => esc_html__( 'Assign terms a custom image to visually separate them from each-other.', 'wp-term-images' )
		);

		// Call the parent and pass the file
		parent::__construct( $file );
	}

	/**
	 * Runs when a user clicks the Remove button for the image
	 *
	 * @since 0.1.0
	 */
	public function ajax_action_remove_images() {

		// Bail if not our request
		if ( empty( $_GET['user_id'] ) || empty( $_GET['_wpnonce'] ) ) {
			return;
		}

		// Bail if nonce verification fails
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'remove_wp_term_images_nonce' ) ) {
			return;
		}

		// Cast values
		$user_id = (int) $_GET['user_id'];

		// Bail if term cannot be edited
		if ( ! current_user_can( 'edit_image', $user_id ) ) {
			wp_die( esc_html__( 'You do not have permission to edit this term.', 'wp-term-images' ) );
		}

		// Delete the image
		wp_term_images_delete_image( $user_id );

		// Output the default image
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			echo get_term_image( $user_id, 90 );
			die();
		}
	}

	/**
	 * AJAX callback for setting media ID as term image
	 *
	 * @since 0.1.0
	 */
	public function ajax_assign_media() {

		// check required information and permissions
		if ( empty( $_POST['term_id'] ) || empty( $_POST['media_id'] ) || empty( $_POST['_wpnonce'] ) ) {
			die();
		}

		// Cast values
		$media_id = (int) $_POST['media_id'];
		$term_id  = (int) $_POST['term_id'];

		// Bail if current user cannot proceed
		if ( ! current_user_can( 'upload_images' ) || ! current_user_can( 'edit_image', $term_id ) ) {
			die();
		}

		// Bail if nonce verification fails
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'assign_wp_term_images_nonce' ) ) {
			die();
		}

		// Make sure media is an image
		if ( wp_attachment_is_image( $media_id ) ) {
			update_term_meta( $term_id, $this->meta_key, $media_id );
		}

		// Output the new image
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			echo wp_get_attachment_image( $media_id );
			die();
		}
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
		wp_enqueue_script( 'wp-term-images', $this->url . 'assets/js/term-image.js', array( 'jquery' ), $this->db_version, true );

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
	 * Align custom `image` column
	 *
	 * @since 0.1.0
	 */
	public function admin_head() {
		?>

		<style type="text/css">
			.column-image {
				width: 74px;
			}
			.column-image img {
				height: 25px;
				width: 25px;
				display: inline-block;
				border: 2px solid #eee;
			}
		</style>

		<?php
	}

	/**
	 * Return the formatted output for the colomn row
	 *
	 * @since 0.1.2
	 *
	 * @param string $meta
	 */
	protected function format_output( $meta = '' ) {
		echo wp_get_attachment_image( $meta );
	}

	/**
	 * Output the form field
	 *
	 * @since 0.1.0
	 *
	 * @param  $term
	 */
	protected function form_field( $term = '' ) {

		// Remove image URL
		$remove_url = add_query_arg( array(
			'action'   => 'remove-wp-term-images',
			'term_id'  => $term->term_id,
			'_wpnonce' => false,
		) );

		// Get the meta value
		$value = isset( $term->term_id )
			? $this->get_meta( $term->term_id )
			: ''; ?>

		<div id="wp-term-images-photo">

			<?php if ( ! empty( $value ) ) : ?>

			<img src="<?php echo esc_url( wp_get_attachment_image_url( $value ) ); ?>" />

			<?php endif; ?>

		</div>

		<button type="text" name="term-<?php echo esc_attr( $this->meta_key ); ?>" id="term-<?php echo esc_attr( $this->meta_key ); ?>" class="button wp-term-images-media">
			<?php esc_html_e( 'Choose Image', 'wp-term-images' ); ?>
		</button>

		<a href="<?php echo esc_url( $remove_url ); ?>" class="button item-delete submitdelete deletion" id="wp-term-images-remove"<?php if ( empty( $value ) ) echo ' style="display:none;"'; ?>>
			<?php esc_html_e( 'Remove', 'wp-user-avatars' ); ?>
		</a>

		<?php
	}
}
endif;
