<?php
/**
 * Vokab Plugin
 *
 * The one class that powers the plugin and makes it extendable.
 *
 * @since       1.0.0
 *
 * @package     Vokab
 * @author      Samuel Paget
 * @copyright   2024 Samuel Paget
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace vokab;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Vokab
 *
 * @since 1.0.0
 *
 * @access public
 */
final class Vokab {

	/**
	 * Constant Vokab Version
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Variable Path
	 *
	 * @since 1.0.0
	 * @var string $path
	 */
	public static $path;

	/**
	 * Variable Directory
	 *
	 * @since 1.0.0
	 * @var string $directory
	 */
	public static $directory;

	/**
	 * Variable File
	 *
	 * @since 1.0.0
	 * @var string $file
	 */
	public static $file;

	/**
	 * Variable Instance
	 *
	 * @since 1.0.0
	 * @var string $instance
	 */
	public static $instance;

	/**
	 * Instance Builder
	 *
	 * @since 1.0.0
	 * @return Vokab
	 */
	public static function instance() : Vokab {

		if ( ! ( isset( self::$instance ) ) && ! ( self::$instance instanceof Vokab ) ) {

			self::$instance = new Vokab();
			self::$instance->setup_properties();

			require self::$directory . 'vendor/autoload.php';

			try {
				spl_autoload_register( array( __CLASS__, 'auto_loader' ) );
			} catch ( \Exception $error ) {
				_doing_it_wrong( __FUNCTION__, esc_html__( 'There was an error initializing the Autoloader. Contact the developer.', 'vokab' ), esc_attr( self::VERSION ) );
			}

			self::$instance->initialize();

			/**
			 * Action: Vokab Initialized
			 *
			 * @since 1.0.0
			 */
			do_action( 'vokab_initialized' );

			add_action( 'plugins_loaded', array( self::$instance, 'textdomain' ) );

			add_action( 'plugins_loaded', array( self::$instance, 'load' ) );

			/**
			 * Action: Vokab Loaded
			 *
			 * @since 1.0.0
			 */
			do_action( 'vokab_loaded' );
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Throw error on object clone.
	 *
	 * Singleton design pattern means is that there is a single object,
	 * and therefore, we don't want or allow the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No can do! You may not clone an instance of the plugin.', 'vokab' ), esc_attr( self::VERSION ) );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * Unserializing of the class is also forbidden in the singleton pattern.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No can do! You may not unserialize an instance of the plugin.', 'vokab' ), esc_attr( self::VERSION ) );
	}

	/**
	 * Setup Properties
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function setup_properties(): void {
		self::$directory = plugin_dir_path( __FILE__ );
		self::$file      = self::$directory . 'vokab.php';
		self::$path      = plugin_dir_url( self::$file );
	}

	/**
	 * Initialize Plugin
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function initialize(): void {
		add_action( 'init', [ __CLASS__, 'register_settings' ], 9 );
	}

	/**
	 * Load Plugin
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function load(): void {

		/**
		 * Filter: Should Load Word Post Type Class
		 *
		 * @since 1.0.0
		 * @param bool
		 */
		if ( apply_filters( 'vokab_load_class_word', true ) ) {
			// new core\Word();
		}

		if ( is_admin() ) {
			new Settings();
		}
	}

	/**
	 * Load Text Domain
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function textdomain(): void {
		load_plugin_textdomain( 'vokab', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Class Autoloader
	 *
	 * @since 1.0.0
	 * @param string $class_name - the class name.
	 * @return void
	 */
	public static function auto_loader( string $class_name ): void {

		// PSR-4 Autoloader Scheme.

		$prefix = 'vokab';
		$len    = strlen( $prefix );

		if ( strncmp( $prefix, $class_name, strlen( $prefix ) ) === 0 ) {
			// get the relative class name.
			$relative_class = substr( $class_name, $len );

			// base directory for the namespace prefix.
			$base_dir = self::$directory . 'src/';

			// replace the namespace prefix with the base directory, replace namespace
			// separators with directory separators in the relative class name, append
			// with .php .
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			// if the file exists, require it.
			if ( file_exists( $file ) ) {
				require $file;
				return;
			}
		}
	}

	/**
	 * Registers the option object to store the plugin's settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register_settings(): void {
		$default_values = [
			'wordsPerPracticeSession'   => 5,
			'practiceReminderFrequency' => 'daily',
		];

		$schema = [
			'type'       => 'object',
			'properties' => [
				'wordsPerPracticeSession'          => [
					'type' => 'integer',
				],
				'practiceReminderFrequency' => [
					'type' => 'string',
					'enum' => [
						'daily',
						'weekly',
					],
				],
			],
		];

		register_setting(
			'options',
			'vokab',
			[
				'type'         => 'object',
				'default'      => $default_values,
				'show_in_rest' => [
					'schema' => $schema,
				],
			]
		);
	}
}
