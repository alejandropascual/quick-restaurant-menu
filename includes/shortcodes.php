<?php
/**
 * Shortcodes
 *
 * @package     ERM
 * @subpackage  Shortcodes
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode Menu one column
 *
 * @since 1.0
 */
/*
 [erm_menu id=319 thumb=no]
 */
function erm_shortcode_menu( $atts, $content = null ) {

    global $post;
    $post_id = is_object( $post ) ? $post->ID : 0;

    $atts = shortcode_atts( array(
        'id' 	        => $post_id,
        'thumb'         => 'yes',
        'price'         => 'top'
    ), $atts, 'erm_menu' );

    $post_id = $atts['id']; // I need only this for the template
    $show_thumbnails = $atts['thumb'] == 'yes' ? true : false;
    $price_position = $atts['price'];

    if ( get_post_type( $post_id ) != 'erm_menu' ) { return ''; }

    // Template
    ob_start();
    include ERM_PLUGIN_DIR . 'templates/menu-standard.php';
    $html = ob_get_clean();

    return $html;
}
add_shortcode( 'erm_menu', 'erm_shortcode_menu' );



/**
 * Shortcode Menu Week
 *
 * @since 1.1
 */
function erm_shortcode_menu_week( $atts, $content = null ){

    global $post;
    $post_id = is_object( $post ) ? $post->ID : 0;

    $atts = shortcode_atts( array(
        'id' 	        => $post_id,
        'thumb'         => 'yes',
        'price'         => 'top'
    ), $atts, 'erm_menu' );

    $post_id = $atts['id'];

    if ( get_post_type( $post_id ) != 'erm_menu_week' ) { return; }

    $rules = get_post_meta( $post_id, 'erm_week_rules', true );
    if (empty($rules)) { return; }

    // Get today
    $now = current_time('timestamp',0);
    $now_day = current_time('w',0); // 0-6 0=sunday
    //echo '<pre>NOW: '; print_r( $now ); echo '</pre>';
    //echo '<pre>NOW DAY: '; print_r( $now_day ); echo '</pre>';
    //echo '<pre>'; print_r( current_time('mysql',0) ); echo '</pre>';

    //$blogtime = current_time( 'mysql',0);
    //list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '/([^0-9])/', $blogtime );
    $hour = current_time('H',0);
    $minute = current_time('i',0);
    $day_minutes = 60*intval($hour)+intval($minute);
    //echo '<pre>HOUR: '; print_r( $hour ); echo '</pre>';
    //echo '<pre>MINUTES: '; print_r( $minute ); echo '</pre>';
    //echo '<pre>DAY MINUTES: '; print_r( $day_minutes ); echo '</pre>';

    // Check each rule
    foreach( $rules as $rule ) {

        //echo '<pre>RULE: '; print_r( $rule ); echo '</pre>';

        // Check if day is included
        $days_rule = array();
        $index = 0;
        foreach($rule['week'] as $day) {
            if ($day === 'true' ) {
                $days_rule[] = $index;
            }
            $index++;
        }
        if ( !in_array($now_day,$days_rule) ) {
            continue;
        }

        // Check hours
        list( $hour1, $min1 ) = preg_split('/:/', $rule['begin']);
        list( $hour2, $min2 ) = preg_split('/:/', $rule['end']);
        $minutes_begin = 60*intval($hour1)+intval($min1);
        $minutes_end = 60*intval($hour2)+intval($min2);
        //echo '<pre>Begin: '; print_r( $minutes_begin ); echo '</pre>';
        //echo '<pre>End: '; print_r( $minutes_end ); echo '</pre>';

        if ( $minutes_begin<=$day_minutes && $minutes_end>=$day_minutes ) {
            return do_shortcode('[erm_menu id='.$rule['menu_id'].' thumb='.$atts['thumb'].' price='.$atts['price'].']');
            // break; da problemas
        }
    }

    return '';
}
add_shortcode( 'erm_menu_week', 'erm_shortcode_menu_week' );