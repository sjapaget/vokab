<?php
/**
 * Vokab
 *
 * @package     Vokab
 * @author      Samuel Paget
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: Vokab
 * Plugin URI:  https://vokab
 * Description: Manage your vocabulary learning
 * Version:     1.0.0
 * Requires at least: 6.6.1
 * Tested up to: 6.6.1
 * Requires PHP: 8.3
 * Author:      Samuel Paget
 * Author URI: https://github.com/sjapaget
 * Text Domain: vokab
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace vokab;

if ( ! function_exists( 'vokab/run_vokab' ) ) {
	/**
	 * Run Vokab - Initalises the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function run_vokab() {
		include_once plugin_dir_path( __FILE__ ) . 'class-vokab.php';

		$vokab = new Vokab();
		$vokab->instance();
	}
	run_vokab();
} else {
	add_action(
		'admin_init',
		function () {
			printf(
				'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
				esc_html__( 'Multiple versions of the Vokab plugin are active and only one is allowed at a time. Deactivate (and remove) the versions you will no longer use.', 'vokab' )
			);
		}
	);
}
