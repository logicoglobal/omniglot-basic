<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Omniglot
 * @subpackage Omniglot/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Omniglot
 * @subpackage Omniglot/includes
 * @author     Omniglot <contact@mainulhassan.info>
 */
class Omniglot {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Omniglot_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'OMNIGLOT_VERSION' ) ) {
			$this->version = OMNIGLOT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'omniglot';

		$this->define_constants();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Omniglot_Loader. Orchestrates the hooks of the plugin.
	 * - Omniglot_i18n. Defines internationalization functionality.
	 * - Omniglot_Admin. Defines all hooks for the admin area.
	 * - Omniglot_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-omniglot-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-omniglot-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-omniglot-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-omniglot-public.php';

		/**
		 * The class responsible for settings page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-omniglot-settings-page.php';

		/**
		 * The class responsible for translating the content.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-omniglot-translator.php';

		/**
		* The class responsible for truncating or splitting the content.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-omniglot-truncate-html.php';

		/**
		 * The class responsible for adding post meta box.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-omniglot-meta-box.php';

		/**
		 * The class responsible for translating posts.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-omniglot-posts-translator.php';

		/**
		 * The helper functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper-functions.php';

		$this->loader = new Omniglot_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Omniglot_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Omniglot_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Define constants for this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function define_constants() {
		$this->define( 'OMNIGLOT_PLUGIN_PATH', plugin_dir_path( dirname( __FILE__ ) ) );
		$this->define( 'OMNIGLOT_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
		$this->define('CN_PLUGIN_SECRET_KEY', '5d9c52fc470911.78320758');
    	$this->define('CN_LICENSE_SERVER_URL', 'https://omniglot.ai');
		$this->define('CN_SPECIAL_SECRET_KEY', '5d9c52fc470999.64812709');
	    $this->define('YOUR_ITEM_REFERENCE', get_site_url());
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Omniglot_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'save_post', $plugin_admin, 'my_project_updated' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'cn_register_custom_widget' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Omniglot_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_meta_tags',1,2 );
		$this->loader->add_action( 'pre_get_posts', $plugin_public ,'cn_posts_custom' );

		$this->loader->add_action( 'wp_ajax_cn_public_omniglot_ajax', $plugin_public, 'cn_ajax_handaler' );
		$this->loader->add_action( 'wp_ajax_nopriv_cn_public_omniglot_ajax', $plugin_public, 'cn_ajax_handaler' );

		// add_filter //
		$this->loader->add_filter( 'the_content', $plugin_public, 'cn_slug_filter_the_title' );

		remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
		$this->loader->add_action('woocommerce_single_product_summary', $plugin_public , 'woocommerce_single_title_lang',5);


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Omniglot_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Define constants if not already defined.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $name The name of constant.
	 * @param string|bool $value The value of constant.
	 */
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.VariableConstantNameFound
			define( $name, $value );
			// phpcs:enable
		}
	}

}
