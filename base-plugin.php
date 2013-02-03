<?php
/*
Plugin Name: Base Plugin
Plugin URI: http://upthemes.com
Description: A common codebase that can be used to quickly create a new WordPress plugin.
Version: 0.1.3
Author: Chris Wallace of UpThemes
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
	 * @var $name	Variable for Base_Plugin used throughout the plugin
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

		// Localize our plugin
		add_action('init',array(&$this,'localization_setup'));

		// Set up admin-specific scripts
		add_action('admin_menu',array($this,'menu_setup'));

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
	 * Creates admin page and enqueues our admin_scripts hook
	 *
	 * @uses add_menu_page()
	 * @uses add_action()
	 *
	 */
	public function menu_setup() {
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

		/**
		 * Example for enqueueing our plugin styles
		 *
		 * Uncomment line below and replace with assets specific to your plugin.
		 */
		wp_enqueue_script('baseplugin-scripts', plugins_url( 'scripts/baseplugin.js', BASEPLUGIN_FILE ), array('jquery'), date( 'Ymd' ) );


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

		/*
		* Example for enqueueing our plugin styles
		*
		* Uncomment line below and replace with assets specific to your plugin.
		*/

		wp_enqueue_style('baseplugin-styles', plugins_url( 'styles/baseplugin.css', BASEPLUGIN_FILE ), false, date( 'Ymd' ) );

	}

	/**
	 * Output plugin page header
	 *
	 */
	public function plugin_page_header(){ ?>
<div class="icon32" id="baseplugin-icon"><br></div>
		<h2><?php echo sprintf( __('%s','baseplugin'), $this->name ); ?></h2>
<?php
	}

	/**
	 * Output plugin page contents
	 *
	 * This is where you should output your plugin settings page.
	 */
	public function plugin_page_settings(){ ?>

	<div class="wrap">

		<?php $this->plugin_page_header(); ?>

		<p><?php _e("This is a sample plugin page.","baseplugin"); ?></p>

	</div>
<?php
	}

}

$baseplugin = Base_Plugin::init();