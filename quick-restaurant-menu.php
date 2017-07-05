<?php
/**
 * Plugin Name: Quick Restaurant Menu
 * Plugin URI: http://thingsforrestaurants.com
 * Description: Create Restaurants Menus
 * Author: Alejandro Pascual
 * Author URI: http://thingsforrestaurants.com
 * Version: 1.6.5
 * Text Domain: erm
 * Domain Path: languages
 *
 * Easy Restaurant Menu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Easy Restaurant Menu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Easy Restaurant Menu. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ERM
 * @category Core
 * @author Alejandro Pascual
 * @version 1.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'El_Restaurant_Menu' ) ) :

    /**
     * Main Class. Singleton
     *
     * @since 1.0
     */
    final class El_Restaurant_Menu {

        /**
         * @var object
         * @since 1.0
         */
        private static $singleton;

        /**
         * @var object
         * @since 1.0
         */
        public $settings;

        /**
         * Main Easy_Restaurant_Menu instance
         *
         * @since 1.0
         * @return instance of Easy_Restaurant_Menu
         */
        public static function singleton() {

            if ( !isset( self::$singleton ) && !( self::$singleton instanceof El_Restaurant_Menu ) ) {

                self::$singleton = new El_Restaurant_Menu;
                self::$singleton->init();
                self::$singleton->setup_constants();
                add_action( 'plugins_loaded', array(self::$singleton, 'load_textdomain' ) );
                self::$singleton->includes();

                self::$singleton->settings = new ERM_Settings( ERM_SETTINGS );
            }
            return self::$singleton;
        }

        /**
         * With singleton: Error if clone object
         *
         * @since 1.0
         */
        public function __clone() {
            // Cloning is not allowed
            _doing_it_wrong( __FUNCTION__, __( 'Clone is not allowed', 'erm' ), '1.0' );
        }

        /**
         * With singleton: Error unserializing class
         *
         * @since 1.0
         */
        public function __wakeup() {
            // Unserializing is not allowd
            _doing_it_wrong( __FUNCTION__, __( 'Unserializing is not allowed', 'erm' ), '1.0' );
        }

        /**
         * Init
         *
         * @since 1.0
         */
        private function init() {

        }

        /**
         * Plugin Constants
         *
         * @since 1.0
         */
        private function setup_constants() {

            // Folder Path
            if ( ! defined( 'ERM_PLUGIN_DIR' ) ) {
                define( 'ERM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            }

            // Folder URL
            if ( ! defined( 'ERM_PLUGIN_URL' ) ) {
                define( 'ERM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            }

            // Root File
            if ( ! defined( 'ERM_PLUGIN_FILE' ) ) {
                define( 'ERM_PLUGIN_FILE', __FILE__ );
            }

            // Version
            if ( ! defined( 'ERM_VERSION' ) ) {
                define( 'ERM_VERSION', '1.6.5' );
            }

            // Name for Setting
            if ( ! defined( 'ERM_SETTINGS' ) ) {
                define( 'ERM_SETTINGS', 'erm_settings' );
            }
        }

        /**
         * Required files
         *
         * @since 1.0
         */
        private function includes() {

            // General
            require_once ERM_PLUGIN_DIR . 'includes/post-types.php';
            require_once ERM_PLUGIN_DIR . 'includes/shortcodes.php';
            require_once ERM_PLUGIN_DIR . 'includes/misc-functions.php';
            require_once ERM_PLUGIN_DIR . 'includes/scripts-front.php';
            require_once ERM_PLUGIN_DIR . 'includes/template-functions.php';
            require_once ERM_PLUGIN_DIR . 'includes/admin/settings.php';

            // Admin
            if ( is_admin() ) {
                require_once ERM_PLUGIN_DIR . 'includes/admin/metabox.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/functions.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/scripts.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/ajax-functions.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/actions.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/thanks-for-using.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/menu-settings.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/menu-settings.php';
                require_once ERM_PLUGIN_DIR . 'includes/admin/table-columns.php';
            }
        }

        /**
         * Language files
         *
         * @since 1.0
         */
        public function load_textdomain() {
            load_plugin_textdomain( 'erm', false, plugin_basename( dirname( __FILE__ ) ) . "/languages/" );
        }

    }

endif; // End if

/**
 * Use this function to return the Singleton
 *
 * @since 1.0
 * @return the singleton object Easy_Restaurant_Menu
 */
function ERM() {
    return El_Restaurant_Menu::singleton();
}

// Create singleton
ERM();

