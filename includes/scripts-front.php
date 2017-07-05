<?php
/**
 * Scripts Front End
 *
 * @package     ERM
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Load Scripts front End
 *
 * @since 1.0
 * @updated 1.1
 */
function erm_load_scripts() {

    global $wp_version, $post;

    if ( !isset($post->post_content) ) return;

    $js_dir  = ERM_PLUGIN_URL . 'assets/js/';
    $css_dir = ERM_PLUGIN_URL . 'assets/css/';

    if ( has_shortcode( $post->post_content, 'erm_menu') || has_shortcode( $post->post_content, 'erm_menu_week') || get_post_type( $post ) == 'erm_menu' ) {

        wp_enqueue_style( 'font-oswald', '//fonts.googleapis.com/css?family=Oswald:400,700' );
        wp_enqueue_style( 'magnific-popup', $css_dir.'magnific-popup.css' );
        wp_enqueue_style( 'erm-front', $css_dir.'erm-front.css' , array(), ERM_VERSION );

        $inject_custom_css = ERM()->settings->get('erm_custom_css_display');
        if ( $inject_custom_css ) {
            $custom_css = ERM()->settings->get('erm_custom_css');
            if ( !empty($custom_css) ) {
                wp_add_inline_style( 'erm-front', $custom_css );
            }
        }

        wp_enqueue_script( 'magnific-popup', $js_dir . 'jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'erm-front', $js_dir . 'erm-front-scripts.js', array( 'magnific-popup' ), '1.0.0', true );
    }
}


add_action( 'wp_enqueue_scripts', 'erm_load_scripts' );