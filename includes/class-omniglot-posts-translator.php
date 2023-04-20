<?php

/**
 * Omniglot Posts Translator
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

/**
 * Class Omniglot_Posts_Translator
 */
class Omniglot_Posts_Translator {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_omniglot_load_taxonomy', array( $this, 'load_taxonomy' ) );
		add_action('wp_ajax_omniglot_validate_before_translate_posts',array( $this, 'validate_before_translate_posts' ));
		add_action( 'wp_ajax_omniglot_translate_posts', array( $this, 'translate_posts' ) );
		add_action( 'wp_ajax_omniglot_generate_posts', array( $this, 'generate_posts' ) );
	}

	/**
	 * Loads the taxonomy via ajax.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_taxonomy() {
		check_ajax_referer( 'omniglot_process_translation', '_omniglot_nonce' );

		$omniglot_options = isset( $_POST['omniglot_options'] ) ?
			array_map( 'sanitize_text_field', wp_unslash( $_POST['omniglot_options'] ) )
			: array();

		$post_type = isset( $omniglot_options['post_type'] ) ? $omniglot_options['post_type'] : '';

		if ( $post_type ) {
			omniglot_get_post_type_taxonomy_filter_markup( $post_type );
		}

		exit;
	}

	/**
	 * Validates the user input before translating posts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function validate_before_translate_posts() {
		$messages = array();
		$valid    = 'false';

		if ( check_ajax_referer( 'omniglot_process_translation', '_omniglot_nonce', false ) ) {
			$omniglot_options = isset( $_POST['omniglot_options'] ) ?
				array_map( 'sanitize_text_field', wp_unslash( $_POST['omniglot_options'] ) )
				: array();

			$post_type       = isset( $omniglot_options['post_type'] ) ? $omniglot_options['post_type'] : '';
			$source_language = isset( $omniglot_options['source_language'] ) ? $omniglot_options['source_language'] : '';
			$target_language = isset( $omniglot_options['target_language'] ) ? $omniglot_options['target_language'] : '';

			if ( ! $post_type ) {
				$messages[] = esc_html__( 'Post type is required', 'omniglot' );
			} elseif ( ! array_key_exists( $post_type, omniglot_get_translatable_post_types() ) ) {
				$messages[] = esc_html__( 'Invalid post type', 'omniglot' );
			}

			if (
				'auto' !== $source_language
				&& ! array_key_exists( $source_language, omniglot_supported_languages() )
			) {
				$messages[] = esc_html__( 'Invalid source language', 'omniglot' );
			}

			if ( ! array_key_exists( $target_language, omniglot_supported_languages() ) ) {
				$messages[] = esc_html__( 'Invalid target language', 'omniglot' );
			}
		} else {
			$messages[] = esc_html__( 'Nonce mismatched', 'omniglot' );
		}

		if ( ! $messages ) {
			$valid = 'true';
		}

		$response = array(
			'valid'    => $valid,
			'messages' => $messages,
		);

		wp_send_json( $response );
	}

	/**
	 * Translate the posts via ajax.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function translate_posts() {
		$success = 'false';
		$status  = 'incomplete';
		$data    = array();
		$errors  = array();

		if ( check_ajax_referer( 'omniglot_process_translation', '_omniglot_nonce', false ) ) {
			$omniglot_options = isset( $_POST['omniglot_options'] ) ?
				array_map( 'sanitize_text_field', wp_unslash( $_POST['omniglot_options'] ) )
				: array();

			$page  = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
			$count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 0;

			$post_type            = isset( $omniglot_options['post_type'] ) ? $omniglot_options['post_type'] : '';
			$translate_atts       = isset( $omniglot_options['translate_atts'] ) ? $omniglot_options['translate_atts'] : '';
			$translate_slug       = isset( $omniglot_options['translate_slug'] ) ? $omniglot_options['translate_slug'] : '';
			$translate_seo        = isset( $omniglot_options['translate_seo'] ) ? $omniglot_options['translate_seo'] : '';
			$override_translation = true; //isset( $omniglot_options['override_translation'] ) ? $omniglot_options['override_translation'] : '';
			$source_language      = isset( $omniglot_options['source_language'] ) ? $omniglot_options['source_language'] : '';
			$target_language      = isset( $omniglot_options['target_language'] ) ? $omniglot_options['target_language'] : '';

			$tax_query = array();

			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$taxonomies = isset( $_POST['omniglot_options']['taxonomy'] ) ?
				wp_unslash( $_POST['omniglot_options']['taxonomy'] ) : array();
			// phpcs:enable

			foreach ( $taxonomies as $taxonony => $terms ) {
				$tax_query[] = array(
					'taxonomy' => $taxonony,
					'field'    => 'term_id',
					'terms'    => array_map( 'absint', $terms ),
				);
			}

			$translator = new Omniglot_Translator();
			$translator->set_source_lang( $source_language );
			$translator->set_target_lang( $target_language );
			$translator->set_translate_attributes( $translate_atts );
			$translator->set_translate_slug( $translate_slug );
			$translator->set_translate_seo( $translate_seo );

			$args = array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'meta_query' => array(
									array(
										'key'     => 'deepl_translated',
										'value'   => 1,
										'compare' => '='
										),
								),
				'posts_per_page' => apply_filters( 'omniglot_translate_posts_per_batch', 1 ),
				'paged'          => $page,
				'tax_query'      => $tax_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			);

			$args = apply_filters( 'omniglot_translate_posts_args', $args, $omniglot_options );

			$results     = new WP_Query( $args );
			$total_posts = $results->found_posts;
			$max_pages   = $results->max_num_pages;
			$translated = array();
			if ( $page <= $max_pages ) {
				$should_translate = false;
				foreach ( $results->posts as $post_id ) {
					// Translate the post.
					$key           = '_omniglot_post_translated_to_' . $target_language;
					$is_translated = get_post_meta( $post_id, $key, true );
					$cn_target_language = get_post_meta( $post_id, 'cn_post_translated_to', true );

					$deepl_translated = get_post_meta($post_id, 'deepl_translated',true);
					if ( ! $is_translated ) {
						$should_translate = true;
					}
					if ( $is_translated && $override_translation ) {
						$should_translate = true;
					}
					
					if ($should_translate) {
						try {

								// $cn_target_language = get_post_meta( $post_id, 'cn_post_translated_to', true );
								// if ($cn_target_language) {
								// 	$newArr[]=array('translated_to'=>$target_language,'translated_from'=>$source_language);
								// 	$translated_to=array_merge($cn_target_language,$newArr);
								// }else{
								// 	$translated_to[]= array('translated_to'=>$target_language,'translated_from'=>$source_language,'cn_child_post_id'=>$generate_post_id);
								// }
								$translated[] = $post_id;
								$translator->translate_post( $post_id );
								update_post_meta($post_id, 'cn_mylang', $target_language);
								//update_post_meta($post_id, 'cn_post_translated_to', $translated_to);
								update_post_meta($post_id, $key, true);

						} catch ( Exception $e ) {
							$errors[] = sprintf(
								/* translators: post_id, error */
								esc_html__( 'Error translating post#%1$d. Error: %2$s', 'omniglot' ),
								$post_id,
								$e->getMessage()
							);
						}
					} else {
						$errors[] = sprintf(
							/*translators: post_id, target language*/
							esc_html__( 'A translation already exists for post#%1$d lang#%2$s', 'omniglot' ),
							$post_id,
							$target_language
						);
					}

					$count++;
				}

				// Calculate percentage.
				$percentage = floor( ( $count / $total_posts ) * 100 );

				$page++;

				$data = array(
					'page'        => $page,
					'count'       => $count,
					'total_posts' => $total_posts,
					'status'      => $status,
					'percentage'  => $percentage,
					'errors'      => $errors,
					'translated'  => $translated,
				);
			} else {
				$data = array(
					'page'        => $page,
					'count'       => $count,
					'total_posts' => $total_posts,
					'status'      => 'complete',
					'percentage'  => 100,
					'errors'      => $errors,
				);
			}

			$success = 'true';
		}

		$response = array(
			'success' => $success,
			'data'    => $data,
			'deepl_translated'=>$deepl_translated
		);

		wp_send_json( $response );
	}
	public function generate_posts() {
		$success = 'false';
		$status  = 'incomplete';
		$data    = array();
		$errors  = array();

		if ( check_ajax_referer( 'omniglot_process_translation', '_omniglot_nonce', false ) ) {
			$omniglot_options = isset( $_POST['omniglot_options'] ) ?
				array_map( 'sanitize_text_field', wp_unslash( $_POST['omniglot_options'] ) )
				: array();

			$page  = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
			$count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 0;

			$post_type            = isset( $omniglot_options['post_type'] ) ? $omniglot_options['post_type'] : '';
			$cn_post_status            = isset( $omniglot_options['cn_post_status'] ) ? $omniglot_options['cn_post_status'] : '';
			$translate_atts       = isset( $omniglot_options['translate_atts'] ) ? $omniglot_options['translate_atts'] : '';
			$translate_slug       = isset( $omniglot_options['translate_slug'] ) ? $omniglot_options['translate_slug'] : '';
			$translate_seo        = isset( $omniglot_options['translate_seo'] ) ? $omniglot_options['translate_seo'] : '';
			$override_translation = true; //isset( $omniglot_options['override_translation'] ) ? $omniglot_options['override_translation'] : '';
			$source_language      = isset( $omniglot_options['source_language'] ) ? $omniglot_options['source_language'] : '';
			$target_language      = isset( $omniglot_options['target_language'] ) ? $omniglot_options['target_language'] : '';

			$tax_query = array();

			if ($cn_post_status) {
				$cn_status='draft';
			}else{
				$cn_status='publish';
			}


			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$taxonomies = isset( $_POST['omniglot_options']['taxonomy'] ) ?
				wp_unslash( $_POST['omniglot_options']['taxonomy'] ) : array();
			// phpcs:enable

			foreach ( $taxonomies as $taxonony => $terms ) {
				$tax_query[] = array(
					'taxonomy' => $taxonony,
					'field'    => 'term_id',
					'terms'    => array_map( 'absint', $terms ),
				);
			}

			$translator = new Omniglot_Translator();
			$translator->set_source_lang( $source_language );
			$translator->set_target_lang( $target_language );
			$translator->set_translate_attributes( $translate_atts );
			$translator->set_translate_slug( $translate_slug );
			$translator->set_translate_seo( $translate_seo );

			$args = array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'meta_query' => array(
									array(
										'key'     => 'deepl_translated',
										'value'   => 1,
										'compare' => '='
										),
								),
				'meta_key'=>'deepl_translated',
				'posts_per_page' => apply_filters( 'omniglot_translate_posts_per_batch', 1 ),
				'paged'          => $page,
				'tax_query'      => $tax_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			);

			$args = apply_filters( 'omniglot_translate_posts_args', $args, $omniglot_options );

			$results     = new WP_Query( $args );
			$total_posts = $results->found_posts;
			$max_pages   = $results->max_num_pages;
			$translated = array();
			if ( $page <= $max_pages ) {
				$should_translate = false;
				foreach ( $results->posts as $post_id ) {
					// Translate the post.
					$key           = '_omniglot_post_translated_to_' . $target_language;
					$is_translated = get_post_meta( $post_id, $key, true );
					
					$deepl_translated = get_post_meta($post_id, 'deepl_translated',true);
					if ( ! $is_translated ) {
						$should_translate = true;
					}

					if ( $is_translated && $override_translation ) {
						$should_translate = true;
					}
					
					if ($should_translate) {
						try {
								$translated[] = $post_id;
								$generate_post_id=$translator->generate_post($post_id,$cn_status);
								$cn_target_language = get_post_meta( $post_id, 'cn_post_translated_to', true );
								if ($cn_target_language) {
									$newArr[]=array('translated_to'=>$target_language,'translated_from'=>$source_language,'cn_child_post_id'=>$generate_post_id);
									$translated_to=array_merge($cn_target_language,$newArr);
									foreach ($translated_to as $cn_translated) {$alltranslated_to[]=$cn_translated['translated_to'];}
									$Match=0;
									foreach (omniglot_supported_languages() as $code => $name ) {
									if (in_array($code, $alltranslated_to)){$Match++;}}
									if ($Match>=8) {update_post_meta($post_id, 'deepl_translated', 2 );}
								}else{
									$translated_to[]= array('translated_to'=>$target_language,'translated_from'=>$source_language,'cn_child_post_id'=>$generate_post_id);
								}
								$cn_post_translated[]=array('translated_from'=>$source_language,'translated_to'=>$target_language,'cn_parent_post_id'=>$post_id);
								update_post_meta($generate_post_id, 'cn_post_translated_to_from', $cn_post_translated);
								update_post_meta($post_id, 'cn_post_translated_to', $translated_to);

								update_post_meta($generate_post_id, 'translated_from', $target_language);
								update_post_meta($post_id, 'translated_to', $source_language);

								update_post_meta($generate_post_id, 'cn_mylang', $target_language);
								update_post_meta($post_id, 'cn_mylang', $source_language);


								update_post_meta($generate_post_id, 'deepl_translated', 2 );

								update_post_meta($post_id, $key, true);
							
						} catch ( Exception $e ) {
							$errors[] = sprintf(
								/* translators: post_id, error */
								esc_html__( 'Error translating post#%1$d. Error: %2$s', 'omniglot' ),
								$post_id,
								$e->getMessage()
							);
						}
					} else {
						$errors[] = sprintf(
							/*translators: post_id, target language*/
							esc_html__( 'A translation already exists for post#%1$d lang#%2$s', 'omniglot' ),
							$post_id,
							$target_language
						);
					}

					$count++;
				}

				// Calculate percentage.
				$percentage = floor( ( $count / $total_posts ) * 100 );

				$page++;

				$data = array(
					'page'        => $page,
					'count'       => $count,
					'total_posts' => $total_posts,
					'status'      => $status,
					'percentage'  => $percentage,
					'errors'      => $errors,
					'translated'  => $translated,
				);
			} else {
				$data = array(
					'page'        => $page,
					'count'       => $count,
					'total_posts' => $total_posts,
					'status'      => 'complete',
					'percentage'  => 100,
					'errors'      => $errors,
				);
			}

			$success = 'true';
		}

		$response = array(
			'success' => $success,
			'data'    => $data,
		);

		wp_send_json( $response );
	}

}

if ( is_admin() ) {
	new Omniglot_Posts_Translator();
}
