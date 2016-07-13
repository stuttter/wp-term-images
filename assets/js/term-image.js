/* global i10n_WPTermImages, ajaxurl */
jQuery( document ).ready( function( $ ) {
    'use strict';

	/* Globals */
	var wp_term_images_modal;

	/**
	 * Invoke the media modal
	 *
	 * @param {object} event The event
	 */
	$( '#the-list' ).on( 'click', '.wp-term-images-media', function ( event ) {
		wp_term_images_show_media_modal( this, event );
	} );

	/**
	 * Invoke the media modal
	 *
	 * @param {object} event The event
	 */
	$( '#addtag' ).on( 'click', '.wp-term-images-media', function ( event ) {
		wp_term_images_show_media_modal( this, event );
	} );

	/**
	 * Invoke the media modal
	 *
	 * @param {object} event The event
	 */
	$( '#edittag' ).on( 'click', '.wp-term-images-media', function ( event ) {
		wp_term_images_show_media_modal( this, event );
	} );

	/**
	 * Remove image
	 *
	 * @param {object} event The event
	 */
	$( '.wp-term-images-remove' ).on( 'click', function ( event ) {
		wp_term_images_reset( this, event );
	} );

	/**
	 * Remove image
	 *
	 * @param {object} event The event
	 */
	$( '#addtag' ).on( 'click', '.wp-term-images-remove', function ( event ) {
		wp_term_images_reset( this, event );
	} );

	/**
	 * Quick edit interactions
	 */
    $( '#the-list' ).on( 'click', 'a.editinline', function() {
        var tag_id    = $( this ).parents( 'tr' ).attr( 'id' ),
			image     = $( 'td.image img', '#' + tag_id ),
			image_src = image.attr( 'src' ),
			image_id  = image.data( 'attachment-id' );

		if ( typeof( image_id ) !== 'undefined' ) {
			$( 'button.wp-term-images-media' ).hide();
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( image_id );
			$( 'a.button', '.inline-edit-row' ).show();
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', image_src ).show();
		} else {
			$( 'a.button', '.inline-edit-row' ).hide();
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( '' );
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', '' ).hide();
			$( 'button.wp-term-images-media' ).show();
		}
    } );

	/**
	 * Shows media modal, and sets image in placeholder
	 *
	 * @param {type} element
	 * @param {type} event
	 * @returns {void}
	 */
	function wp_term_images_show_media_modal( element, event ) {
		event.preventDefault();

		// First time modal
		wp_term_images_modal = wp.media.frames.wp_term_images_modal = wp.media( {
			title:    i10n_WPTermImages.insertMediaTitle,
			button:   { text: i10n_WPTermImages.insertIntoPost },
			library:  { type: 'image' },
			multiple: false
		} );

		// Picking an image
		wp_term_images_modal.on( 'select', function () {

			// Get the image URL
			var image = wp_term_images_modal.state().get( 'selection' ).first().toJSON();

			if ( '' !== image ) {
				if ( ! $( element ).hasClass( 'quick' ) ) {
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
		} );

		// Open the modal
		wp_term_images_modal.open();
	}

	/**
	 * Reset the add-tag form
	 *
	 * @param {element} element
	 * @param {event} event
	 * @returns {void}
	 */
	function wp_term_images_reset( element, event ) {
		event.preventDefault();

		// Clear image metadata
		if ( ! $( element ).hasClass( 'quick' ) ) {
			$( '#term-image' ).val( 0 );
			$( '#wp-term-images-photo' ).attr( 'src', '' ).hide();
			$( '.wp-term-images-remove' ).hide();
		} else {
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( '' );
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', '' ).hide();
			$( 'a.button', '.inline-edit-row' ).hide();
			$( 'button.wp-term-images-media' ).show();
		}
	}
} );
