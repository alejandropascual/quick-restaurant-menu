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
function erm_load_admin_scripts( $hook ) {

    global $wp_version, $post, $pagenow, $typenow;

    $js_dir  = ERM_PLUGIN_URL . 'assets/js/';
    $css_dir = ERM_PLUGIN_URL . 'assets/css/';
    $lib_dir = ERM_PLUGIN_URL . 'assets/libs/';

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Only for post type erm_menu
    if ( apply_filters( 'erm_load_admin_editing_erm_menu', erm_is_admin_editing_erm_menu(), $hook ) ) {

        // Fontawesome
        wp_enqueue_style( 'fontawesome', $css_dir.'font-awesome'.$suffix.'.css', array(), '4.3.0' );

        // Sweetalert
        wp_enqueue_style( 'sweetalert', $css_dir.'sweetalert.css', array(), '1.0.0' );

        // ERM style
        wp_enqueue_style( 'erm-admin-style', $css_dir.'erm-admin-style.css', array(), ERM_VERSION );

        // Magnific popup
        wp_enqueue_style( 'magnific-popup', $css_dir.'magnific-popup.css' );

        // Sweetalert
        wp_enqueue_script( 'sweetalert', $js_dir.'sweetalert.min.js', array(), '1.0.0', true );

        // Bootstrap
        //wp_enqueue_style( 'bootstrap', $lib_dir.'bootstrap/css/bootstrap.css' );
        wp_enqueue_style( 'bootstrap-custom', $css_dir.'bootstrap-custom.css' );
        wp_enqueue_script( 'bootstrap', $lib_dir.'bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.5', true );

        // CKeditor
        wp_enqueue_script( 'ckeditor', $lib_dir.'ckeditor2/ckeditor.js', array('jquery'), '2.0.0', true );
        wp_enqueue_script( 'jq-ckeditor', $lib_dir.'ckeditor2/adapters/jquery.js', array('ckeditor'), '2.0.0', true );

        // Knockout
        wp_enqueue_script( 'knockout', $js_dir.'knockout.min.js', array('jquery'), '3.3.0', true );
        wp_enqueue_script( 'knockout-sortable', $js_dir.'knockout-sortable'.$suffix.'.js', array('knockout'), '0.11.0', true );
        wp_enqueue_script( 'ko-modal', $js_dir.'ko-modal.js', array('knockout'), '1.0.0', true );

        // Media
        if( function_exists( 'wp_enqueue_media' ) && version_compare( $wp_version, '3.5', '>=' ) ) {
            wp_enqueue_media();
        }
        wp_enqueue_script( 'media-upload' );

        // Magnific-popup
        wp_enqueue_script( 'magnific-popup', $js_dir . 'jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );

        // ERM script
        wp_enqueue_script( 'erm-admin-scripts', $js_dir.'erm-admin-erm_menu.js', array('jquery','knockout', 'knockout-sortable'), ERM_VERSION, true );

        // Menu items
        global $post_id;
        $menu_items = erm_get_menu_items_data( $post->ID );
        wp_localize_script( 'erm-admin-scripts', 'erm_vars', array(
            'menu_id'       => $post_id,
            'menu_items'    => $menu_items,
            'editor_css'    => $css_dir.'erm-admin-tinymce.css',
            'use_new_media_35' => function_exists( 'wp_enqueue_media' ) ? 1 : 0,
            'notices' => array(
                'alert_delete' => __('Are you sure to delete?', 'erm'),
                'alert_confirm' => __('Yes, delete it!', 'erm')
            )
        ) );

    }

    //Columns erm_menu, erm_menu_item
    else if ( $pagenow == 'edit.php' && ( $typenow == 'erm_menu_item' || $typenow == 'erm_menu' || $typenow == 'erm_menu_week' ) ) {
        wp_enqueue_style( 'erm-admin-cols-style', $css_dir.'erm-admin-cols-style.css', array(), ERM_VERSION );
    }

    // Post type erm_menu_week
    else if ( apply_filters( 'erm_load_admin_editing_erm_menu_week', erm_is_admin_editing_erm_menu_week(), $hook ) ) {

        // Fontawesome
        wp_enqueue_style( 'fontawesome', $css_dir.'font-awesome'.$suffix.'.css', array(), '4.3.0' );

        // ion.rangeSlider
        wp_enqueue_style( 'ion.rangeSlider', $css_dir.'ion.rangeSlider.css', array(), '2.0.6' );
        wp_enqueue_style( 'ion.rangeSlider.skin', $css_dir.'ion.rangeSlider.skinHTML5.css', array(), '2.0.6' );

        // Knockout
        wp_enqueue_script( 'knockout', $js_dir.'knockout.min.js', array(), '3.3.0', true );
        wp_enqueue_script( 'knockout-sortable', $js_dir.'knockout-sortable'.$suffix.'.js', array(), '0.11.0', true );

        // ion rangeSlider
        wp_enqueue_script( 'ion-rangeslider', $js_dir.'ion.rangeSlider'.$suffix.'.js', array(), '2.0.6', true );

        // ERM script
        wp_enqueue_script( 'erm-admin-scripts', $js_dir.'erm-admin-erm_menu_week.js', array('jquery','knockout', 'knockout-sortable', 'ion-rangeslider'), ERM_VERSION, true );

        // Data
        global $post_id;

        // These anonymus functions need php 5.3
        // Add void menu to the start of list menus
        /*add_filter('erm_get_list_menus',function($list){
           return array_merge(array(
               array('id'=>0,'title'=>'No Menu selected')),
               $list);
        });*/

        // Menu rules by default
        /*add_filter('erm_get_menu_week_rules',function($franjas){
            if (empty($franjas)) {
                $franjas = array(
                    array(
                        'week' => array(true,true,true,true,true,true,true),
                        'begin' => '00:00',
                        'end' => '24:00',
                        'menu_id' => 0
                    )
                );
            }
            return $franjas;
        });*/

        wp_localize_script( 'erm-admin-scripts', 'erm_vars', array(
            'post_id' => $post_id,
            'menus' => erm_get_list_menus(),
            'franjas' => apply_filters( 'erm_get_menu_week_rules' , get_post_meta($post_id,'erm_week_rules',true) ),
            'start_of_week' => get_option('start_of_week'),
            //'week_days' => erm_get_week_days_ordered(),
            'week_days' => erm_get_week_days_english()
        ));

    }

}
add_action( 'admin_enqueue_scripts', 'erm_load_admin_scripts', 100 );

/**
 * Add void menu to the list
 *
 * @since 1.2
 * @param $list
 * @return array
 */
function erm_admin_scripts_filter_list_menus( $list ){
    return array_merge(array(
            array('id'=>0,'title'=>__('No Menu selected','erm'))),
        $list);
}
add_filter('erm_get_list_menus','erm_admin_scripts_filter_list_menus');

/**
 * Default schedules
 *
 * @since 1.2
 * @param $franjas
 * @return array
 */
function erm_admin_scripts_filter_rules( $franjas ){
    if (empty($franjas)) {
        $franjas = array(
            array(
                'week' => array(true,true,true,true,true,true,true),
                'begin' => '00:00',
                'end' => '24:00',
                'menu_id' => 0
            )
        );
    }
    return $franjas;
}
add_filter('erm_get_menu_week_rules', 'erm_admin_scripts_filter_rules');