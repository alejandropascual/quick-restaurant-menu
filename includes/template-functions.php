<?php


/**
 * Before Menu Content
 *
 * Adds an action to the beginning of erm_menu post content that can be hooked to
 * by other functions.
 *
 * @since 1.0.0
 * @global $post
 *
 * @param $content The the_content field of the menu object
 * @return string the content with any additional data attached
 */
function erm_before_menu_content( $content ) {
    global $post;

    if ( $post && $post->post_type == 'erm_menu' && is_singular( 'erm_menu' ) && is_main_query() && !post_password_required() ) {
        ob_start();
        do_action( 'erm_before_menu_content', $post->ID );
        $content = ob_get_clean() . do_shortcode( $content );
    }

    return $content;
}
add_filter( 'the_content', 'erm_before_menu_content' );



/**
 * After Menu Content
 *
 * Adds an action to the end of erm_menu post content that can be hooked to by
 * other functions.
 *
 * @since 1.0.0
 * @global $post
 *
 * @param $content The the_content field of the menu object
 * @return string the content with any additional data attached
 */
function erm_after_menu_content( $content ) {
    global $post;

    if ( $post && $post->post_type == 'erm_menu' && is_singular( 'erm_menu' ) && is_main_query() && !post_password_required() ) {
        ob_start();
        do_action( 'erm_after_menu_content', $post->ID );
        $content .= ob_get_clean();
    }

    return $content;
}
add_filter( 'the_content', 'erm_after_menu_content' );



/**
 * After menu content load the template for the menu
 */
function erm_display_menu_list( $post_id ) {

    global $post;
    $menu_post = $post;

    $show_thumbnails = true;
    $price_position = 'top';

    include ERM_PLUGIN_DIR . 'templates/menu-standard.php';
}

add_action( 'erm_after_menu_content', 'erm_display_menu_list' );