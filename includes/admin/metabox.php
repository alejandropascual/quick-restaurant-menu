<?php
/**
 * Metabox Functions
 *
 * @package     ERM
 * @subpackage  Admin
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register metabox for Menu
 *
 * @since 1.0
 * @updated 1.1
 * @return void
 */
function erm_add_menu_meta_box() {

    $post_types = apply_filters( 'erm_menu_metabox_post_types' , array( 'erm_menu' ) );

    foreach ( $post_types as $post_type ) {

        add_meta_box( 'erm_menu_items', __( 'Menu Items', 'erm' ), 'erm_render_menu_meta_box', $post_type, 'normal', 'high' );
        add_meta_box( 'erm_footer_item', __( 'Footer Menu', 'erm' ), 'erm_render_footer_meta_box', $post_type, 'normal', 'high' );
        //add_meta_box( 'erm_menu_shortcode', __( 'Shortcode', 'erm' ), 'erm_render_shortcode_meta_box', $post_type, 'side' );
    }

    $post_types = apply_filters( 'erm_menu_week_post_types', array( 'erm_menu_week' ) );

    foreach( $post_types as $post_type ) {

        add_meta_box( 'erm_menu_weekly', __( 'Weekly menu rules', 'erm' ), 'erm_render_menu_week_meta_box', $post_type, 'normal', 'high' );
        add_meta_box( 'erm_menu_weekly_shortcode', __( 'Shortcode', 'erm' ), 'erm_render_menu_week_shortcode_meta_box', $post_type, 'side' );
    }
}
add_action( 'add_meta_boxes', 'erm_add_menu_meta_box' );

/**
 * Menu Items metabox
 *
 * @since 1.0
 * @return void
 */
function erm_render_menu_meta_box() {
    global $post;

    /*
     * Output list of Menu Items
     */
    do_action( 'erm_meta_box_menu_items', $post->ID );
}

/**
 *  Render Menu Items inside metabox
 *
 * @since 1.0
 * @param $post_id
 */
function erm_render_menu_items( $post_id ) {
    include ERM_PLUGIN_DIR.'/includes/admin/templates/metabox-menu-items.php';
}
add_action( 'erm_meta_box_menu_items', 'erm_render_menu_items', 10 );

/**
 * Footer Menu metabox
 *
 * @since 1.0
 * @return void
 */
function erm_render_footer_meta_box() {
    global $post;

    do_action( 'erm_meta_box_footer', $post->ID );
}

/**
 *  Render Menu Footer
 *
 * @since 1.0
 * @param $post_id
 */
function erm_render_footer_item( $post_id ) {

    $content = get_post_meta( $post_id, '_erm_footer_menu', true );

    wp_editor( $content, '_erm_footer_menu', array(
        'wpautop'       => true,
        'media_buttons' => false,
        //'textarea_name' => 'meta_biography',
        'textarea_rows' => 10,
        'teeny'         => true
    ) );

    wp_nonce_field( 'erm_footer_metabox_nonce', 'erm_footer_metabox_nonce' );
}
add_action( 'erm_meta_box_footer', 'erm_render_footer_item', 10);

/**
 *  Save Footer menu
 *
 * @since 1.0
 * @param $post_id
 */
function erm_save_footer_item( $post_id ){

    // Check if our nonce is set.
    if ( ! isset( $_POST['erm_footer_metabox_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['erm_footer_metabox_nonce'], 'erm_footer_metabox_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'erm_menu' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Make sure that it is set.
    if ( ! isset( $_POST['_erm_footer_menu'] ) ) {
        return;
    }

    // Sanitize user input.
    //$my_data = sanitize_text_field( $_POST['_erm_footer_menu'] );
    $my_data = $_POST['_erm_footer_menu'];

    // Update the meta field in the database.
    update_post_meta( $post_id, '_erm_footer_menu', $my_data );

}
add_action( 'save_post', 'erm_save_footer_item' );

/**
 * Shortcode Menu metabox
 *
 * @since 1.0
 * @return void
 */
function erm_render_shortcode_meta_box() {
    global $post;

    $sc = '[erm_menu id='.$post->ID.']';
    echo '<p>'.__('Use this shortcode for displaying the menu in other pages','erm').'</p>';
    echo '<div style="background-color: #F1F1F1;padding: 10px;font-size: 20px;">'; print_r( $sc ); echo '</div>';
}

/**
 * Menu Weekly metabox
 *
 * @since 1.0
 * @return void
 */
function erm_render_menu_week_meta_box() {
    global $post;

    do_action( 'erm_render_menu_week_meta_box', $post->ID );
}

/**
 *  Render Menu Week metabox
 *
 * @since 1.1
 * @param $post_id
 */
function erm_render_menu_week_rules( $post_id ){
    include ERM_PLUGIN_DIR.'/includes/admin/templates/metabox-menu-week.php';
}
add_action('erm_render_menu_week_meta_box','erm_render_menu_week_rules');

/**
 *  Render Menu Week shortcode metabox
 *
 * @since 1.1
 */
function erm_render_menu_week_shortcode_meta_box(){
    global $post;

    $sc = '[erm_menu_week id='.$post->ID.']';
    echo '<p>'.__('Use this shortcode for displaying the menu in other pages','erm').'</p>';
    echo '<div style="background-color: #F1F1F1;padding: 10px;font-size: 16px;">'; print_r( $sc ); echo '</div>';
}