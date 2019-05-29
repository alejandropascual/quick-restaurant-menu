<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function erm_register_menu_basic_information() {
    add_submenu_page('edit.php?post_type=erm_menu', __('Basic Information','erm'), __('Basic Information','erm'), 'manage_options', 'basic_information', 'erm_display_menu_basic_information' );
}
add_action( 'admin_menu', 'erm_register_menu_basic_information' );

function erm_display_menu_basic_information() {

    ?>

    <div class="wrap qrm-wrap qrm-info-wrap">
        <div class="qrm-badge"><span>Version<?php echo ERM_VERSION; ?></span></div>
        <h1><strong><?php _e( 'Quick Restaurant Menu', 'erm' ); ?></strong> Free</h1>
        <p class="qrm-info-text"><?php printf( __( 'The easiest way to create the menu for your Restaurant', 'erm' ) ); ?></p>
        <hr />

        <h2 class="qrm-callout"><?php _e( 'For Modern User Interaction', 'erm' ); ?></h2>
        <div class="qrm-row qrm-3-col">
            <div>
                <h3><?php _e( 'Login', 'erm' ); ?></h3>
                <p><?php printf( __( 'Friction-less login using %s shortcode or a widget.', 'erm' ), '<strong class="nowrap">[wppb-login]</strong>' ); ?></p>
            </div>
            <div>
                <h3><?php _e( 'Registration', 'erm'  ); ?></h3>
                <p><?php printf( __( 'Beautiful registration forms fully customizable using the %s shortcode.', 'erm' ), '<strong class="nowrap">[wppb-register]</strong>' ); ?></p>
            </div>
            <div>
                <h3><?php _e( 'Edit Profile', 'erm' ); ?></h3>
                <p><?php printf( __( 'Straight forward edit profile forms using %s shortcode.', 'erm' ), '<strong class="nowrap">[wppb-edit-profile]</strong>' ); ?></p>
            </div>
        </div>

        <h2 class="qrm-callout"><?php _e( 'For Modern User Interaction', 'erm' ); ?></h2>
        <div class="qrm-row qrm-3-col">
            <div>
                <h3><?php _e( 'Login', 'erm' ); ?></h3>
                <p><?php printf( __( 'Friction-less login using %s shortcode or a widget.', 'erm' ), '<strong class="nowrap">[wppb-login]</strong>' ); ?></p>
            </div>
            <div>
                <h3><?php _e( 'Registration', 'erm'  ); ?></h3>
                <p><?php printf( __( 'Beautiful registration forms fully customizable using the %s shortcode.', 'erm' ), '<strong class="nowrap">[wppb-register]</strong>' ); ?></p>
            </div>
            <div>
                <h3><?php _e( 'Edit Profile', 'erm' ); ?></h3>
                <p><?php printf( __( 'Straight forward edit profile forms using %s shortcode.', 'erm' ), '<strong class="nowrap">[wppb-edit-profile]</strong>' ); ?></p>
            </div>
        </div>

    </div>

    <style>
        .qrm-info-wrap {
            max-width: 1050px;
            margin: 25px 40px 0 20px;
        }
        .qrm-badge {
            background: url(<?php echo ERM_PLUGIN_URL.'/assets/images/logo.png'; ?>) center no-repeat;
            background-size: cover !important;
        }
        .qrm-badge {
            float: right;
            color: #fff;
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
            width: 120px;
            height: 150px;
            margin-bottom: 25px;
            text-align: center;
            text-rendering: optimizelegibility;
        }
        .qrm-badge span {
            display: inline-block;
            text-transform: uppercase;
            color: #fff;
            margin-top: 110px;
            font-size: 90%;
        }
        .qrm-wrap h1 {
            color: #333333;
            font-size: 2.8em;
            font-weight: 400;
            line-height: 1.2em;
            margin: 0;
            padding-top: 35px;
        }

        .qrm-wrap .qrm-callout {
            font-size: 2em;
            font-weight: 300;
            line-height: 1.3;
            margin: 1.1em 0 0.2em 0;
        }
        .qrm-row { overflow: hidden; }
        .qrm-3-col > div {
            float: left;
            width: 28%;
            margin-right: 5%;
        }
        .qrm-wrap, .qrm-wrap p {
            font-size: 15px;
            max-width: 1050px;
        }
    </style>

    <?php

}