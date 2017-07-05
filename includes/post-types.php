<?php
/**
 * Post Types
 *
 * @package     ERM
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function erm_setup_post_types() {
    global $wp_version;

    // Post type menu
    $labels_menu = apply_filters( 'erm_menu_labels', array(
        'name' 				=> __( 'Menus', 'post type general name', 'erm' ),
        'singular_name' 	=> __( 'Menu', 'post type singular name', 'erm' ),
        'add_new' 			=> __( 'Add New Menu', 'erm' ),
        'add_new_item' 		=> __( 'Add New Menu', 'erm' ),
        'edit_item' 		=> __( 'Edit Menu', 'erm' ),
        'new_item' 			=> __( 'New Menu', 'erm' ),
        'all_items' 		=> __( 'All Menus', 'erm' ),
        'view_item' 		=> __( 'View Menus', 'erm' ),
        'search_items' 		=> __( 'Search Menus', 'erm' ),
        'not_found' 		=> __( 'No Menus found', 'erm' ),
        'not_found_in_trash'=> __( 'No Menus found in Trash', 'erm' ),
        'parent_item_colon' => '',
        'menu_name' 		=> __( 'Rest. Menus', 'menu post type menu name', 'erm' )
    ));

    $slug           = defined('ERM_MENU_SLUG') ? ERM_MENU_SLUG : 'qr_menu';
    $new_slug = ERM()->settings->get('erm_menu_slug');
    if ( strlen($new_slug) >= 3 && $new_slug != $slug ) {
        $slug = $new_slug;
    }
    $has_archive    = defined('ERM_MENU_HAS_ARCHIVE') && ERM_MENU_HAS_ARCHIVE ? true : false;
    $rewrite        = defined('ERM_DISABLE_REWRITE') && ERM_DISABLE_REWRITE ? false : array('slug'=>$slug, 'with_front'=>false);

    $args_menu = array(
        'labels'            => $labels_menu,
        'public'            => true,
        //'publicly_queryable'=> true,
        'show_ui' 			=> true,
        'show_in_menu' 		=> true,
        'query_var' 		=> true,
        'rewrite' 			=> $rewrite,
        'map_meta_cap'      => true,
        'has_archive' 		=> $has_archive,
        'hierarchical' 		=> false,
        'supports'          => apply_filters( 'erm_menu_supports', array( 'title', 'editor', 'revisions', 'author' ) )
    );
    if( version_compare( $wp_version, '3.8-RC', '>=' ) || version_compare( $wp_version, '3.8', '>=' ) ) {
        $args_menu['menu_icon'] = 'dashicons-list-view';
    }

    register_post_type('erm_menu', apply_filters( 'erm_menu_post_type_args', $args_menu ) );

    // Post type menu_item
    $labels_menu_item = apply_filters( 'erm_menu_item_labels', array(
        'name' 				=> __( 'Menu Items', 'post type general name', 'erm' ),
        'singular_name' 	=> __( 'Menu Item', 'post type singular name', 'erm' ),
        'add_new' 			=> __( 'Add New', 'erm' ),
        'add_new_item' 		=> __( 'Add New Menu Item', 'erm' ),
        'edit_item' 		=> __( 'Edit Menu Item', 'erm' ),
        'new_item' 			=> __( 'New Menu Item', 'erm' ),
        'all_items' 		=> __( 'All Menu Items', 'erm' ),
        'view_item' 		=> __( 'View Menu Item', 'erm' ),
        'search_items' 		=> __( 'Search Menu Items', 'erm' ),
        'not_found' 		=> __( 'No Menu Item found', 'erm' ),
        'not_found_in_trash'=> __( 'No Menu Items found in Trash', 'erm' ),
        'parent_item_colon' => '',
        'menu_name' 		=> __( 'Rest. Menu Items', 'erm' )
    ));

    $args_menu_item = array(
        'labels'            => $labels_menu_item,
        //'show_ui'            => ERM()->settings->get('erm_show_dashboard_menu_items') == 1 ? true : false,
        'show_ui'           => true,
        'public'            => true,
        //'publicly_queryable'=> true,
        'query_var' 		=> true,
        //'rewrite' 			=> false,
        'capabilities'      => array('create_posts'=>false),
        'map_meta_cap'      => true, // true to edit/delete
        'has_archive' 		=> true,
        'hierarchical' 		=> false,
        'show_in_menu'      => 'edit.php?post_type=erm_menu',
        'supports'          => apply_filters( 'erm_menu_item_supports', array( 'title', 'editor', 'thumbnail', 'revisions', 'author' ) )
    );
    if( version_compare( $wp_version, '3.8-RC', '>=' ) || version_compare( $wp_version, '3.8', '>=' ) ) {
        $args_menu_item['menu_icon'] = 'dashicons-exerpt-view';
    }

    register_post_type('erm_menu_item', apply_filters( 'erm_menu_item_post_type_args', $args_menu_item ) );

    // Post type menu_week
    $labels_menu_week = apply_filters( 'erm_menu_week_labels', array(
        'name' 				=> __( 'Weekly Menus', 'post type general name', 'erm' ),
        'singular_name' 	=> __( 'Weekly Menu', 'post type singular name', 'erm' ),
        'add_new' 			=> __( 'Add New', 'erm' ),
        'add_new_item' 		=> __( 'Add New Weekly Menu', 'erm' ),
        'edit_item' 		=> __( 'Edit Weekly Menu', 'erm' ),
        'new_item' 			=> __( 'New Weekly Menu', 'erm' ),
        'all_items' 		=> __( 'All Weekly Menus', 'erm' ),
        'view_item' 		=> __( 'View Weekly Menu', 'erm' ),
        'search_items' 		=> __( 'Search Weekly Menus', 'erm' ),
        'not_found' 		=> __( 'No Weekly Menu found', 'erm' ),
        'not_found_in_trash'=> __( 'No Weekly Menus found in Trash', 'erm' ),
        'parent_item_colon' => '',
        'menu_name' 		=> __( 'Rest. Menus Week', 'erm' )
    ));

    $args_menu_week = array(
        'labels'            => $labels_menu_week,
        'public'            => true,
        'publicly_queryable'=> false,
        'query_var' 		=> false,
        'rewrite' 			=> false,
        'map_meta_cap'      => true, // true to edit/delete
        'has_archive' 		=> false,
        'hierarchical' 		=> false,
        'show_in_menu'      => 'edit.php?post_type=erm_menu',
        'supports'          => apply_filters( 'erm_menu_week_supports', array( 'title' ) )
    );
    if( version_compare( $wp_version, '3.8-RC', '>=' ) || version_compare( $wp_version, '3.8', '>=' ) ) {
        $args_menu_week['menu_icon'] = 'dashicons-exerpt-view';
    }

    register_post_type('erm_menu_week', apply_filters( 'erm_menu_week_post_type_args', $args_menu_week ) );


}
add_action( 'init', 'erm_setup_post_types', 1 );