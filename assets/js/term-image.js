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

		// What was clicked
		var clicked = $( this );

		// Picking an image
		wp_term_images_modal.on( 'select', function () {

			// Prevent doubles
			term_image_lock( 'lock' );

			// Get the image URL
			var image = wp_term_images_modal.state().get( 'selection' ).first().toJSON();

			if ( '' !== image ) {
				if ( ! clicked.hasClass( 'quick' ) ) {
					$( '#term-image' ).val( image.id );
					$( '#wp-term-images-photo' ).attr( 'src', image.url ).show();
					$( '.wp-term-images-remove' ).show();
				} else {
					$( 'button.wp-term-images-media' ).hide();
					$( 'a.button', '.inline-edit-row' ).show();
					$( ':input[name="term-image"]', '.inline-edit-row' ).val( image.id );
					$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', image.url ).show();
				}
			}

			term_image_lock( 'unlock' );
		} );

		// Open the modal
		wp_term_images_modal.open();
	} );

	/**
	 * Remove image
	 *
	 * @param {object} event The event
	 */
	$( '.wp-term-images-remove' ).on( 'click', function ( event ) {
		event.preventDefault();

		// Clear image metadata
		if ( ! $( this ).hasClass( 'quick' ) ) {
			$( '#term-image' ).val( 0 );
			$( '#wp-term-images-photo' ).attr( 'src', '' ).hide();
			$( '.wp-term-images-remove' ).hide();
		} else {
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( '' );
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', '' ).hide();
			$( 'a.button', '.inline-edit-row' ).hide();
			$( 'button.wp-term-images-media' ).show();
		}
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

	/**
	 * Quick edit interactions
	 */
    $( '.editinline' ).on( 'click', function() {
        var tag_id = $( this ).parents( 'tr' ).attr( 'id' ),
			image  = $( 'td.image img', '#' + tag_id ).attr( 'src' );

		if ( typeof( image ) !== 'undefined' ) {
			$( 'button.wp-term-images-media' ).hide();
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( image );
			$( 'a.button', '.inline-edit-row' ).show();
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', image ).show();
		} else {
			$( 'a.button', '.inline-edit-row' ).hide();
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( '' );
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', '' ).hide();
			$( 'button.wp-term-images-media' ).show();
		}
    } );
} );
