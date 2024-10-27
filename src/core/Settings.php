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

namespace vokab\core;

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
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'admin_menu', [ __CLASS__, 'admin_menu' ], 100 );
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
}
