<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://notice.studio
 * @since      1.0.0
 *
 * @package    Noticefaq
 * @subpackage Noticefaq/includes
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
 * @package    Noticefaq
 * @subpackage Noticefaq/includes
 */
class Noticefaq {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Noticefaq_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'NOTICEFAQ_VERSION' ) ) {
			$this->version = NOTICEFAQ_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'noticefaq';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		add_action('wp_head', array($this, 'enqueue_noticefaq_scripts'),100);
		add_action('admin_menu', array($this, 'add_menu_page_notice'));
		add_shortcode('noticefaq', array( $this , 'noticefaq_shortcode' ));

	}
    public function add_menu_page_notice() {        
		add_menu_page( 'Notice FAQ', 'Notice FAQ', 'manage_options', 'noticefaq', array( $this , 'noticefaq_init' ),'dashicons-lightbulb', 10 );
    }
	public function enqueue_noticefaq_scripts(){
		wp_enqueue_script('noticefaq-js', 'https://bundle.notice.studio/index.js');
		wp_enqueue_style('noticefaq-css', 'https://bundle.notice.studio/index.css');
		
	}
	public function noticefaq_init(){
    echo '
	<div class="outerCont">
	<h3>👋 Welcome to Notice FAQ / Documentation / Blog Dashboard.</h3>
	<br>
	<img src="'.plugin_dir_url( __DIR__ ).'assets/images/Notice.png'.'" width="100%" alt="notice" />
	<ul>
	<li><p>Create your own FAQ, Documentation or Blog at <a href="https://notice.studio"  target="_blank">Notice.studio</a></p></li>
	<li><p>Your newly created project ID is available in <b>Deploy</b> section 🚀</p></li>
	<li><p>Display all your creations on your website using the below shortcode:</p></li>
	<li><p>[noticefaq projectid="your-project-id"]</p></li>
	</ul>
	<br>
	<p>Created by <a href="https://notice.studio" target="_blank">Notice.studio</a></p>
	</div>
	';
}
public function noticefaq_shortcode( $atts, $content = null)	{
	extract( shortcode_atts( array(
				'projectid' => ''
			), $atts 
		) 
	);
	// this will display our message before the content of the shortcode
	return '<div class="notice-target-container" project-id="'.$projectid.'"></div>';
}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Noticefaq_Loader. Orchestrates the hooks of the plugin.
	 * - Noticefaq_i18n. Defines internationalization functionality.
	 * - Noticefaq_Admin. Defines all hooks for the admin area.
	 * - Noticefaq_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-noticefaq-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-noticefaq-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-noticefaq-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-noticefaq-public.php';

		$this->loader = new Noticefaq_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Noticefaq_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Noticefaq_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Noticefaq_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Noticefaq_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Noticefaq_Loader    Orchestrates the hooks of the plugin.
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

}
