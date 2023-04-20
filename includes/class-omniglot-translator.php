<?php

/**
 * The translator.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

/**
 * The translator class.
 */
class Omniglot_Translator {

	/**
	 * DeepL API key.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $deepl_api_key;

	/**
	 * Language of the text to be translated.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $source_lang;

	/**
	 * The language into which the text should be translated.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $target_lang;

	/**
	 * DeepL supported language codes.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $supported_languages;

	/**
	 * Should translate attributes.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $translate_attributes = true;

	/**
	 * Should translate post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $translate_slug = false;

	/**
	 * Should translate post seo.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $translate_seo = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->deepl_api_key       = omniglot_get_deepl_api_key();
		$this->supported_languages = array_keys( omniglot_supported_languages() );
	}

	/**
	 * Gets the DeepL API key.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_deepl_api_key() {
		return $this->deepl_api_key;
	}

	/**
	 * Sets the source language.
	 *
	 * @since 1.0.0
	 *
	 * @param string $lang Language of the text to be translated.
	 *
	 * @return void
	 */
	public function set_source_lang( $lang ) {
		$this->source_lang = $lang;
	}

	/**
	 * Gets the source language.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_source_lang() {
		return $this->source_lang;
	}

	/**
	 * Sets the target language.
	 *
	 * @since 1.0.0
	 *
	 * @param string $lang The language into which the text should be translated.
	 *
	 * @return void
	 */
	public function set_target_lang( $lang ) {
		$this->target_lang = $lang;
	}

	/**
	 * Gets the target language.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_target_lang() {
		return $this->target_lang;
	}

	/**
	 * Gets the supported languages.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_supported_languages() {
		return $this->supported_languages;
	}

	/**
	 * Sets the translate_attributes value.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $translate_attributes Should translate attributes.
	 *
	 * @return void
	 */
	public function set_translate_attributes( $translate_attributes ) {
		$this->translate_attributes = $translate_attributes;
	}

	/**
	 * Gets the translate_attributes value.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function get_translate_attributes() {
		return $this->translate_attributes;
	}

	/**
	 * Sets the translate_slug value.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $translate_slug Should translate post slug.
	 *
	 * @return void
	 */
	public function set_translate_slug( $translate_slug ) {
		$this->translate_slug = $translate_slug;
	}

	/**
	 * Gets the translate_slug value.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function get_translate_slug() {
		return $this->translate_slug;
	}

	/**
	 * Sets the translate_seo value.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $translate_seo Should translate post seo.
	 *
	 * @return void
	 */
	public function set_translate_seo( $translate_seo ) {
		$this->translate_seo = $translate_seo;
	}

	/**
	 * Gets the translate_seo value.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function get_translate_seo() {
		return $this->translate_seo;
	}

	/**
	 * Check if the given languages are supported.
	 *
	 * This method might throw an exception so you should wrap it in a try-catch-block.
	 *
	 * @since 1.0.0
	 *
	 * @param string $source_lang Language of the text to be translated.
	 * @param string $target_lang The language into which the text should be translated.
	 *
	 * @return bool
	 *
	 * @throws Exception Throws if language is not supported.
	 */
	protected function check_languages( $source_lang, $target_lang ) {
		if ( 'auto' !== $source_lang && ! in_array( $source_lang, $this->get_supported_languages(), true ) ) {
			throw new Exception(
				sprintf(
					/* translators: language name */
					esc_html__( 'The language "%s" is not supported as source language.', 'omniglot' ),
					$source_lang
				)
			);
		}

		if ( ! in_array( $target_lang, $this->get_supported_languages(), true ) ) {
			throw new Exception(
				sprintf(
					/* translators: language name */
					esc_html__( 'The language "%s" is not supported as target language.', 'omniglot' ),
					$target_lang
				)
			);
		}

		return true;
	}

	/**
	 * DeepL HTTP error codes.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function deepl_error_codes() {
		return array(
			400 => esc_html__( 'Wrong request, please check error message and your parameters.', 'omniglot' ),
			403 => esc_html__( 'Authorization failed. Please supply a valid auth_key parameter.', 'omniglot' ),
			413 => esc_html__( 'Request Entity Too Large. The request size exceeds the current limit.', 'omniglot' ),
			429 => esc_html__( 'Too many requests. Please wait and send your request once again.', 'omniglot' ),
			456 => esc_html__( 'Quota exceeded. The character limit has been reached.', 'omniglot' ),
		);
	}

	/**
	 * Gets DeepL error message.
	 *
	 * @since 1.0.0
	 *
	 * @param int $code The DeepL error code.
	 *
	 * @return string
	 */
	public function get_deepl_error_message( $code ) {
		$error_messages = $this->deepl_error_codes();

		return $error_messages[ $code ];
	}

	/**
	 * Gets the DeepL arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content The content to be translated.
	 *
	 * @return array
	 */
	public function get_deepl_query_args( $content ) {
		$deepl_args = array(
			'auth_key'            => $this->get_deepl_api_key(),
			'text'                => rawurlencode( $content ),
			'source_lang'         => $this->get_source_lang(),
			'target_lang'         => $this->get_target_lang(),
			'tag_handling'        => 'xml',
			'split_sentences'     => 1,
			'preserve_formatting' => 1,
		);

		if ( 'auto' === $this->get_source_lang() ) {
			unset( $deepl_args['source_lang'] );
		}

		return apply_filters( 'omniglot_deepl_query_args', $deepl_args );
	}

	/**
	 * Gets the translated content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content The content to be translated.
	 *
	 * @return string
	 *
	 * @throws Exception Throws DeepL error if occured.
	 */
	public function get_translated( $content ) {
		$deepl_args = $this->get_deepl_query_args( $content );
		$endpoint   = omniglot_get_deepl_endpoint();
		$deepl_url  = add_query_arg( $deepl_args, $endpoint );
		$response   = wp_remote_get( $deepl_url );

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		}

		$code = $response['response']['code'];

		if ( in_array( $code, array_keys( $this->deepl_error_codes() ), true ) ) {
			throw new Exception( $this->get_deepl_error_message( $code ) );
		} elseif ( 200 !== $code ) {
			throw new Exception( esc_html__( 'DeepL Internal error.', 'omniglot' ) );
		}

		$body               = wp_remote_retrieve_body( $response );
		$decoded            = json_decode( $body, true );
		$translated_content = $decoded['translations']['0']['text'];

		return $translated_content;
	}

	/**
	 * Gets the translated attributes.
	 *
	 * This method might throw an exception so you should wrap it in a try-catch-block.
	 *
	 * @see https://stackoverflow.com/a/10834989/2647905
	 * @see https://stackoverflow.com/a/17220474/2647905
	 * @see https://stackoverflow.com/a/11387770/2647905
	 * @see https://davidwalsh.name/domdocument-utf8-problem
	 * @see https://haroonejaz.net/how-to-replace-image-src-in-dynamic-html-string-with-php/
	 * @see https://stackoverflow.com/a/45680712/2647905
	 *
	 * @since 1.0.0
	 *
	 * @param string $content The content to be translated.
	 *
	 * @return string
	 *
	 * @throws Exception Throws DeepL error if occured.
	 */
	public function get_translated_attributes( $content ) {
		if ( ! strlen( $content ) ) {
			return;
		}

		$dom = new DOMDocument();
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		$translatable_tags = omniglot_tags_n_atts_to_be_translated();

		foreach ( $translatable_tags as $translatable_tag => $attributes ) {
			$tags_in_content = $dom->getElementsByTagName( $translatable_tag );

			foreach ( $tags_in_content as $tag ) {
				foreach ( $attributes as $attribute ) {
					$old_value = $tag->getAttribute( $attribute );

					if ( ! $old_value ) {
						continue;
					}

					$before_translated_tag = $dom->saveHTML( $tag );

					$new_value = $this->get_translated( $old_value );

					$tag->setAttribute( $attribute, $new_value );

					$translated_tag = $dom->saveHTML( $tag );

					$content = str_replace(
						$before_translated_tag,
						$translated_tag,
						$content
					);
				}
			}
		}

		return $content;
	}

	/**
	 * Translate content.
	 *
	 * This method might throw an exception so you should wrap it in a try-catch-block.
	 *
	 * @param string $content The content to be translated.
	 *
	 * @return string
	 *
	 * @throws Exception Throws DeepL error if occured.
	 */
	public function translate( $content ) {
		$this->check_languages( $this->get_source_lang(), $this->get_target_lang() );

		$translated_content = $this->get_translated( $content );

		if ( $this->get_translate_attributes() ) {
			$translated_content = $this->get_translated_attributes( $translated_content );
		}

		$translated_content = apply_filters( 'omniglot_after_translate', $translated_content, $this );

		return $translated_content;
	}

	/**
	 * Gets the translated post.
	 *
	 * This method might throw an exception so you should wrap it in a try-catch-block.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post id.
	 *
	 * @return array
	 *
	 * @throws Exception Throws if anything wrong happened when translating or updating the post.
	 */
	public function get_translated_post( $post_id ) {
		$post         = get_post( $post_id );
		$post_type    = $post->post_type;
		$post_title   = $post->post_title;
		$post_name    = $post->post_name;
		$post_content = $post->post_content;

		$post_data_to_be_translated = array(
			'post_title',
			'post_content',
			'post_name',
		);

		if ( ! $this->get_translate_slug() ) {
			$key = array_search( 'post_name', $post_data_to_be_translated, true );

			if ( false !== $key ) {
				unset( $post_data_to_be_translated[ $key ] );
			}
		}

		$post_data_to_be_translated = apply_filters(
			'omniglot_post_data_to_be_translated',
			$post_data_to_be_translated,
			$post_type,
			$post_id,
			$this
		);

		$post_meta_to_be_translated = array();

		if ( $this->get_translate_seo() ) {
			$yoast_seo_fields = array(
				'_yoast_wpseo_title',
				'_yoast_wpseo_bctitle',
				'_yoast_wpseo_metadesc',
				'_yoast_wpseo_focuskw',
				'_yoast_wpseo_opengraph-description',
			);

			$post_meta_to_be_translated = array_merge( $post_meta_to_be_translated, $yoast_seo_fields );
		}

		$post_meta_to_be_translated = apply_filters(
			'omniglot_post_meta_to_be_translated',
			$post_meta_to_be_translated,
			$post_type,
			$post_id,
			$this
		);

		$postarr = array(
			'ID' => $post_id,
		);

		$new_post_content = '';

		foreach ( $post_data_to_be_translated as $key ) {
			if ( 'post_content' === $key ) {
				$post_content_array = omniglot_split_long_string( $post_content );

				foreach ( $post_content_array as $content ) {
					$new_post_content .= $this->translate( $content );
				}

				$translated = $new_post_content;
			} else {
				$property   = ${$key};
				$translated = $this->translate( $property );

				if ( 'post_name' === $key ) {
					$translated = sanitize_title( $translated );
				}
			}

			$postarr[ $key ] = $translated;
		}

		foreach ( $post_meta_to_be_translated as $key ) {
			$original = get_post_meta( $post_id, $key, true );

			if ( ! $original ) {
				continue;
			}

			$translated = $this->translate( $original );

			$postarr['meta_data'][ $key ] = $translated;
		}

		return apply_filters( 'omniglot_translated_postarr', $postarr, $this );
	}

	/**
	 * Translates the post.
	 *
	 * This method might throw an exception so you should wrap it in a try-catch-block.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post id.
	 *
	 * @return int
	 *
	 * @throws Exception Throws if anything wrong happened when translating or updating the post.
	 */
	public function translate_post( $post_id ) {
		$postarr    = $this->get_translated_post( $post_id );
		$post_metas = isset( $postarr['meta_data'] ) ? $postarr['meta_data'] : array();

		unset( $postarr['meta_data'] );

		$updated_post_id = wp_update_post( $postarr, true );

		if ( is_wp_error( $updated_post_id ) ) {
			throw new Exception( $updated_post_id->get_error_message() );
		}

		foreach ( $post_metas as $key => $meta_value ) {
			update_post_meta( $post_id, $key, $meta_value );
		}

		do_action( 'omniglot_after_translate_post', $updated_post_id, $this );

		return $updated_post_id;
	}




	public function get_generate_post( $post_id,$Status) {
		$post         = get_post( $post_id );
		$post_type    = $post->post_type;
		$post_title   = $post->post_title;
		$post_name    = $post->post_name;
		$post_content = $post->post_content;
		$post_excerpt = $post->post_excerpt;
		if ($post_type=='product') {
			$post_data_to_be_translated = array(
				'post_title',
				'post_content',
				// 'post_name',
				'post_excerpt',
			);
		}else{
			$post_data_to_be_translated = array(
				'post_title',
				'post_content',
				// 'post_name',
			);	
		}
		if ( ! $this->get_translate_slug() ) {
			$key = array_search( 'post_name', $post_data_to_be_translated, true );

			if ( false !== $key ) {
				unset( $post_data_to_be_translated[ $key ] );
			}
		}

		$post_data_to_be_translated = apply_filters(
			'omniglot_post_data_to_be_translated',
			$post_data_to_be_translated,
			$post_type,
			$post_id,
			$this
		);

		$post_meta_to_be_translated = array();

		if ( $this->get_translate_seo() ) {
			if ($post_type=='product') {
				$yoast_seo_fields = array(
				'_yoast_wpseo_title',
				'_yoast_wpseo_bctitle',
				'_yoast_wpseo_metadesc',
				'_yoast_wpseo_focuskw',
				'_yoast_wpseo_opengraph-description',
				'_regular_price',
				'_sale_price',
				'_thumbnail_id',
				'_sku',
				);
				
			}else{
				$yoast_seo_fields = array(
				'_yoast_wpseo_title',
				'_yoast_wpseo_bctitle',
				'_yoast_wpseo_metadesc',
				'_yoast_wpseo_focuskw',
				'_yoast_wpseo_opengraph-description',
				);

			}
			
			$post_meta_to_be_translated = array_merge( $post_meta_to_be_translated, $yoast_seo_fields );
		}

		$post_meta_to_be_translated = apply_filters(
			'omniglot_post_meta_to_be_translated',
			$post_meta_to_be_translated,
			$post_type,
			$post_id,
			$this
		);
		$postarr = array(
			'post_status' =>$Status,
			'post_type'=>$post_type
			
		);

		$new_post_content = '';

		foreach ( $post_data_to_be_translated as $key ) {
			if ( 'post_content' === $key ) {
				$post_content_array = omniglot_split_long_string( $post_content );

				foreach ( $post_content_array as $content ) {
					$new_post_content .= $this->translate( $content );
				}

				$translated = $new_post_content;
			} else {

				$property   = ${$key};
				if(empty($property)){
					continue;
				}
				$translated = $this->translate( $property );	

				if ( 'post_name' === $key ) {
					$translated = sanitize_title( $translated );
				}
			}

			$postarr[ $key ] = $translated;
		}

		foreach ( $post_meta_to_be_translated as $key ) {
			$original = get_post_meta( $post_id, $key, true );

			if ( ! $original ) {
				continue;
			}

			$translated = $this->translate( $original );

			$postarr['meta_data'][ $key ] = $translated;
		}

		if( class_exists('\Elementor\Plugin') ){
		    
		    $builder_meta = get_post_meta( $post_id, '_elementor_data', true );

	        $array = json_decode($builder_meta, true);
			if(!empty($array)){
				foreach( $array as $index_1 => $sections ) {
				            foreach($sections['elements'] as $index_2 => $element){
				                $array[$index_1]['elements'][$index_2] = $this->processElements( $element );
				            }
				        }
						$postarr['meta_data'][ '_elementor_data' ] = wp_slash( json_encode( $array ) );
			}
	        
	       // update_post_meta( $post_id, '_elementor_data', wp_slash( json_encode( $array ) ) );

		}


		return apply_filters( 'omniglot_translated_postarr', $postarr, $this );
	}
public function get_widgets_fields(){
    

    $settings_disabled_widgets = [];
    $disabled_widgets = [
        'ee-calendar',
        'video',
        'google_maps',
        'icon',
        'ee-google-map',
        'timeline',
        'circle-progress',
        'form'
    ];

    //$disabled_widgets = array_merge( $disabled_widgets, $settings_disabled_widgets );

    $active_widgets = [];
    $names = [];
    foreach( \Elementor\Plugin::instance()->widgets_manager->get_widget_types() as $widget_slug => $widget ){
        if( in_array($widget_slug, $disabled_widgets) ) continue;

        $stack = $widget->get_stack();

        $widget_active = false;

        foreach( $stack['controls'] as $control ){
            if( $control['type'] !== 'text' ) continue;
            if( isset($control['of_type'] )){
                if( $control['of_type'] === 'video' ) continue;
            }

            if( isset( $control['default']) ){
                if( is_numeric( $control['default']) ) continue;
            }

            if( strpos ( strtolower($control['label']), 'css') !== false
                || strpos( strtolower($control['title']), 'class') !== false
                || strpos( strtolower($control['name']), 'css') !== false
                || strpos( strtolower($control['description']), 'css') !== false
                || strpos( strtolower($control['name']), 'url') !== false
                || strpos( strtolower($control['name']), 'query_id') !== false
            ) continue;

            $widget_active = true;
            $names[] = $control['name'];
        }

        if( $widget_active ) $active_widgets[$widget_slug] = $widget_slug;
    }

    $names = array_unique( $names );

    return $names;
}

public function processElements( $array ){

    $fields = $this->get_widgets_fields();

    /* html */
    if( isset($array['settings']['editor']) ) {
        $array['settings']['editor'] = $this->translate($array['settings']['editor']);        
    }

    foreach( $fields as $field ){
        if( isset($array['settings'][$field]) ) {
            $array['settings'][$field] = $this->translate($array['settings'][$field]);
        }
    }

    if( isset( $array['elements'] ) && count( $array['elements']) > 0 ){
        foreach( $array['elements'] as $index => $element){
            $array['elements'][$index] = $this->processElements( $element );
        }
    }
    return $array;
}

	public function generate_post( $post_id,$Status ) {
		$postarr    = $this->get_generate_post($post_id,$Status);
		$post_metas = isset( $postarr['meta_data'] ) ? $postarr['meta_data'] : array();

		unset( $postarr['meta_data'] );
		$updated_post_id = wp_insert_post( $postarr, true );
		//$updated_post_id = wp_update_post( $postarr, true );

		if ( is_wp_error( $updated_post_id ) ) {
			throw new Exception( $updated_post_id->get_error_message() );
		}
		
			global $wpdb;

	/*	if(\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)){ */
			$custom_fields = get_post_custom(  $post_id  );
   foreach ( $custom_fields as $key => $value ) {
   		
		  	if( is_array($value) && count($value) > 0 ) {
				foreach( $value as $i=>$v ) {

					$unserialized = @unserialize($v);
			        if( $v === 'b:0;' || $unserialized !== false ){
			            $v = unserialize( $v );
			        }

					if( $key === '_elementor_template_type' ){
						update_post_meta( $updated_post_id, $key, $v );
					}else{						
						add_post_meta( $updated_post_id, $key, $v );
		             	/*$result = $wpdb->insert( $wpdb->prefix.'postmeta', array(
							'post_id' => $updated_post_id,
							'meta_key' => $key,
							'meta_value' => $v
						));  */ 		
					}
				}			
			}
		}
		/*}*/
		$cn_post  = get_post( $post_id );
		$cn_post_type = $cn_post->post_type;
		if ($cn_post_type=='product') {
			$cat=get_the_terms($post_id,'product_cat');
			foreach ($cat as $cnpostcat) {
				$catArr[]=$cnpostcat->term_id;
			}
			wp_set_post_terms($updated_post_id, $catArr, 'product_cat' );		
		}else {
			$taxonomies = get_object_taxonomies( $cn_post_type );
	        $terms      = wp_get_object_terms($post_id, $taxonomies, []);

	        $set_terms  = [];
	        foreach( $terms as $term ){
	            if( ! isset($set_terms[$term->taxonomy]) ) $set_terms[$term->taxonomy] = [];
	            $set_terms[$term->taxonomy][] = $term->term_id;
	        }
	        
	        if( count( $set_terms ) > 0 ){
                foreach( $set_terms as $taxonomy => $ids ){
                    wp_set_object_terms( $updated_post_id, $ids, $taxonomy );
                }
            }


			/*$cat=get_the_terms($post_id,'category');
			if(!empty($cat) && ! is_wp_error( $cat )){
				foreach ($cat as $cnpostcat) {
					$catArr[]=$cnpostcat->term_id;
				}
				wp_set_post_terms($updated_post_id, $catArr, 'category' );	
			}*/

			/*
			$post_tags = get_the_terms( $post_id, 'post_tag' );
			if ( ! empty( $post_tags ) && ! is_wp_error( $post_tags ) ) {
			    foreach ($post_tags as $cnpostcat) {
					$catTagArr[]=$cnpostcat->term_id;
				}
			    wp_set_post_terms($updated_post_id, $catTagArr, 'post_tag' );	
			}
			*/
			 			
		}
		

		foreach ( $post_metas as $key => $meta_value ) {
			update_post_meta( $updated_post_id, $key, $meta_value );
		}
		do_action( 'omniglot_after_translate_post', $updated_post_id, $this );
		return $updated_post_id;
	}
    
}