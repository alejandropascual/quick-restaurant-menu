<?php
/**
 * Scripts
 *
 * @package     ERM
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Enqueue admin scripts
 *
 * @since 1.0
 * @updated 1.1
 * @param string $hook Page hook
 * @return void
 */
function erm_load_admin_scripts( $hook )
{
	global $wp_version, $post, $pagenow, $typenow;

	$js_dir = ERM_PLUGIN_URL . 'assets/js/';

	$IS_DEBUGGING = false;

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Only for post type erm_menu
	if ( apply_filters( 'erm_load_admin_editing_erm_menu', erm_is_admin_editing_erm_menu(), $hook ) )
	{
		wp_enqueue_style( 'menu_style', $js_dir.'erm_menu/style.css' );

		// For debugging
		if ($IS_DEBUGGING){
			wp_enqueue_script('menu_manifest', $js_dir.'erm_menu/manifest.js',  array(), ERM_VERSION, true );
			wp_enqueue_script('menu_vendor', $js_dir.'erm_menu/vendor.js',  array('menu_manifest'), ERM_VERSION, true );
			wp_enqueue_script('menu_main', $js_dir.'erm_menu/main.js',  array('menu_manifest', 'menu_vendor'), ERM_VERSION, true );
		}

		// For production
		else {
			wp_enqueue_script('menu_main', $js_dir.'erm_menu/main.js',  array(), ERM_VERSION, true );
		}
	}
}

add_action( 'admin_enqueue_scripts', 'erm_load_admin_scripts', 100 );
