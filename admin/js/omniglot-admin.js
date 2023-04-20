( function( $ ) {
	'use strict';

	$( function() {
		if ( typeof omniglotParams === 'undefined' ) {
			return;
		}
		////// Created by Shivam (http://coderninja.in)//

			// $('.cn_select2').select2({placeholder: 'Click to select multiple '});



			$( '.omniglot-cn-save-btn' ).on( 'click', function( e ) {
				e.preventDefault();
				const $btn = $( this ),
				$wrapper = $btn.closest( '.omniglot-translator-meta-cn-box' ),
				$loader = $wrapper.find( '.cn_spinner_div' ),
				deepltranslated =$wrapper.find( '[name="deepl_translated"]' ).val(),
				nonce = $wrapper.find( '[name="omniglot_meta_cn_box_nonce"]' ).val(),
				postId = $wrapper.find( '[name="cn_post_id"]' ).val();

				$btn.addClass( 'button-disabled' );
				$loader.css( 'visibility', 'visible' );

				const data = {
					omniglot_nonce: nonce,
					post_id: postId,
					deepl_translated: deepltranslated,
					action: 'omniglot_dpl_translated',
				};
				$.ajax( {
					type: 'POST',
					url: omniglotParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						if ( response.success ) {
							response.deepl_translated
							  location.reload();
						}
						$btn.removeClass( 'button-disabled' );
						$loader.removeAttr( 'style' );
					},
				} );
			});
			$( '.omniglot-generate-new-btn' ).on( 'click', function( e ) {

				e.preventDefault();
				const $btn = $( this ),
					$wrapper = $btn.closest( '.omniglot-translator-meta-box' ),
					$loader = $wrapper.find( '.cn_spinner_div' ),
					$errorWrapper = $wrapper.find( '.omniglot-translation-error' ),
					nonce = $wrapper.find( '[name="omniglot_meta_box_nonce"]' ).val(),
					sourceLang = $wrapper.find( '#source_lang' ).val(),
					targetLang = $wrapper.find( '#target_lang' ).val(),
					translateAtts = $wrapper.find( '#translate_atts' ).is( ':checked' ),
					translateSlug = $wrapper.find( '#translate_slug' ).is( ':checked' ),
					translateSeo = $wrapper.find( '#translate_seo' ).is( ':checked' ),
					cn_saveas = $wrapper.find( '#cn_saveas' ).is( ':checked' ),
					gutenbergActive = $wrapper.find( '[name="gutenberg_active"]' ).val(),
					postId = $wrapper.find( '[name="post_id"]' ).val();
					
				$errorWrapper.css( 'display', 'none' );
				$errorWrapper.html( '' );
				$btn.addClass( 'button-disabled' );
				$loader.css( 'visibility', 'visible' );
				var cn_status;
				if (cn_saveas) {
					cn_status='draft';
				}else{
					cn_status='publish';
				}

				const data = {
					omniglot_nonce: nonce,
					post_id: postId,
					source_lang: sourceLang,
					target_lang: targetLang,
					translate_atts: translateAtts,
					translate_slug: translateSlug,
					translate_seo: translateSeo,
					gutenberg_active: gutenbergActive,
					cn_post_status: cn_status,
					action: 'omniglot_generate',
				};

				$.ajax( {
					type: 'POST',
					url: omniglotParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						$( 'body' ).trigger( 'omniglot_after_ajax_response', [ response ] );

						if ( response.error.length ) {
							$errorWrapper.css( 'display', 'block' );
							$errorWrapper.html( '<p>' + response.error + '</p>' );
						} else if ( response.success ) {
							  $('#generate_new_page_url').attr('href', omniglotParams.admin_post_url+'?post='+response.translated+'&action=edit');
							  //$('#generate_new').show();
							  location.reload();
						}
						$( 'body' ).trigger( 'omniglot_before_hiding_loader', [ response ] );
						$btn.removeClass( 'button-disabled' );
						$loader.removeAttr( 'style' );

					},
				} );
			} );

			$( '.omniglot-generate-manual-btn' ).on( 'click', function( e ) {

				e.preventDefault();
				const $btn = $( this ),
					$wrapper = $btn.closest( '.omniglot-translator-meta-box' ),
					$loader = $wrapper.find( '.cn_spinner_div' ),
					$errorWrapper = $wrapper.find( '.omniglot-translation-error' ),
					nonce = $wrapper.find( '[name="omniglot_meta_box_nonce"]' ).val(),
					sourceLang = $wrapper.find( '#source_lang' ).val(),
					targetLang = $wrapper.find( '#target_lang' ).val(),
					translateAtts = $wrapper.find( '#translate_atts' ).is( ':checked' ),
					translateSlug = $wrapper.find( '#translate_slug' ).is( ':checked' ),
					translateSeo = $wrapper.find( '#translate_seo' ).is( ':checked' ),
					cn_saveas = $wrapper.find( '#cn_saveas' ).is( ':checked' ),
					manual_translate = 'manual_translate',
					gutenbergActive = $wrapper.find( '[name="gutenberg_active"]' ).val(),
					postId = $wrapper.find( '[name="post_id"]' ).val();
					
				$errorWrapper.css( 'display', 'none' );
				$errorWrapper.html( '' );
				$btn.addClass( 'button-disabled' );
				$loader.css( 'visibility', 'visible' );
				var cn_status;
				if (cn_saveas) {
					cn_status='draft';
				}else{
					cn_status='publish';
				}

				const data = {
					omniglot_nonce: nonce,
					post_id: postId,
					source_lang: sourceLang,
					target_lang: targetLang,
					translate_atts: translateAtts,
					translate_slug: translateSlug,
					translate_seo: translateSeo,
					gutenberg_active: gutenbergActive,
					cn_post_status: cn_status,
					cn_manual_translate: manual_translate,
					action: 'omniglot_generate',
				};

				$.ajax( {
					type: 'POST',
					url: omniglotParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						$( 'body' ).trigger( 'omniglot_after_ajax_response', [ response ] );

						if ( response.error.length ) {
							$errorWrapper.css( 'display', 'block' );
							$errorWrapper.html( '<p>' + response.error + '</p>' );
						} else if ( response.success ) {
							  $('#generate_new_page_url').attr('href', omniglotParams.admin_post_url+'?post='+response.translated+'&action=edit');
							  //$('#generate_new').show();
							  location.reload();
						}
						$( 'body' ).trigger( 'omniglot_before_hiding_loader', [ response ] );
						$btn.removeClass( 'button-disabled' );
						$loader.removeAttr( 'style' );

					},
				} );
			} );
			
		////// Created by Shivam (http://coderninja.in)//


		$( '.omniglot-translate-btn' ).on( 'click', function( e ) {
			e.preventDefault();

			const $btn = $( this ),
				$wrapper = $btn.closest( '.omniglot-translator-meta-box' ),
				$loader = $wrapper.find( '.cn_spinner_div' ),
				$errorWrapper = $wrapper.find( '.omniglot-translation-error' ),
				nonce = $wrapper.find( '[name="omniglot_meta_box_nonce"]' ).val(),
				sourceLang = $wrapper.find( '#source_lang' ).val(),
				targetLang = $wrapper.find( '#target_lang' ).val(),
				translateAtts = $wrapper.find( '#translate_atts' ).is( ':checked' ),
				translateSlug = $wrapper.find( '#translate_slug' ).is( ':checked' ),
				translateSeo = $wrapper.find( '#translate_seo' ).is( ':checked' ),
				gutenbergActive = $wrapper.find( '[name="gutenberg_active"]' ).val(),
				postId = $wrapper.find( '[name="post_id"]' ).val();

			$errorWrapper.css( 'display', 'none' );
			$errorWrapper.html( '' );
			$btn.addClass( 'button-disabled' );
			$loader.css( 'visibility', 'visible' );

			const data = {
				omniglot_nonce: nonce,
				post_id: postId,
				source_lang: sourceLang,
				target_lang: targetLang,
				translate_atts: translateAtts,
				translate_slug: translateSlug,
				translate_seo: translateSeo,
				gutenberg_active: gutenbergActive,
				action: 'omniglot_translate',
			};

			$.ajax( {
				type: 'POST',
				url: omniglotParams.ajaxurl,
				data,
				dataType: 'json',
				success( response ) {
					$( 'body' ).trigger( 'omniglot_after_ajax_response', [ response ] );

					if ( response.error.length ) {
						$errorWrapper.css( 'display', 'block' );
						$errorWrapper.html( '<p>' + response.error + '</p>' );
					} else if ( response.success ) {
						if ( gutenbergActive ) {
							// Reload the page
							location.reload();
						} else {
							location.reload();
							// // Replace the content with translated content
							// $( '#poststuff #title' ).val( response.translated.post_title );
							// omniglotSetWpEditorContent( response.translated.post_content );

							// if ( response.translated.post_name !== undefined ) {
							// 	$( '#editable-post-name, #editable-post-name-full' ).html( response.translated.post_name );
							// }
						}
					}

					$( 'body' ).trigger( 'omniglot_before_hiding_loader', [ response ] );

					$btn.removeClass( 'button-disabled' );
					$loader.removeAttr( 'style' );
				},
			} );
		} );

		function omniglotGetWpEditorContent() {
			let content;

			if (
				'undefined' !== typeof window.tinyMCE &&
                window.tinyMCE.get( 'content' ) &&
                ! window.tinyMCE.get( 'content' ).isHidden()
			) {
				content = window.tinyMCE.get( 'content' ).getContent();
			} else {
				content = $( '#content' ).val();
			}

			return content.trim();
		}

		function omniglotSetWpEditorContent( newContent ) {
			if (
				'undefined' !== typeof window.tinyMCE &&
                window.tinyMCE.get( 'content' ) &&
                ! window.tinyMCE.get( 'content' ).isHidden()
			) {
				const editor = window.tinyMCE.get( 'content' );
				editor.setContent( newContent, { format: 'html' } );
			} else {
				$( '#content' ).val( newContent );
			}
		}

		function initSelect2() {
			if ( jQuery().select2 ) {
				$( '.omniglot-taxonomy-select' ).select2();
			}
		}

		$( '.settings_page_omniglot #post_type' ).on( 'change', function() {
			const that = $( this ),
				$form = that.closest( 'form' ),
				postType = that.val(),
				$spinner = that.parent().find( '.omniglot-spinner' ),
				$wrapper = $( '.omniglot-dynamic-taxonomy-filter-wrapper' );

			let data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();

			data += '&action=omniglot_load_taxonomy';
			that.attr( 'disabled', 'disabled' );
			$spinner.css( 'visibility', 'visible' );
			$wrapper.html( '' );

			if ( ! postType ) {
				that.removeAttr( 'disabled' );
				$spinner.css( 'visibility', 'hidden' );
				return;
			}

			$.ajax( {
				type: 'POST',
				url: omniglotParams.ajaxurl,
				data,
				dataType: 'html',
				success( response ) {
					$wrapper.html( response );
					// Attach select2
					initSelect2();

					that.removeAttr( 'disabled' );
					$spinner.css( 'visibility', 'hidden' );
				},
			} );
		} );

		function processTranslation( $form, data ) {
			const $progressWrapper = $form.parent( '.wrap' ).find( '.omniglot-translation-progress' ),
				$progressbar = $progressWrapper.find( '.progressbar > div' ),
				$progressCount = $progressWrapper.find( '.count' ),
				$progressTotal = $progressWrapper.find( '.total' ),
				$submitBtn = $form.find( '.button' ),
				$mylod = $form.find( '.mylod' ),
				$messageWrapper = $progressWrapper.find( '.omniglot-success-message' );

			$.ajax( {
				url: omniglotParams.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data,
				success( response ) {
					if ( response.success === 'true' ) {
						$progressCount.html( response.data.count );
						$progressbar.css( 'width', response.data.percentage + '%' );

						if ( ! $progressWrapper.hasClass( 'active' ) ) {
							$progressWrapper.addClass( 'active' );
						}

						if ( response.data.status === 'incomplete' ) {
							$progressTotal.html( response.data.total_posts );

							data += '&page=' + response.data.page;
							data += '&count=' + response.data.count;

							processTranslation( $form, data );
						} else {
							$submitBtn.removeAttr( 'disabled' );
							$mylod.hide();
							$messageWrapper.html( omniglotParams.translationSuccessMessage );
						}
					} else {
						$mylod.hide();
						console.log( 'there was an error' );
					}
				},
			} ).fail( function( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			} );
		}

		function validateBeforeTranslation( $form, $submitBtn, $errorWrapper ) {
			let data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
			const $mylod = $form.find( '.mylod' );

			data += '&action=omniglot_validate_before_translate_posts';

			$.ajax( {
				type: 'POST',
				url: omniglotParams.ajaxurl,
				data,
				dataType: 'json',
				success( response ) {
					if ( 'false' === response.valid ) {
						const messages = response.messages;
						let markup = '';

						for ( let i = 0; i < messages.length; i++ ) {
							markup += '<p>' + messages[ i ] + '</p>';
						}

						$errorWrapper.html( markup );
						$errorWrapper.css( 'display', 'block' );
						$submitBtn.removeAttr( 'disabled' );
					} else {
						const $progressWrapper = $form.parent( '.wrap' ).find( '.omniglot-translation-progress' ),
							$progressInfo = $progressWrapper.find( '.progress-info' );

						data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
						data += '&action=omniglot_translate_posts';

						$progressInfo.html( omniglotParams.total_translated_placeholder );
						$progressWrapper.addClass( 'active' );
						$mylod.show();
						processTranslation( $form, data );
					}
				},
			} );
		}

		$( '.settings_page_omniglot #translate_posts_btn' ).on( 'click', function( e ) {
			e.preventDefault();
			// console.log('admin all translate');

			const $form = $( this ).closest( 'form' ),
				$progressWrapper = $form.parent().find( '.omniglot-translation-progress' ),
				$progressbar = $progressWrapper.find( '.progressbar > div' ),
				$submitBtn = $form.find( '.button' ),
				$mylod = $form.find( '.mylod' ),
				$messageWrapper = $progressWrapper.find( '.omniglot-success-message' ),
				$errorWrapper = $form.parent().find( '.omniglot-translation-error' );

			$progressbar.css( 'width', '0' );
			$messageWrapper.html( '' );
			$errorWrapper.html( '' );
			$errorWrapper.css( 'display', 'none' );
			$submitBtn.attr( 'disabled', 'disabled' );
			

			// Validate the user inputs.
			validateBeforeTranslation( $form, $submitBtn, $errorWrapper );
		} );


		//////
			function cn_processTranslation( $form, data ) {
				const $progressWrapper = $form.parent( '.wrap' ).find( '.omniglot-translation-progress' ),
					$progressbar = $progressWrapper.find( '.progressbar > div' ),
					$progressCount = $progressWrapper.find( '.count' ),
					$progressTotal = $progressWrapper.find( '.total' ),
					$submitBtn = $form.find( '.button' ),
					$mylod = $form.find( '.mylod' ),
					$messageWrapper = $progressWrapper.find( '.omniglot-success-message' );

				$.ajax( {
					url: omniglotParams.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data,
					success( response ) {
						if ( response.success === 'true' ) {
							$progressCount.html( response.data.count );
							$progressbar.css( 'width', response.data.percentage + '%' );
							if ( ! $progressWrapper.hasClass( 'active' ) ) {
								$progressWrapper.addClass( 'active' );
							}
							if ( response.data.status === 'incomplete' ) {
								$progressTotal.html( response.data.total_posts );
								data += '&page=' + response.data.page;
								data += '&count=' + response.data.count;
								cn_processTranslation( $form, data );
							} else {
								$submitBtn.removeAttr( 'disabled' );
								$mylod.hide();
								$messageWrapper.html( omniglotParams.translationSuccessMessage );
							}
						} else {
							$mylod.hide();
							console.log( 'there was an error' );
						}
					},
				} ).fail( function( response ) {
					if ( window.console && window.console.log ) {
						console.log( response );
					}
				} );
			}
			function cn_validateBeforeTranslation( $form, $submitBtn, $errorWrapper ) {
				let data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
				const $mylod = $form.find( '.mylod' );
				data += '&action=omniglot_validate_before_translate_posts';

				$.ajax( {
					type: 'POST',
					url: omniglotParams.ajaxurl,
					data,
					dataType: 'json',
					success( response ) {
						if ( 'false' === response.valid ) {
							const messages = response.messages;
							let markup = '';
							for ( let i = 0; i < messages.length; i++ ) {
								markup += '<p>' + messages[ i ] + '</p>';
							}
							$errorWrapper.html( markup );
							$errorWrapper.css( 'display', 'block' );
							$submitBtn.removeAttr( 'disabled' );
						} else {
							const $progressWrapper = $form.parent( '.wrap' ).find( '.omniglot-translation-progress' ),
								$progressInfo = $progressWrapper.find( '.progress-info' );

							data = $form.find( ':not([name="action"], [name="_wpnonce"])' ).serialize();
							data += '&action=omniglot_generate_posts';

							$progressInfo.html( omniglotParams.total_translated_placeholder );
							$progressWrapper.addClass( 'active' );
							$mylod.show();
							cn_processTranslation( $form, data );
						}
					},
				} );
			}
			$('.settings_page_omniglot #generate_posts_btn' ).on('click', function(e) {
				e.preventDefault();
				 console.log('admin all Generate');
				const $form = $( this ).closest( 'form' ),
					$progressWrapper = $form.parent().find( '.omniglot-translation-progress' ),
					$progressbar = $progressWrapper.find( '.progressbar > div' ),
					$submitBtn = $form.find( '.button' ),
					$mylod = $form.find( '.mylod' ),
					$messageWrapper = $progressWrapper.find( '.omniglot-success-message' ),
					$errorWrapper = $form.parent().find( '.omniglot-translation-error' );
				$progressbar.css( 'width', '0' );
				$messageWrapper.html( '' );
				$errorWrapper.html( '' );
				$errorWrapper.css( 'display', 'none' );
				$submitBtn.attr( 'disabled', 'disabled' );


				// Validate the user inputs.
				cn_validateBeforeTranslation( $form, $submitBtn, $errorWrapper );
			} );
		/////





	} );
}( jQuery ) );
