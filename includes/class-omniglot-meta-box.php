<?php

/**
 * The file is responsible to add meta box.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

/**
 * Omniglot_Meta_Box class.
 */
class Omniglot_Meta_Box {

	/**
	 * Instantiate the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public static function get_instance() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new Omniglot_Meta_Box();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Set up the default hooks and actions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup_actions() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'wp_ajax_omniglot_translate', array( $this, 'process_translation_via_ajax' ) );
		add_action( 'wp_ajax_omniglot_generate', array( $this, 'process_generate_via_ajax' ) );
		
		add_action( 'wp_ajax_omniglot_dpl_translated', array( $this, 'dpl_translated_via_ajax' ) );
	}

	/**
	 * Adds the meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_meta_box() {
		add_meta_box(
			'omniglot_box_id',
			esc_html__( 'Omniglot Translation', 'omniglot' ),
			array( $this, 'meta_box_html' ),
			omniglot_get_meta_box_screens(),
			'side',
			'high'
		);
		add_meta_box(
			'omniglot_cn_box_id',
			esc_html__( 'Omniglot Translator', 'omniglot' ),
			array( $this, 'meta_cn_box_html' ),
			omniglot_get_meta_box_screens(),
			'side',
			'high'
		);
	}

	/**
	 * Meta box html.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function meta_cn_box_html() {
		?>
		<div class="omniglot-translator-meta-cn-box">
			<?php

			$post_statusArr=array('publish' =>'Published', 'draft' =>'Draft');

			$cn_license = get_option('cn_license');
			if ($cn_license=='activated') { 
			$verification=omniglot_license_key_verification();
			if ($verification['status']=='active') {
				$post_id = get_the_ID();
				$deepl_translated=get_post_meta($post_id, 'deepl_translated',true);
				$cn_post_translated_to=get_post_meta($post_id, 'cn_post_translated_to',true);
				$cn_post_translated_to_from=get_post_meta($post_id, 'cn_post_translated_to_from',true);
				wp_nonce_field( 'omniglot_meta_cn_box', 'omniglot_meta_cn_box_nonce' );
			?>
			<p>
				<input type="hidden" name="cn_post_id" value="<?php echo esc_attr( $post_id ); ?>">
				<?php
				if (!empty($cn_post_translated_to)) {?>
					<label for="cn_post_translated_to"><?php esc_html_e( 'Translated in', 'omniglot' ); ?></label>
					<br>
					<?php 
					
					foreach ($cn_post_translated_to as $translated_to) {
						$cn_child_post_id=$translated_to['cn_child_post_id'];
						foreach ( omniglot_supported_languages() as $code => $name ) : 
							if ($code==$translated_to['translated_to']) {
								$cn_child_post=get_post($cn_child_post_id);
								if($cn_child_post->post_status=='publish' or $cn_child_post->post_status=='draft'){?>
									<li>
										<a target="_blank" href="<?php echo admin_url('post.php').'?post='.$cn_child_post_id; ?>&action=edit">
											<span><?php echo esc_html( $name ); ?></span>
										</a> -  <?php echo $post_statusArr[$cn_child_post->post_status]; ?>
									</li>
							<?php }
							 } ?>
					<?php endforeach; 
					} ?>
			</p>
				<?php } ?>
			<p>
				<?php

				
				if ($cn_post_translated_to_from) {?>
					<label for="cn_post_translated_to_from"><strong><?php esc_html_e( 'Translated to', 'omniglot' ); ?></strong></label>
					<br>
					<?php 
					foreach ($cn_post_translated_to_from as $translated_to_from) {
						if ($translated_to_from['translated_from']) {
							$cn_parent_post_id=$translated_to_from['cn_parent_post_id'];
							foreach ( omniglot_supported_languages() as $code => $name ) : 
						 	if ($code==$translated_to_from['translated_from']) {
						 		$cn_parent_post=get_post($cn_parent_post_id);
						 		if($cn_parent_post->post_status=='publish'){?>
						 			<a target="_blank" href="<?php echo admin_url('post.php').'?post='.$cn_parent_post_id; ?>&action=edit"><span><?php echo esc_html( $name ); ?></span></a>
							 	<?php }else{?>
							 		<span><?php echo esc_html( $name ); ?></span>
							 	<?php }
							 } ?>
						 	
						<?php endforeach; 
						}
						if ($translated_to_from['translated_to']) {
							foreach ( omniglot_supported_languages() as $code => $name ) : 
						 	if ($code==$translated_to_from['translated_to']) {?>
						 		<span>to</span>
						 		<span><?php echo esc_html( $name ); ?></span>
						 	<?php } ?>
						<?php endforeach; 
						}
					}
				} ?>
				</p>
			<p>
				<input type="checkbox" name="deepl_translated" cn="<?php echo $deepl_translated; ?>" id="deepl_translated" <?php if($deepl_translated==2){ echo 'checked';} ?>  value="<?php if($deepl_translated==1){ echo 2; } else{ echo 1; }?>">
				<label for="deepl_translated"><?php esc_html_e( 'Omniglot Translated', 'omniglot' ); ?></label>
			</p>
			<p>
				<button
					class="button button-primary omniglot-cn-save-btn"
				><?php esc_html_e( 'Save', 'omniglot' ); ?></button>
			</p>
			<div class="cn_spinner_div">
					<span class="cn_spinner omniglot-spinner"></span>
				</div>
		
		<?php
		}else{
			echo '<span style="color:red"> You have entered '.$verification['message'].'.<br>
			To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
		}
		}else{
			echo '<span style="color:red">To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
		}
		?>
		</div>
		<?php

	}
	public function meta_box_html() {
		?>
		<div class="omniglot-translator-meta-box">
			<?php
			$cn_license = get_option('cn_license');
		if ($cn_license=='activated') { 
		$verification=omniglot_license_key_verification();
		if ($verification['status']=='active') {
		
			$post_id = get_the_ID();

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'omniglot_meta_box', 'omniglot_meta_box_nonce' );
			?>
			<p>
				<label for="source_lang"><?php esc_html_e( 'Translate From', 'omniglot' ); ?>:</label>
				<select name="source_lang" id="source_lang">
					<option value=""><?php esc_html_e( 'Please select', 'omniglot' ); ?></option>
					<?php foreach ( omniglot_supported_languages() as $code => $name ) : ?>
						<option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $name ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="target_lang"><?php esc_html_e( 'Translate to', 'omniglot' ); ?>:</label>
				<select name="target_lang" id="target_lang" >
					<option value=""><?php esc_html_e( 'Please select', 'omniglot' ); ?></option>
					<?php foreach ( omniglot_supported_languages() as $code => $name ) : ?>
						<option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $name ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<input type="checkbox" name="translate_atts" id="translate_atts" checked>
				<label for="translate_atts"><?php esc_html_e( 'Should translate attributes', 'omniglot' ); ?></label>
			</p>
			<p>
				<input type="checkbox" name="translate_slug" id="translate_slug">
				<label for="translate_slug"><?php esc_html_e( 'Should translate slug', 'omniglot' ); ?></label>
			</p>
			<p>
				<input type="checkbox" name="translate_seo" id="translate_seo">
				<label for="translate_seo">
					<?php esc_html_e( 'Should translate SEO Meta Tags (Only Yoast)', 'omniglot' ); ?>
				</label>
			</p>
			<p>
				<input type="checkbox" name="cn_saveas" id="cn_saveas">
				<label for="cn_saveas">
					<?php esc_html_e( 'Save as draft', 'omniglot' ); ?>
				</label>
			</p>
			<!-- <p>
				<input type="checkbox" name="manual_translate" id="manual_translate">
				<label for="cn_saveas">
					<?php //esc_html_e( 'Manual Translate ', 'omniglot' ); ?>
				</label>
			</p> -->
			<p class="text-center">
				<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>">
				<input type="hidden" name="gutenberg_active" value="<?php echo esc_attr( omniglot_is_gutenberg_active() ); ?>">
				<button class="text-left button button-primary omniglot-translate-btn"
				><?php esc_html_e( 'Translate', 'omniglot' ); ?></button>
				<span>OR</span>
				<button class="text-right button button-primary omniglot-generate-new-btn"
				><?php esc_html_e( 'Generate new', 'omniglot' ); ?></button><br>
				<br>
				<button class="text-right button button-primary omniglot-generate-manual-btn"
				><?php esc_html_e( 'Manual Translate', 'omniglot' ); ?></button><br>
				<br>
				<br>
				<div class="cn_spinner_div">
					<span class="cn_spinner omniglot-spinner"></span>
					<?php /*
					<img style="height: 20px;
    width: 20px;" src="<?php echo OMNIGLOT_PLUGIN_URI.'../admin/img/loading.gif'; ?>" />
        			*/?>
    				<span class="cn_spinner_text">AI Translating...</span>
				</div>
				
			</p>
			<?php omniglot_get_error_message_wrapper(); ?>
		
		<?php
		}else{
			echo '<span style="color:red"> You have entered '.$verification['message'].'.<br>
			To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
		}
		}else{
			echo '<span style="color:red">To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
		}
		?>
		
		</div><?php

	}

	/**
	 * Process the translation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function process_translation_via_ajax() {
		check_ajax_referer( 'omniglot_meta_box', 'omniglot_nonce' );

		$success    = false;
		$error      = '';
		$translated = '';

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$post_id          = isset( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		$source_lang      = isset( $_POST['source_lang'] ) ? sanitize_text_field( $_POST['source_lang'] ) : '';
		$target_lang      = isset( $_POST['target_lang'] ) ? sanitize_text_field( $_POST['target_lang'] ) : '';
		$translate_atts   = isset( $_POST['translate_atts'] ) ? sanitize_text_field( $_POST['translate_atts'] ) : '';
		$translate_slug   = isset( $_POST['translate_slug'] ) ? sanitize_text_field( $_POST['translate_slug'] ) : '';
		$translate_seo    = isset( $_POST['translate_seo'] ) ? sanitize_text_field( $_POST['translate_seo'] ) : '';
		$gutenberg_active = true; //isset( $_POST['gutenberg_active'] ) ? sanitize_text_field( $_POST['gutenberg_active'] ) : '';
		// phpcs:enable

		$translate_atts = 'true' === $translate_atts ? true : false;
		$translate_slug = 'true' === $translate_slug ? true : false;
		$target_lang=$_POST['target_lang'][0];

		try {
			$translator = new Omniglot_Translator();
			$translator->set_source_lang( $source_lang );
			$translator->set_target_lang( $target_lang );
			$translator->set_translate_attributes( $translate_atts );
			$translator->set_translate_slug( $translate_slug );
			$translator->set_translate_seo( $translate_seo );

			if ( apply_filters( 'omniglot_should_save_translated_post_if_gutenberg_active', $gutenberg_active ) ) {
				// Translate and save the post.
				$translated = $translator->translate_post( $post_id );
				update_post_meta($post_id, 'translated_to', $target_lang);
				update_post_meta($post_id, 'translated_from', $source_lang);
				update_post_meta($post_id, 'cn_mylang', $target_lang);
			} else {
				// Only show the translation, user needs to update the post manually.
				$translated = $translator->get_translated_post( $post_id );
			}

			$translated = apply_filters(
				'omniglot_translated_data_after_finish_translation_via_ajax',
				$translated,
				$post_id
			);

			do_action( 'omniglot_after_finish_translation_via_ajax', $post_id );

			$success = true;
		} catch ( Exception $e ) {
			$success = false;
			$error   = $e->getMessage();
		}

		wp_send_json(
			array(
				'success'    => $success,
				'error'      => $error,
				'translated' => $translated,
			)
		);
	}
	public function process_generate_via_ajax() {
		check_ajax_referer( 'omniglot_meta_box', 'omniglot_nonce' );

		$success    = false;
		$error      = '';
		$translated = '';

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$post_id          = isset( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		$source_lang      = isset( $_POST['source_lang'] ) ? sanitize_text_field( $_POST['source_lang'] ) : '';
		// $target_langAll      = isset( $_POST['target_lang'] ) ? sanitize_text_field( $_POST['target_lang'] ) : '';
		$translate_atts   = isset( $_POST['translate_atts'] ) ? sanitize_text_field( $_POST['translate_atts'] ) : '';
		$translate_slug   = isset( $_POST['translate_slug'] ) ? sanitize_text_field( $_POST['translate_slug'] ) : '';
		$translate_seo    = isset( $_POST['translate_seo'] ) ? sanitize_text_field( $_POST['translate_seo'] ) : '';
		$gutenberg_active = isset( $_POST['gutenberg_active'] ) ? sanitize_text_field( $_POST['gutenberg_active'] ) : '';
		$cn_post_status = isset( $_POST['cn_post_status'] ) ? sanitize_text_field( $_POST['cn_post_status'] ) : '';
		$cn_manual_translate = isset( $_POST['cn_manual_translate'] ) ? sanitize_text_field( $_POST['cn_manual_translate'] ) : '';
		$translated_to = array();
		$cn_post_translated = array();

		// phpcs:enable
		$translate_atts = 'true' === $translate_atts ? true : false;
		$translate_slug = 'true' === $translate_slug ? true : false;
		try {
			$translator = new Omniglot_Translator();
			if ($_POST['target_lang']) {
				 $target_lang = $_POST['target_lang'] ;
				//foreach ($_POST['target_lang'] as $target_lang) 
				{
					$translator->set_source_lang( $source_lang );
					$translator->set_target_lang( $target_lang );
					$translator->set_translate_attributes( $translate_atts );
					$translator->set_translate_slug( $translate_slug );
					$translator->set_translate_seo( $translate_seo );
					if ($cn_manual_translate) {
							$content_post = get_post($post_id);
						$content = $content_post->post_content;
						$translated = wp_insert_post(array('post_title'=>'enter your text','post_status'=>'draft','post_type'=>'page','post_content'=>$content ));
					
					}else{
						$translated = $translator->generate_post($post_id,$cn_post_status);	
					}
					
					$cn_target_language = get_post_meta( $post_id, 'cn_post_translated_to', true );
					if ($cn_target_language) {
						$newArr[]=array('translated_to'=>$target_lang,'translated_from'=>$source_lang,'cn_child_post_id'=>$translated);
						$translated_to=array_merge($cn_target_language,$newArr);

							foreach ($translated_to as $cn_translated) {
								$alltranslated_to[]=$cn_translated['translated_to'];
							}
							$Match=0;
							foreach (omniglot_supported_languages() as $code => $name ) {
								if (in_array($code, $alltranslated_to))
								  {
								  	$Match++;
								  }
							}
							if ($Match>=8) {
								update_post_meta($post_id, 'deepl_translated', 2 );
							}
					}else{
						$translated_to[]= array('translated_to'=>$target_lang,'translated_from'=>$source_lang,'cn_child_post_id'=>$translated);

					}
					$cn_post_translated[]=array('translated_from'=>$source_lang,'translated_to'=>$target_lang,'cn_parent_post_id'=>$post_id);
	global $wpdb;
				$custom_fields = get_post_custom(  $post_id  );
   foreach ( $custom_fields as $key => $value ) {
	  if( is_array($value) && count($value) > 0 ) {
			foreach( $value as $i=>$v ) {
				$result = $wpdb->insert( $wpdb->prefix.'postmeta', array(
					'post_id' => $translated,
					'meta_key' => $key,
					'meta_value' => $v
				));
			}
		}
    }
					update_post_meta($translated, 'cn_post_translated_to_from', $cn_post_translated);
					update_post_meta($post_id, 'cn_post_translated_to', $translated_to);

					update_post_meta($translated, 'translated_from', $target_lang);
					update_post_meta($post_id, 'translated_to', $source_lang);

					update_post_meta($translated, 'cn_mylang', $target_lang);
					update_post_meta($post_id, 'cn_mylang', $source_lang);


					update_post_meta($translated, 'deepl_translated', 2 );
					$translated = apply_filters('omniglot_translated_data_after_finish_translation_via_ajax',$translated,$post_id);
					do_action( 'omniglot_after_finish_translation_via_ajax', $post_id );
					unset($translated_to);
					unset($cn_post_translated);
					unset($newArr);
					unset($alltranslated_to);
					$success = true;
				}
			}else{
				$success = false;
				$error   = 'Select at least one target language';
			}


		} catch ( Exception $e ) {
			$success = false;
			$error   = $e->getMessage();
		}

		wp_send_json(
			array(
				'success'    => $success,
				'error'      => $error,
				'translated' => $translated,
				'translated_to'=>$translated_to,
				'cn_post_translated'=>$cn_post_translated,
				'saveas'=>$cn_post_status,
			)
		);
	}


	public function dpl_translated_via_ajax() {
		check_ajax_referer( 'omniglot_meta_cn_box', 'omniglot_nonce' );

		$success    = false;
		$error      = '';
		$post_id          = isset( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		$deepl_translated   = isset( $_POST['deepl_translated'] ) ?  $_POST['deepl_translated'] : '';
		try {
			if (update_post_meta($post_id, 'deepl_translated',$deepl_translated)) {
				$success = true;
			}
		} catch ( Exception $e ) {
			$success = false;
			$error   = $e->getMessage();
		}
		wp_send_json(
			array(
				'success'    => $success,
				'error'      => $error,
				'deepl_translated'=>$deepl_translated
			)
		);
	}

}

Omniglot_Meta_Box::get_instance();