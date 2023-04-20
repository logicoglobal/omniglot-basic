<?php

/**
 * Omniglot Settings Page
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

/**
 * Omniglot Settings Page class.
 *
 * @since      1.0.0
 * @package    Omniglot
 * @subpackage Omniglot/includes
 * @author     Omniglot <contact@mainulhassan.info>
 */
class Omniglot_Settings_Page {

	/**
	 * Omniglot options.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $omniglot_options;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'omniglot_add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'omniglot_page_init' ) );
	}

	/**
	 * Adds the menu page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function omniglot_add_menu_page() {
		add_options_page(
			esc_html__( 'Omniglot', 'omniglot' ), // Page title.
			esc_html__( 'Omniglot', 'omniglot' ), // Menu title.
			'manage_options', // Capability.
			'omniglot', // Menu slug.
			array( $this, 'omniglot_create_admin_page' ) // Callback function.
		);
	}

	/**
	 * Gets the settings page url.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_settings_page_url() {
		return menu_page_url( 'omniglot', false );
	}

	/**
	 * Gets the tab item url.
	 *
	 * @since 1.0.0
	 *
	 * @param string $item The tab item slug.
	 *
	 * @return string
	 */
	public function get_tab_item_url( $item ) {
		return add_query_arg( 'tab', $item, $this->get_settings_page_url() );
	}

	/**
	 * Gets the tab item class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $item The tab item slug.
	 *
	 * @return string
	 */
	public function get_tab_item_class( $item ) {
		$tabs = $this->get_tabs();
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
		// phpcs:enable
		$class = 'nav-tab';

		reset( $tabs );
		$first_item = key( $tabs );

		if ( ( ! $tab && is_array( $tabs ) && $item === $first_item ) || $item === $tab ) {
			$class .= ' nav-tab-active';
		}

		return $class;
	}

	/**
	 * Gets the tabs and the fields.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_tabs() {
		return apply_filters(
			'omniglot_settings_page_tabs',
			array(
				'general'           => array(
					'title'      => esc_html__( 'General', 'omniglot' ),
					'submit_btn' => true,
					'fields'     => array(
						array(
							'id'          => 'deepl_api_key',
							'title'       => esc_html__( 'DeepL API Key', 'omniglot' ),
							'type'        => 'text',
							'placeholder' => '',
						),
						array(
							'id'          => 'omniglot-license',
							'title'       => esc_html__( 'Omniglot License', 'omniglot' ),
							'type'        => 'text',
							'placeholder' => '',
						),
						array(
							'id'          => 'show_watermark',
							'title'       => esc_html__( 'Show the watermark', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
					),
				),
				'translate-content' => array(
					'title'  => esc_html__( 'Translate bulk contents', 'omniglot' ),
					'fields' => array(
						array(
							'id'          => 'post_type',
							'title'       => esc_html__( 'Content type', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => esc_html__( 'Select post type', 'omniglot' ),
							'options'     => omniglot_get_translatable_post_types(),
						),
						array(
							'id'          => 'source_language',
							'title'       => esc_html__( 'Source Language', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => '',
							'options'     => array_merge(
								array( 'auto' => esc_html__( 'Automatic', 'omniglot' ) ),
								omniglot_supported_languages()
							),
						),
						array(
							'id'          => 'target_language',
							'title'       => esc_html__( 'Target Language', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => '',
							'options'     => omniglot_supported_languages(),
						),
						
						array(
							'id'          => 'translate_atts',
							'title'       => esc_html__( 'Should translate attributes', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'          => 'translate_slug',
							'title'       => esc_html__( 'Should translate slug', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'          => 'translate_seo',
							'title'       => esc_html__( 'Should translate SEO Meta Tags (Only Yoast)', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'          => 'override_translation',
							'title'       => esc_html__( '*The new translations will override the previous ones', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'    => 'translate_posts_btn',
							'title' => esc_html__( 'Translate', 'omniglot' ),
							'type'  => 'btn',
						),
					),
				),
				'generate-new-content' => array(
					'title'  => esc_html__( 'Generate new contents', 'omniglot' ),
					'fields' => array(
						array(
							'id'          => 'post_type',
							'title'       => esc_html__( 'Content type', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => esc_html__( 'Select post type', 'omniglot' ),
							'options'     => omniglot_get_translatable_post_types(),
						),
						array(
							'id'          => 'source_language',
							'title'       => esc_html__( 'Source Language', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => '',
							'options'     => array_merge(
								array( 'auto' => esc_html__( 'Automatic', 'omniglot' ) ),
								omniglot_supported_languages()
							),
						),
						array(
							'id'          => 'target_language',
							'title'       => esc_html__( 'Target Language', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => '',
							'options'     => omniglot_supported_languages(),
						),
						
						array(
							'id'          => 'translate_atts',
							'title'       => esc_html__( 'Should translate attributes', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'          => 'translate_slug',
							'title'       => esc_html__( 'Should translate slug', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'          => 'translate_seo',
							'title'       => esc_html__( 'Should translate SEO Meta Tags (Only Yoast)', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'          => 'cn_post_status',
							'title'       => esc_html__( 'Save as draft', 'omniglot' ),
							'type'        => 'checkbox',
							'placeholder' => '',
						),
						array(
							'id'    => 'generate_posts_btn',
							'title' => esc_html__( 'Generate new content', 'omniglot' ),
							'type'  => 'btn',
						),
					),
				),
				'appearance' => array(
					'title'  => esc_html__( 'Language Switcher', 'omniglot' ),
					'submit_btn' => true,
					'fields' => array(
						array(
							'id'          => 'appearance',
							'title'       => esc_html__( 'Language Switcher Design', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => esc_html__( 'Default (All icons)', 'omniglot' ),
							'options'     => '',
						),
						array(
							'id'          => 'page_open_tab',
							'title'       => esc_html__( 'Page Open In', 'omniglot' ),
							'type'        => 'select',
							'placeholder' => esc_html__( 'Current Window', 'omniglot' ),
							'options'     => '',
						),
					),
				),
			)
		);
	}

	/**
	 * Format the tabs.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function format_tabs() {
		?>
		<nav class="nav-tab-wrapper">
			<?php foreach ( $this->get_tabs() as $name => $data ) : ?>
				<a
					href="<?php echo esc_url( $this->get_tab_item_url( $name ) ); ?>"
					class="<?php echo esc_attr( $this->get_tab_item_class( $name ) ); ?>"
				><?php echo esc_html( $data['title'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * Format the progress bar.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function format_progress_bar() {
		?>
		<div class="omniglot-translation-progress">
			<table class="progress-table">
				<tr>
					<td class="progressbar-column">
						<div class="progressbar">
							<div></div>
						</div>
					</td>
					<td class="progress-info">
						<?php
						// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
						echo omniglot_get_placeholder_markup_for_total_translated_posts();
						// phpcs:enable
						?>
					</td>
				</tr>
			</table>
			<p class="omniglot-success-message"></p>
		</div>
		<?php
	}

	/**
	 * Adds the settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function omniglot_create_admin_page() {
		$this->omniglot_options = get_option( 'omniglot_options' );
		?>
		<div class="wrap">
			<h2></h2>

			<?php $this->format_tabs(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'omniglot_option_group' );

				$tabs = $this->get_tabs();
				// phpcs:disable WordPress.Security.NonceVerification.Recommended
				// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
				// phpcs:enable
				$data = $tab ? $tabs[ $tab ] : array();

				if ( ! $tab ) {
					reset( $tabs );
					$tab  = key( $tabs );
					$data = current( $tabs );
				}

				do_settings_sections( 'omniglot-settings-' . $tab );

				if ( isset( $data['submit_btn'] ) && true === $data['submit_btn'] ) {
					submit_button();
				}
				?>
			</form>
			<?php
			omniglot_get_error_message_wrapper();
			$this->format_progress_bar();
			?>
		</div>
		<?php
	}

	/**
	 * Adds the settings sections.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_omniglot_settings_sections() {
		foreach ( $this->get_tabs() as $name => $data ) {
			add_settings_section(
				'omniglot_setting_' . $name, // Id.
				'', // Title.
				'', // Callback.
				'omniglot-settings-' . $name // Page.
			);
		}
	}

	/**
	 * Adds the settings fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_omniglot_settings_fields() {
		foreach ( $this->get_tabs() as $name => $data ) {
				foreach ( $data['fields'] as $field ) {
					$field_callback = apply_filters(
						'omniglot_get_' . $field['type'] . '_field_type','get_' . $field['type'] . '_field_type'
					);

					$title = $field['title'];

					if ( 'btn' === $field['type'] ) {
						$title = '';
					}

					$title = apply_filters( 'omniglot_setting_field_title', $title, $field );

					add_settings_field(
						$field['id'], // Id.
						$title, // Title.
						array( $this, $field_callback ), // Callback.
						'omniglot-settings-' . $name, // Page.
						'omniglot_setting_' . $name, // Section.
						$field
					);
				}

		}
	}

	/**
	 * Initialize settings fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function omniglot_page_init() {
		register_setting(
			'omniglot_option_group', // Option group.
			'omniglot_options', // Option name.
			array( $this, 'omniglot_sanitize' ) // Sanitize callback function.
		);

		$this->add_omniglot_settings_sections();

		$this->add_omniglot_settings_fields();
	}

	/**
	 * Sanitizes the user inputs.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input The user submitted data.
	 *
	 * @return array
	 */
	public function omniglot_sanitize( $input ) {
		$sanitary_values = array();

		foreach ( $this->get_tabs() as $tab ) {
			foreach ( $tab['fields'] as $field ) {
				$name = $field['id'];
				$type = $field['type'];

				if ( 'checkbox' === $type ) {
					if ( isset( $input[ $name ] ) ) {
						$sanitary_values[ $name ] = sanitize_text_field( $input[ $name ] );
					} else {
						$sanitary_values[ $name ] = 'off';
					}
				} else {
					if ( isset( $input[ $name ] ) ) {
						$sanitary_values[ $name ] = sanitize_text_field( $input[ $name ] );
					}
				}
			}
		}
		$sanitary_values[ 'show_watermark' ] = 'on';
		$new_options      = array();
		$omniglot_options = get_option( 'omniglot_options' );
		$omniglot_options = $omniglot_options ? get_option( 'omniglot_options' ) : array();

		foreach ( $sanitary_values as $key => $value ) {
			$new_options[ $key ] = $value;
		}

		foreach ( $omniglot_options as $key => $value ) {
			if ( ! array_key_exists( $key, $new_options ) ) {
				$new_options[ $key ] = $value;
			}
		}

		return $new_options;
	}

	/**
	 * Gets the text field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_text_field_type( $field ) {
		 $id= $field['id'];

		if ($id=='omniglot-license') {
			 $cn_license = get_option('cn_license');
			if ($cn_license=='inactive') {
				if ($this->omniglot_options[$id]) {
			  		$license_key=$this->omniglot_options[$id];
				    // define('CN_SPECIAL_SECRET_KEY', '5d97b1ad7c4e55.89621425');
    				// define('CN_LICENSE_SERVER_URL', 'http://demo.coderninja.in/omniglot');
				    // define('YOUR_ITEM_REFERENCE', 'My First Plugin');
			        $api_params = array(
			            'slm_action' => 'slm_activate',
			            'secret_key' => CN_SPECIAL_SECRET_KEY,
			            'license_key' => $license_key,
			            'registered_domain' => $_SERVER['SERVER_NAME'],
			            'item_reference' => urlencode(YOUR_ITEM_REFERENCE),
			        );
			        $query = esc_url_raw(add_query_arg($api_params, CN_LICENSE_SERVER_URL));
			        $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
			        $license_data = json_decode(wp_remote_retrieve_body($response));
			        if($license_data->result == 'success'){
			            echo  '<span style="color: #1800ff;font-weight: bold;">'.$license_data->message.'</span>';
			            update_option('cn_license', 'activated');
			        }
			        else{
			            echo '<span style="color:red;font-weight: bold;">'.$license_data->message.'</span>';
			             update_option('cn_license', 'inactive');
			        }
				}
			}
		}

		$value       = isset( $this->omniglot_options[ $id ] ) ? $this->omniglot_options[ $id ] : '';
		$desc        = isset( $field['desc'] ) ? $field['desc'] : '';
		$required    = isset( $field['required'] ) ? $field['required'] : '';
		$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		?>
		<input
			type="text"
			class="regular-text"
			name="omniglot_options[<?php echo esc_attr( $id ); ?>]"
			id="<?php echo esc_attr( $id ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo $required ? 'required="required"' : ''; ?>
			<?php echo $placeholder ? 'placeholder="' . esc_attr( $placeholder ) . '"' : ''; ?>
		>

		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Gets the select field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_select_field_type( $field ) {
		$id          = $field['id'];
		$options     = isset( $field['options'] ) ? $field['options'] : array();
		$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		$desc        = isset( $field['desc'] ) ? $field['desc'] : '';
		$value       = isset( $this->omniglot_options[ $id ] ) ? $this->omniglot_options[ $id ] : '';
		$required    = isset( $field['required'] ) ? $field['required'] : '';
		?>
		<select name="omniglot_options[<?php echo esc_attr( $id ); ?>]" id="<?php echo esc_attr( $id ); ?>" <?php echo $required ? 'required="required"' : ''; ?> >
			<?php if ( $placeholder ) : ?>
				<option value=""><?php echo esc_html( $placeholder ); ?></option>
			<?php endif; ?>

			<?php foreach ( $options as $key => $title ) : ?>
				<option
					value="<?php echo esc_attr( $key ); ?>"
					<?php selected( $value, $key ); ?>
				><?php echo esc_html( $title ); ?></option>
			<?php endforeach; ?>
		</select>

		<?php if ( 'post_type' === $field['id'] ) : ?>
			<span class="spinner omniglot-spinner" style="float: none;"></span>
		<?php endif; ?>

		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>

		<?php if ( 'post_type' === $field['id'] ) : ?>
			<div class="omniglot-dynamic-taxonomy-filter-wrapper"></div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Gets the btn field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_btn_field_type( $field ) {
		$id    = $field['id'];
		$title = $field['title'];
		?>
		<div class="mylod" id="mylod" style="">
		    <div class="mylodsub">
		        <!-- <img class="spinner1_img" src="<?php// echo OMNIGLOT_PLUGIN_URI ?>../admin/img/spinner1.gif" style=""> -->
		        <p id="cnmsg1" style="margin: 0;color: #000;font-size: 18px;">AI Translating...<br>Check the status below</p>
		    </div>
    	</div>
    	<?php
    	$cn_license = get_option('cn_license');
    	$verification=omniglot_license_key_verification();
			if ('translate_posts_btn' === $id) {
				if ($cn_license=='inactive') { 
					echo '<span style="color:red">To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
				}elseif ($verification['status']!='active') {
					echo '<span style="color:red"> You have entered '.$verification['message'].'.<br>To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
				}else{ ?>
					<button class="button button-primary" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></button>
				<?php }
			}elseif ('generate_posts_btn' === $id) {
				if ($cn_license=='inactive') { 
					echo '<span style="color:red">To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
				}elseif ($verification['status']!='active') {
					echo '<span style="color:red"> You have entered '.$verification['message'].'.<br>To use this option please enter the license key first. <a href="'.admin_url('options-general.php').'?page=omniglot">Omniglot settings</a></span>';
				}else{ ?>
					<button class="button button-primary" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></button>
				<?php }
			}?>
		
		<?php
		if ( 'translate_posts_btn' === $id ) {
			wp_nonce_field( 'omniglot_process_translation', '_omniglot_nonce', false );
		}
		if ( 'generate_posts_btn' === $id ) {
			wp_nonce_field( 'omniglot_process_translation', '_omniglot_nonce', false );
		}
	}

	/**
	 * Gets the checkbox field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_checkbox_field_type( $field ) {
		$id       = $field['id'];
		$value    = isset( $this->omniglot_options[ $id ] ) ? $this->omniglot_options[ $id ] : '';
		$required = isset( $field['required'] ) ? $field['required'] : '';
		?>
		<input
			type="checkbox"
			name="omniglot_options[<?php echo esc_attr( $id ); ?>]"
			id="<?php echo esc_attr( $id ); ?>"
			<?php checked( $value, 'on' ); ?>
			<?php if( $id ==  'show_watermark' ){
				echo 'disabled="disabled"';
			} ?>
			<?php echo $required ? 'required="required"' : ''; ?>
		>
		<?php
	}

}

if ( is_admin() ) {
	new Omniglot_Settings_Page();
}
