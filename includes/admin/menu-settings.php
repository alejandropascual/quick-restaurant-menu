<?php
/**
 * Menu Settings
 *
 * @package     ERM
 * @subpackage  Admin
 * @copyright   Copyright (c) 2015, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Register Menu
 */
function erm_register_menu_settings() {

    $hook = add_submenu_page('edit.php?post_type=erm_menu', __('settings','erm'), __('Settings','erm'), 'manage_options', ERM_SETTINGS, 'erm_display_menu_settings' );
    add_action('load-'.$hook,'erm_settings_saved');
}

function erm_settings_saved(){
    if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
    {
        flush_rewrite_rules(false);
    }
}

add_action( 'admin_menu', 'erm_register_menu_settings' );

function erm_display_menu_settings() {

    // All tabs
    $tabs = array(
        'general' => __('General','erm'),
        /*'misc_' => array(
            'name' => 'Misc',
            'sections' => array(
                'others1' => 'Others 1',
                'others2' => 'Others 2'
            )
        )*/
    );

    // Detect Tab
    $active_tab = ( isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $tabs ) ) ? $_GET['tab'] : 'general';
    $active_section = isset( $_GET['section'] ) ? $_GET['section'] : '';

    ob_start();
    ?>
        <?php do_action('erm_before_settings_page'); ?>

        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <?php



                    foreach( $tabs as $tab_id => $tab_data ) {

                        // Tab with one section
                        if ( is_string( $tab_data ) ) {
                            $tab_name = $tab_data;
                        }
                        // Tab with several sections
                        else {
                            $tab_name = $tab_data['name'];
                        }

                        // URL
                        $tab_url = add_query_arg( array(
                            'settings-updated' => false,
                            'tab' => $tab_id,
                            'section' => false
                        ) );

                        // First section if exists
                        if ( is_array( $tab_data ) ) {
                            $keys = array_keys( $tab_data['sections'] );
                            $tab_url = add_query_arg( array(
                                'settings-updated' => false,
                                'tab' => $tab_id,
                                'section' => $keys[0]
                            ) );
                        }

                        $active = ($active_tab == $tab_id) ? ' nav-tab-active' : '';

                        echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
                        echo esc_html( $tab_name );
                        echo '</a>';
                    }
                ?>
            </h2>
        </div>
        <?php settings_errors(); ?>
        <div id="tab_container">

            <?php

            //List of sections if exists
            $tab = $tabs[$active_tab];

            if ( is_array($tab) ) {

                echo '<ul class="erm-options-sections__ subsubsub">';

                $count = count( $tab['sections'] );
                $index = 1;
                foreach( $tab['sections'] as $section_key => $section_name ) {

                    $tab_sec_url = add_query_arg( array(
                        'settings-updated' => false,
                        'tab' => $active_tab,
                        'section' => $section_key,
                        'erm-message' => false
                    ) );

                    echo '<li>';
                    $class = ( $section_key == $active_section ? 'current' : '' ) ;
                    echo '<a href="' . esc_url( $tab_sec_url ) . '" class="'.$class.'">' . $section_name . '</a>';
                    if ($index++ < $count ) { echo ' | '; }
                    echo '</li>';

                }
                echo '</ul>';
            }
            ?>

            <form method="post" action="options.php">
                <table class="form-table">
                    <?php
                    settings_fields( ERM_SETTINGS );
                    do_settings_fields( ERM_SETTINGS.'_' . $active_tab.$active_section, ERM_SETTINGS.'_' . $active_tab.$active_section );
                    ?>
                </table>
                <?php submit_button(); ?>
            </form>


        </div>
    <?php

    do_action('erm_after_settings_page');

    echo ob_get_clean();
}
