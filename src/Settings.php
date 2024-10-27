<?php
/**
 * Vokab / Core / Settings
 *
 * Handles the settings for the plugin.
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
 * Word custom post type.
 *
 * @since 1.0.0
 */
class Settings {

	/**
	 * Variable: Screen
	 *
	 * Saves the screen id of the Vokab Settings screen
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $screen;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'register_setting' ] );

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'admin_menu', [ __CLASS__, 'admin_menu' ], 100 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_style_script' ] );
	}

	/**
	 * Add Settings Page
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function admin_menu(): void {
		add_options_page(
			__( 'Vokab', 'vokab' ),
			__( 'Vokab', 'vokab' ),
			'manage_options',
			'vokab',
			[ __CLASS__, 'settings_page_html' ],
		);
	}

	/**
	 * Settings Page HTML
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function settings_page_html() {
		printf(
			'<div class="wrap" id="vokab-settings">%s</div>',
			esc_html__( 'Loading…', 'vokab' )
		);
	}

	/**
	 * Enqueue Script for Settings Page
	 *
	 * @since 1.0.0
	 * @param  string $admin_page - the string identifying which admin page we're on.
	 * @return void
	 */
	public static function enqueue_style_script( string $admin_page ): void {

		if ( 'settings_page_vokab' !== $admin_page ) {
			return;
		}

		$plugin_file = dirname( __DIR__ ) . '/vokab.php';

		if ( ! file_exists( $plugin_file ) ) {
			return;
		}

		$asset_file  = plugin_dir_path( $plugin_file ) . 'build/index.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_script(
			'vokab-settings-script',
			plugins_url( 'build/index.js', $plugin_file ),
			$asset['dependencies'],
			$asset['version'],
			[
				'in_footer' => true
			]
		);

		wp_enqueue_style( 'wp-components' );
	}

	/**
	 * Registers the option object to store the plugin's settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register_setting(): void {
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
