<?php
/**
 * Main class of this plugin.
 *
 * @package    ElegantCRM
 * @subpackage Classes
 * @author     Rahul Aryan <rah12@live.com>
 * @since      1.0.0
 */
namespace ElegantCRM;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The main plugin class.
 *
 * @since 1.0.0
 */
class Plugin {
	/**
	 * Version of the plugin.
	 *
	 * @var string
	 */
	const VER = '1.0.0';

	/**
	 * Plugin options key.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'elegant-crm';

	/**
	 * Database version.
	 *
	 * @var string
	 */
	const DB_VER = 1;

	/**
	 * Instance of this class.
	 *
	 * @var Points
	 */
	private static $instance = null;

	/**
	 * Plugin actions.
	 *
	 * @var array
	 */
	private $actions = [];

	/**
	 * Plugin filters.
	 *
	 * @var array
	 */
	private $filters = [];

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	public $plugin_dir = '';

	/**
	 * Url to plugin DIR.
	 *
	 * @var string
	 */
	public $plugin_dir_url = '';

	/**
	 * Options of this plugin.
	 *
	 * @var array
	 */
	public $options;

	/**
	 * Get the singleton instance of this class.
	 *
	 * @return Points
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->load_instances();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	private function __construct() {
		$this->plugin_dir     = wp_normalize_path( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR;
		$this->plugin_dir_url = plugin_dir_url( dirname( __FILE__ ) );

		$this->load_dependencies();
		$this->get_options();
		$this->initial_hooks();
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string            $hook          The name of the WordPress action that is being registered.
	 * @param object            $component     A reference to the instance of the object on which the action is defined.
	 * @param string            $callback      The name of the function definition on the $component.
	 * @param int      Optional $priority      The priority at which the function should be fired.
	 * @param int      Optional $accepted_args The number of arguments that should be passed to the $callback.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string            $hook          The name of the WordPress filter that is being registered.
	 * @param object            $component     A reference to the instance of the object on which the filter is defined.
	 * @param string            $callback      The name of the function definition on the $component.
	 * @param int      Optional $priority      The priority at which the function should be fired.
	 * @param int      Optional $accepted_args The number of arguments that should be passed to the $callback.
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @param array             $hooks         The collection of hooks that is being registered (that is, actions or filters).
	 * @param string            $hook          The name of the WordPress filter that is being registered.
	 * @param object            $component     A reference to the instance of the object on which the filter is defined.
	 * @param string            $callback      The name of the function definition on the $component.
	 * @param int      Optional $priority      The priority at which the function should be fired.
	 * @param int      Optional $accepted_args The number of arguments that should be passed to the $callback.
	 * @param integer           $priority      Priority.
	 * @param integer           $accepted_args Accepted arguments.
	 *
	 * @return type The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);

		return $hooks;
	}

	/**
	 * Register the filters and actions with WordPress.
	 */
	public function setup_hooks() {
		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}

	/**
	 * Return version of this plugin.
	 *
	 * @return string
	 */
	public function get_ver() {
		return self::VER;
	}

	/**
	 * Return version of database of this plugin.
	 *
	 * @return string
	 */
	public function get_db_ver() {
		return self::DB_VER;
	}

	/**
	 * Get all options.
	 *
	 * @return void
	 */
	private function get_options() {
		$this->options = get_option( self::OPTION_NAME, [] );
		$this->options = wp_parse_args( $this->options, get_default_options() );
	}

	/**
	 * Load translations.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'elegant-crm' );
		$loaded = load_textdomain( 'elegant-crm', trailingslashit( WP_LANG_DIR ) . "elegant-crm/elegant-crm-{$locale}.mo" );

		if ( $loaded ) {
			return $loaded;
		} else {
			load_plugin_textdomain( 'elegant-crm', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}
	}

	/**
	 * Launch the rocket.
	 */
	public static function launch() {
		/**
		 * Action before loading CafePoints plugin.
		 */
		do_action( 'before_loading_elegant_crm' );

		elegant_crm()->setup_hooks();
	}

	/**
	 * Require once for plugin.
	 *
	 * @param string $file File to include which is under plugin directory.
	 * @since 1.0.0
	 */
	public function require_file( $file ) {
		require_once wp_normalize_path( $this->plugin_dir . $file . '.php' );
	}

	/**
	 * Include file of plugin.
	 *
	 * @param string $file File to include which is under plugin directory.
	 * @since 1.0.0
	 */
	public function include_file( $file, $args = [] ) {
		if ( ! empty( $args ) ) {
			extract( $args );
		}

		include wp_normalize_path( $this->plugin_dir . $file . '.php' );
	}

	/**
	 * Get the options of the plugin.
	 *
	 * @param string $key       Option key.
	 * @param mixed  $default   Default value if option not found.
	 * @param mixed  $new_value Default value if option not found.
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function opt( $key, $default = null, $new_value = null ) {
		// Update options if parameter `$new_value` not empty.
		if ( null !== $new_value ) {
			$this->options[ $key ] = $new_value;
			update_option( self::OPTION_NAME, $this->options, true );
		}

		// Return option.
		if ( isset( $this->options[ $key ] ) ) {
			return $this->options[ $key ];
		}

		// Return default.
		return $default;
	}

	/**
	 * Load dependencies.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {
		$this->require_file( 'inc/helpers' );
	}

	/**
	 * Load instance of other classes.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function load_instances() {
		// Load site hooks.
		CPT::instance();

		// Load site hooks.
		Hooks::instance();

		// Shortcode.
		Shortcode::instance();
	}

	/**
	 * Must have hooks.
	 *
	 * @since 1.0.0
	 */
	private function initial_hooks() {
		add_action( 'plugins_loaded', [ __CLASS__, 'load_textdomain' ] );
		add_action( 'plugins_loaded', [ __CLASS__, 'launch' ] );
	}
}
