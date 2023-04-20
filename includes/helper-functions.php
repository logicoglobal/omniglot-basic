<?php

/**
 * Helper functions.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

if ( ! function_exists( 'omniglot_get_deepl_api_key' ) ) {
	/**
	 * Gets the deepl api key.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function omniglot_get_deepl_api_key() {
		$omniglot_options = get_option( 'omniglot_options' );
		$deepl_api_key    = $omniglot_options['deepl_api_key'];

		return $deepl_api_key;
	}
}

if ( ! function_exists( 'omniglot_get_deepl_endpoint' ) ) {
	/**
	 * Gets the deepl api key.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function omniglot_get_deepl_endpoint() {
		$endpoint = apply_filters( 'omniglot_deepl_endpoint', 'https://api.deepl.com/v2/translate' );

		return $endpoint;
	}
}

if ( ! function_exists( 'omniglot_supported_languages' ) ) {
	/**
	 * DeepL supported languages.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function omniglot_supported_languages() {
		$languages = array(
			'FR' => 'French',
			'EN' => 'English',
			'EN-GB' => 'English (British)',
			'EN-US' => 'English (American)',
			'DE' => 'German',
			'ES' => 'Spanish',
			'PT' => 'Portuguese',
			'PT-BR' => 'Portuguese (Brazilian)',
			'PT-PT' => 'Portuguese (Excluding Brazilian)',
			'IT' => 'Italian',
			'NL' => 'Dutch',
			'PL' => 'Polish',
			'RU' => 'Russian',
			'ZH' => 'Chinese', 
			'JA' => 'Japanese',
			'PL' => 'Polish',
			'BG' => 'Bulgarian',
			'CS' => 'Czech',
			'DA' => 'Danish',
			'EL' => 'Greek',
			'ET' => 'Estonian',
			'FI' => 'Finnish',
			'HU' => 'Hungarian',
			'LV' => 'Latvian',
			'LT' => 'Lithuanian',
			'RO' => 'Romanian',
			'SV' => 'Swedish',
			'SL' => 'Slovenian',
			'SK' => 'Slovak',
		);

		return apply_filters( 'omniglot_supported_languages', $languages );
	}
}

if ( ! function_exists( 'omniglot_license_key_verification' ) ) {
	/**
	 * DeepL supported languages.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function omniglot_license_key_verification() {
		$omniglot_options=get_option( 'omniglot_options' );
		$license_key=$omniglot_options['omniglot-license'];
        $api_params = array(
            'slm_action' => 'slm_check',
            'secret_key' => CN_SPECIAL_SECRET_KEY,
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(YOUR_ITEM_REFERENCE),
        );
        $query = esc_url_raw(add_query_arg($api_params, CN_LICENSE_SERVER_URL));
        $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ( !empty( $license_data ) && isset( $license_data->result ) && $license_data->result == 'success') {
        	if ($license_data->status=='active') {
        		$verification=array('status'=>'active');
	        }else{
	        	$verification=array('status'=>$license_data->status,'message'=>$license_data->message);
	        }
        }else{
        	$verification=array('status'=>'error','message'=>$license_data->message);
        }
        
		return apply_filters( 'omniglot_license_key_verification', $verification );
	}
}

if ( ! function_exists( 'omniglot_tags_n_atts_to_be_translated' ) ) {
	/**
	 * List of tags and attributes to be translated.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function omniglot_tags_n_atts_to_be_translated() {
		$tags = array(
			'a'   => array( 'title' ),
			'img' => array( 'title', 'alt' ),
		);

		return apply_filters( 'omniglot_tags_n_atts_to_be_translated', $tags );
	}
}

if ( ! function_exists( 'omniglot_split_long_string' ) ) {
	/**
	 * Split long string into multiple strings.
	 *
	 * @since 1.0.0
	 *
	 * @param string $long_string The long string.
	 *
	 * @return array
	 */
	function omniglot_split_long_string( $long_string ) {
		$length     = apply_filters( 'omniglot_split_length', 1500 );
		$max_length = apply_filters( 'omniglot_split_max_length', 2000 );

		$truncate = new Omniglot_Truncate_HTML();
		$truncate->set_length( $length );
		$truncate->set_max_length( $max_length );
		$truncate->set_content( $long_string );

		return $truncate->get_splitted();
	}
}

if ( ! function_exists( 'omniglot_is_gutenberg_active' ) ) {
	/**
	 * Check if gutenberg plugin is active.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function omniglot_is_gutenberg_active() {
		if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
			// The Gutenberg plugin is on.
			return true;
		}

		$current_screen = get_current_screen();

		if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
			// Gutenberg page on 5+.
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'omniglot_get_error_message_wrapper' ) ) {
	/**
	 * Gets the error message wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function omniglot_get_error_message_wrapper() {
		echo '<div class="omniglot-translation-error"></div>';
	}
}

if ( ! function_exists( 'omniglot_get_meta_box_screens' ) ) {
	/**
	 * Gets the screens where the meta box should be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function omniglot_get_meta_box_screens() {
		$_post_types = get_post_types( array( 'public' => true ) );
		$post_types  = array();

		foreach ( $_post_types as $key => $value ) {
			if ( 'attachment' !== $key ) {
				$post_types[ $key ] = $value;
			}
		}

		return apply_filters( 'omniglot_meta_box_screens', $post_types );
	}
}

if ( ! function_exists( 'omniglot_get_translatable_post_types' ) ) {
	/**
	 * Gets the post types customer should be able to translate.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function omniglot_get_translatable_post_types() {
		$_post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_types  = array();

		foreach ( $_post_types as $post_type ) {
			if ( 'attachment' !== $post_type->name ) {
				$post_types[ $post_type->name ] = $post_type->label;
			}
		}

		return apply_filters( 'omniglot_translatable_post_types', $post_types );
	}
}


if ( ! function_exists( 'omniglot_get_post_type_taxonomies' ) ) {
	/**
	 * Gets the post type taxonomies.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type The post type.
	 *
	 * @return array
	 */
	function omniglot_get_post_type_taxonomies( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		$filtered_taxonomies = array();

		foreach ( $taxonomies as $taxonomy ) {
			$name = $taxonomy->name;

			if (
				'product_visibility' === $name
				|| 'product_shipping_class' === $name
			) {
				continue;
			}

			if ( 'product_type' === $name ) {
				$label = esc_html__( 'Product Type', 'omniglot' );
			} else {
				$label = $taxonomy->label;
			}

			$filtered_taxonomies[ $taxonomy->name ] = $label;
		}

		return apply_filters( 'omniglot_post_type_taxonomies', $filtered_taxonomies, $post_type, $taxonomies );
	}
}

if ( ! function_exists( 'omniglot_format_select_field' ) ) {
	/**
	 * Format the select field with options.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the select field.
	 * @param string $id The id of the select field.
	 * @param array  $terms The options' value and text.
	 *
	 * @return void
	 */
	function omniglot_format_select_field( $name, $id, $terms ) {
		?>
		<div class="omniglot-select-field-wrapper" style="max-width: 25em;">
			<select
				name="<?php echo esc_attr( $name ); ?>"
				id="<?php echo esc_attr( $id ); ?>"
				class="omniglot-taxonomy-select"
				multiple="multiple"
				style="width: 100%;"
			>
				<?php foreach ( $terms as $term ) : ?>
					<option value="<?php echo esc_attr( $term->term_id ); ?>">
						<?php echo esc_html( $term->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}
}

if ( ! function_exists( 'omniglot_get_post_type_taxonomy_filter_markup' ) ) {
	/**
	 * Gets the post type taxonomy filter markup.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type The post type.
	 *
	 * @return void
	 */
	function omniglot_get_post_type_taxonomy_filter_markup( $post_type ) {
		do_action( 'omniglot_before_post_type_taxonomy_filter_markup', $post_type );

		$taxonomies = omniglot_get_post_type_taxonomies( $post_type );

		foreach ( $taxonomies as $taxonomy => $name ) {
			$args = apply_filters(
				'omniglot_taxonomy_terms_args',
				array( 'taxonomy' => $taxonomy ),
				$taxonomy
			);

			$terms = apply_filters( 'omniglot_taxonomy_terms', get_terms( $args ) );

			if ( ! $terms ) {
				return;
			}

			printf( '<h3 class="term-title" style="font-size: 14px;">%s:</h3>', esc_html( $name ) );

			$name = "omniglot_options[taxonomy][${taxonomy}][]";
			$id   = $taxonomy;

			omniglot_format_select_field( $name, $id, $terms );
		}

		do_action( 'omniglot_after_post_type_taxonomy_filter_markup', $post_type );
	}
}

if ( ! function_exists( 'omniglot_get_placeholder_markup_for_total_translated_posts' ) ) {
	/**
	 * Gets the placeholder markup for total translated posts.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function omniglot_get_placeholder_markup_for_total_translated_posts() {
		return sprintf(
			'<span class="count">0</span> %1$s <span class="total">0</span> %2$s',
			esc_html__( 'of', 'omniglot' ),
			esc_html__( 'done', 'omniglot' )
		);
	}
}
