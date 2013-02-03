<?php
/*
Plugin Name: Base Plugin
Plugin URI: http://upthemes.com
Description: A common codebase that can be used to quickly create a new WordPress plugin.
Version: 0.1
Author: Chris Wallace / UpThemes
Author URI: http://upthemes.com
License: GPL2
*/

// don't call the file directly
if ( !defined( 'ABSPATH' ) )
	return;

$baseplugin_file = __FILE__;

/* Find our plugin, wherever it may live! */
if (isset( $plugin) ) {
	$baseplugin_file = $plugin;
}
else if ( isset( $mu_plugin ) ) {
	$baseplugin_file = $mu_plugin;
}
else if ( isset( $network_plugin ) ) {
	$baseplugin_file = $network_plugin;
}

define('BASEPLUGIN_FILE', $baseplugin_file);
define('BASEPLUGIN_PATH', WP_PLUGIN_DIR.'/'.basename( dirname( $baseplugin_file ) ) );

/**
 * Base_Plugin class
 *
 * @class Base_Plugin	The class that holds the entire Base_Plugin plugin
 */
class Base_Plugin {

	/**
	 * @var $name				Variable for Base_Plugin used throughout the plugin
	 */
	protected $name = "Base Plugin";

	/**
	 * @var $nonce_key	A security key used internally by the plugin
	 */
	protected $nonce_key = '+Y|*Ec/-\s3';

	/**
	 * PHP 5.3 and lower compatibility
	 *
	 * @uses Base_Plugin::__construct()
	 *
	 */
	public function Base_Plugin(){
		$this->__construct();
	}

	/**
	 * Constructor for the Base_Plugin class
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 *
	 * @uses register_activation_hook()
	 * @uses register_deactivation_hook()
	 * @uses is_admin()
	 * @uses add_action()
	 *
	 */
	public function __construct() {
		register_activation_hook( BASEPLUGIN_FILE, array(&$this, 'activate' ) );
		register_deactivation_hook( BASEPLUGIN_FILE, array(&$this, 'deactivate' ) );

		add_action('init',array(&$this,'localization_setup'));

		/**
		 *
		 *
		 */
		// add_action( 'admin_print_styles-' . $hook, array($this,'admin_styles'));

		if ( is_admin() ){
			// Set up admin-specific scripts
			add_action('admin_menu',array($this,'admin_menu'));
		}else{
			// Set up theme-specific scripts
		}
	}

	/**
	 * Initializes the Base_Plugin() class
	 *
	 * Checks for an existing Base_Plugin() instance
	 * and if it doesn't find one, creates it.
	 *
	 * @uses Base_Plugin()
	 *
	 */
	public function &init() {
		static $instance = false;

		if ( !$instance ) {
			$instance = new Base_Plugin();
		}

		return $instance;
	}

	/**
	 * Placeholder for activation function
	 *
	 * Nothing being called here yet.
	 */
	public function activate() {

	}

	/**
	 * Placeholder for deactivation function
	 *
	 * Nothing being called here yet.
	 */
	public function deactivate() {

	}

	/**
	 * Initialize plugin for localization
	 *
	 * @uses load_plugin_textdomain()
	 *
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'baseplugin', false, dirname( plugin_basename( BASEPLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Initialize admin menu
	 *
	 * @uses Base_Plugin::load_menu()
	 *
	 */
	public function admin_menu() {
		$this->load_menu();
	}

	/**
	 * Creates admin page and enqueues our admin_scripts hook
	 *
	 * @uses Base_Plugin::load_menu()
	 *
	 */
	public function load_menu() {
		$hook = add_menu_page( $this->name, $this->name, 'manage_options', 'baseplugin', array(&$this, 'plugin_page_settings' ), plugins_url( 'images/menu.png', BASEPLUGIN_FILE ) );
		add_action( 'admin_print_styles-' . $hook, array($this,'admin_styles'));
		add_action( 'admin_print_scripts-' . $hook, array($this,'admin_scripts'));
	}

	/**
	 * Enqueue admin scripts
	 *
	 * Allows plugin assets to be loaded.
	 *
	 * @uses wp_enqueue_script()
	 * @uses wp_localize_script()
	 *
	 */
	public function admin_scripts() {
		if ( !current_user_can( 'manage_options' ) )
			return;

		/**
		 * Example for enqueueing our plugin styles
		 *
		 * Uncomment line below and replace with assets specific to your plugin.
		 */
		wp_enqueue_script('base-plugin-scripts', plugins_url( 'scripts/base-plugin.js', BASEPLUGIN_FILE ), array('jquery'), date( 'Ymd' ) );


		/**
		 * Example for setting up text strings from Javascript files for localization
		 *
		 * Uncomment line below and replace with proper localization variables.
		 */
		// $translation_array = array( 'some_string' => __( 'Some string to translate', 'baseplugin' ), 'a_value' => '10' );
		// wp_localize_script( 'base-plugin-scripts', 'baseplugin', $translation_array ) );

	}

	/**
	 * Enqueue admin styles
	 *
	 * Allows plugin assets to be loaded.
	 *
	 * @uses wp_enqueue_style()
	 *
	 */
	public function admin_styles() {
		if ( !current_user_can( 'manage_options' ) )
			return;

		/*
		* Example for enqueueing our plugin styles
		*
		* Uncomment line below and replace with assets specific to your plugin.
		*/

		wp_enqueue_style('base-plugin-styles', plugins_url( 'styles/base-plugin.css', BASEPLUGIN_FILE ), false, date( 'Ymd' ) );

	}

	public function plugin_page_header(){
		echo '<div class="icon32" id="baseplugin-icon"><br></div>';
		echo "<h2>" . sprintf( __('%s Settings','baseplugin'), $this->name ) . "</h2>";
	}

	public function plugin_page_settings(){
		$this->plugin_page_header();

		// Print your plugin page settings
		echo "This is a sample plugin page.";

	}

}

$baseplugin = Base_Plugin::init();