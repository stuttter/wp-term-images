/* global i10n_WPTermImages, ajaxurl */
jQuery( document ).ready( function( $ ) {
    'use strict';

	/* Globals */
	var wp_term_images_modal,
		term_image_working;

	/**
	 * Invoke the media modal
	 *
	 * @param {object} event The event
	 */
	$( '.wp-term-images-media' ).on( 'click', function ( event ) {
		event.preventDefault();

		// Already adding
		if ( term_image_working ) {
			return;
		}

		// Open the modal
		if ( wp_term_images_modal ) {
			wp_term_images_modal.open();
			return;
		}

		// First time modal
		wp_term_images_modal = wp.media.frames.wp_term_images_modal = wp.media( {
			title:    i10n_WPTermImages.insertMediaTitle,
			button:   { text: i10n_WPTermImages.insertIntoPost },
			library:  { type: 'image' },
			multiple: false
		} );

		// Picking an image
		wp_term_images_modal.on( 'select', function () {

			// Prevent doubles
			term_image_lock( 'lock' );

			// Get the image URL
			var image_url = wp_term_images_modal.state().get( 'selection' ).first().toJSON().id;

			// Post the new image
			$.post( ajaxurl, {
				action:   'assign_wp_term_images_media',
				media_id: image_url,
				term_id:  i10n_WPTermImages.term_id,
				_wpnonce: i10n_WPTermImages.mediaNonce
			}, function ( data ) {
console.log( data );
				// Update the UI
				if ( '' !== data ) {
					$( '#wp-term-images-photo' ).html( data );
					$( '#wp-term-images-remove' ).show();
				}

				term_image_lock( 'unlock' );
			} );
		} );

		// Open the modal
		wp_term_images_modal.open();
	} );

	/**
	 * Remove image
	 *
	 * @param {object} event The event
	 */
	$( '#wp-term-images-remove' ).on( 'click', function ( event ) {
		event.preventDefault();

		// Already removing
		if ( term_image_working ) {
			return;
		}

		// Prevent doubles
		term_image_lock( 'lock' );

		// Remove the URL
		$.get( ajaxurl, {
			action:   'remove_wp_term_images',
			term_id:  i10n_WPTermImages.term_id,
			_wpnonce: i10n_WPTermImages.deleteNonce
		} ).done( function ( data ) {

			// Update the UI
			if ( '' !== data ) {
				$( '#wp-term-images-photo' ).html( data );
				$( '#wp-term-images-remove' ).hide();
			}

			term_image_lock( 'unlock' );
		} );
	} );

	/**
	 * Lock the image fieldset
	 *
	 * @param {boolean} lock_or_unlock
	 */
	function term_image_lock( lock_or_unlock ) {
		if ( lock_or_unlock === 'unlock' ) {
			term_image_working = false;
			$( '.wp-term-images-media' ).prop( 'disabled', false );
		} else {
			term_image_working = true;
			$( '.wp-term-images-media' ).prop( 'disabled', true );
		}
	}

    jQuery( '.editinline' ).on( 'click', function() {
        var tag_id = jQuery( this ).parents( 'tr' ).attr( 'id' ),
			image  = jQuery( 'td.image i', '#' + tag_id ).attr( 'data-image' );

        jQuery( ':input[name="term-image"]', '.inline-edit-row' ).val( image );
    } );
} );
