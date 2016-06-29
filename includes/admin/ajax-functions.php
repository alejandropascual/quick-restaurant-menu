<?php
/**
 * AJAX Functions
 *
 * Process ajax admin functions.
 *
 * @package     ERM
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 *
 */
function erm_update_menu_item() {

    //echo '<pre>'; print_r( $_POST ); echo '</pre>'; exit();

    if (isset($_POST['post_id'])) {

        $post_id = absint( $_POST['post_id'] );

        wp_update_post(array(
            'ID'            => $post_id,
            'post_title'    => $_POST['title'],
            'post_name'     => $_POST['title'],
            'post_content'  => $_POST['content'],
        ));
        update_post_meta( $post_id, '_erm_visible', $_POST['visible'] == 'true' ? true : false );
        update_post_meta( $post_id, '_erm_prices', $_POST['prices']);

        $image_id = absint( $_POST['image_id'] );
        if ( $image_id != 0 ) {
            set_post_thumbnail( $post_id, $image_id );
        } else {
            delete_post_thumbnail( $post_id );
        }

        wp_send_json_success();
    }
    exit();
}
add_action( 'wp_ajax_erm_update_menu_item', 'erm_update_menu_item' );

/**
 * Delete menu item
 */
function erm_delete_menu_item() {

    if (isset($_POST['post_id'])) {
        wp_delete_post( absint($_POST['post_id']), true);
        wp_send_json_success();
    }
    exit();
}
add_action( 'wp_ajax_erm_delete_menu_item', 'erm_delete_menu_item' );

/**
 * Create new menu item
 */
function erm_create_menu_item() {

    $post_id = wp_insert_post(array(
        'post_type'      => 'erm_menu_item',
        'post_content'   => '',
        'post_name'      => 'New',
        'post_title'     => 'New',
        'post_status'    => 'publish'
    ), true );

    if ( is_wp_error($post_id) ) {
        wp_send_json_error();

    } else {
        $type = isset($_POST['type']) ? $_POST['type'] : 'product';
        update_post_meta( $post_id, '_erm_visible', true );
        update_post_meta( $post_id, '_erm_type', $type );
        wp_send_json_success(array(
            'id'        => $post_id,
            'type'      => $type,
            'title'     => 'New',
            'content'   => '',
            'image_id'  => 0,
            'src_thumb' => '',
            'src_big'   => '',
            'visible'   => 1,
            'prices' => array(),
            'link'  => get_edit_post_link( $post_id )
        ));
    }

    exit();
}
add_action( 'wp_ajax_erm_create_menu_item', 'erm_create_menu_item' );

/**
 * Update menu item
 *
 * @since 1.0
 */
function erm_update_list_menu_items() {

    if ( isset($_POST['ids']) ) {
        $post_id = absint( $_POST['post_id'] );
        update_post_meta( $post_id, '_erm_menu_items', $_POST['ids']);
        wp_send_json_success();
    }

    exit();
}
add_action( 'wp_ajax_erm_update_list_menu_items', 'erm_update_list_menu_items' );

/**
 * Get list of menu items ajax
 *
 * @since 1.0
 */
function erm_list_menu_items() {

    $posts = get_posts( array(
        'post_type' => 'erm_menu_item',
        'numberposts' => -1,
        'order_by' => 'post_title',
        'order' => 'ASC'
    ) );

    $html = '';
    $items = array();
    if ($posts) {
        $html .= '<div style="display: inline-block; text-align: left; margin-bottom:20px;">';
        foreach( $posts as $post ) {
            if ( get_post_meta($post->ID,'_erm_type',true) == 'product'){
                $html .= '<label><input data-id="'.$post->ID.'" type="checkbox">'.$post->post_title.'</label><br>';
                $items[] = erm_get_menu_item_data( $post->ID );
            }
        }
        $html .= '</div><hr>';
        $html .= '<button id="add-menu-items" class="button button-default">'.__('Add Menu Items','erm').'</button>';
    } else {
        $html .= '<h1>NO MENU ITEMS</h1>';
    }
    wp_send_json_success( array('html'=>$html, 'items'=>$items) );
    exit();
}
add_action( 'wp_ajax_erm_list_menu_items', 'erm_list_menu_items' );


/**
 * Save menu week
 *
 * @since 1.1
 */
function erm_update_menu_week() {

    $post_id = $_POST['post_id'];
    $franjas = $_POST['franjas'];
    //echo '<pre>'; print_r( $franjas ); echo '</pre>';
    update_post_meta( $post_id, 'erm_week_rules', $franjas );


    wp_send_json_success();
}
add_action( 'wp_ajax_erm_update_menu_week', 'erm_update_menu_week' );
