<?php
/**
 * Post types Custom COlumns
 *
 * @package     ERM
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


/**
 * Table Post type erm_menu_item add columns
 *
 * @since 1.0
 * @param $defaults
 * @return array
 */
function erm_posts_menu_item_columns( $defaults ) {
    $result = array();
    $result['cb'] = $defaults['cb'];
    $result['thumb'] = __('Image', 'erm');
    unset( $defaults['cb'] );
    foreach( $defaults as $key => $value ) {
        $result[ $key ] = $value;
    }
    return $result;
}

/**
 * Table Post type erm_menu_item Show columns
 *
 * @since 1.0
 * @param $column_name
 * @param $post_id
 */
function erm_posts_menu_item_columns_echo( $column_name, $post_id ) {

    if ( $column_name == 'thumb' ) {
        if ( get_post_meta( $post_id, '_erm_type', true ) == 'section' ) {
            echo '<strong>'.__('SECTION', 'erm').'</strong>';
        } else {
            $image_id = get_post_thumbnail_id( $post_id );
            if ($image_id) {
                $src = erm_get_image_src( $image_id );
                echo '<a href="'.get_edit_post_link($post_id).'"><img src="'.$src.'" style="width:80px;height:auto;"></a>';
            }
        }
    }
}

add_filter('manage_erm_menu_item_posts_columns', 'erm_posts_menu_item_columns');
add_action('manage_erm_menu_item_posts_custom_column', 'erm_posts_menu_item_columns_echo', 10, 2);


/**
 * Table Post type erm_menu add columns
 *
 * @since 1.0
 * @param $defaults
 * @return array
 */
function erm_posts_menu_columns( $defaults ) {
    $result = array();
    $result['cb'] = $defaults['cb'];
    $result['title'] = $defaults['title'];
    $result['shortcode'] = __('Shortcode', 'erm');
    unset( $defaults['cb'] );
    unset( $defaults['title'] );
    foreach( $defaults as $key => $value ) {
        $result[ $key ] = $value;
    }
    return $result;
}

/**
 * Table Post type erm_menu Show columns
 *
 * @since 1.0
 * @param $column_name
 * @param $post_id
 */
function erm_posts_menu_columns_echo( $column_name, $post_id ) {
    if ( $column_name == 'shortcode' ) {
        echo '[erm_menu id='.$post_id.']';
    }
}

add_filter('manage_erm_menu_posts_columns', 'erm_posts_menu_columns');
add_action('manage_erm_menu_posts_custom_column', 'erm_posts_menu_columns_echo', 10, 2);


/**
 * Table Post type erm_menu add columns
 *
 * @since 1.1
 * @param $defaults
 * @return array
 */
function erm_posts_menu_week_columns( $defaults ) {
    $result = array();
    $result['cb'] = $defaults['cb'];
    $result['title'] = $defaults['title'];
    $result['shortcode'] = __('Shortcode', 'erm');
    unset( $defaults['cb'] );
    unset( $defaults['title'] );
    foreach( $defaults as $key => $value ) {
        $result[ $key ] = $value;
    }
    return $result;
}

/**
 * Table Post type erm_menu Show columns
 *
 * @since 1.1
 * @param $column_name
 * @param $post_id
 */
function erm_posts_menu_week_columns_echo( $column_name, $post_id ) {
    if ( $column_name == 'shortcode' ) {
        echo '[erm_menu_week id='.$post_id.']';
    }
}

add_filter('manage_erm_menu_week_posts_columns', 'erm_posts_menu_week_columns');
add_action('manage_erm_menu_week_posts_custom_column', 'erm_posts_menu_week_columns_echo', 10, 2);