<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class APS_Plugin {

    private $templates_dir = ERM_PLUGIN_DIR.'/includes/admin/templates/';

    private function __contruct(){
        $this->load_deactivation();
    }

    private function load_deactivation() {
        global $pagenow;
        if ( 'plugins.php' === $pagenow ) {
            add_action( 'admin_footer', 'aps_show_deativation_feedback_modal' );
        }
    }

    private function show_deactivation_feddback_modal() {

        require $this->templates_dir . 'deactivate-feedback-modal.php';

    }
}

new APS_Plugin();