<?php
/**
 * Actions
 *
 * @package     ERM
 * @subpackage  Actions Hooks
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Trash menu items when remove post type erm_menu
 *
 * @since 1.0
 */
function erm_trash_menu_items( $post_id ) {

    if ( get_post_type( $post_id ) != 'erm_menu' ) return;

    $menu_items = get_post_meta( $post_id, '_erm_menu_items', true );

    if ( empty($menu_items) ) return array();

    $menu_items = preg_split('/,/', $menu_items);

    foreach( $menu_items as $id ) {
        wp_trash_post( $id );
    }
}
add_action( 'wp_trash_post', 'erm_trash_menu_items' );

/**
 * Restore menu items when restore post type erm_menu
 *
 * @since 1.0
 * @param $post_id
 */
function erm_untrash_menu_items( $post_id ) {

    if ( get_post_type( $post_id ) != 'erm_menu' ) return;

    $menu_items = get_post_meta( $post_id, '_erm_menu_items', true );

    if ( empty($menu_items) ) return array();

    $menu_items = preg_split('/,/', $menu_items);

    foreach( $menu_items as $id ) {
        wp_untrash_post( $id );
    }
}
add_action( 'untrash_post', 'erm_untrash_menu_items' );

/**
 * Delete menu items when delete Menu post type
 *
 * @since 1.0
 * @param $post_id
 */
function erm_delete_menu_items( $post_id ) {

    if ( get_post_type( $post_id ) != 'erm_menu' ) return;

    $menu_items = get_post_meta( $post_id, '_erm_menu_items', true );

    if ( empty($menu_items) ) return array();

    $menu_items = preg_split('/,/', $menu_items);

    foreach( $menu_items as $id ) {
        wp_delete_post( $id, true );
    }
}
add_action('before_delete_post', 'erm_delete_menu_items');

/**
 * Post Type erm_menu_item is by default of type 'product'
 *
 * @since 1.0
 * @param $post_id
 */
function erm_save_post_erm_menu_item( $post_id ) {

    if (get_post_type( $post_id ) != 'erm_menu_item' ) return;
    $type = get_post_meta( $post_id, '_erm_type', true );
    if ( empty($type) ) {
        update_post_meta( $post_id, '_erm_type', 'product' );
    }
}
add_action('save_post', 'erm_save_post_erm_menu_item');
