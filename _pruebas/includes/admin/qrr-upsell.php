<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class QRR_Upsell {

    private $affiliate;
    private $class_exists = 'Quick_Restaurant_Reservations';
    private $learn_more = 'https://thingsforrestaurants.com/quick-restaurant-reservations/';
    private $logo = 'https://thingsforrestaurants.com/wp-content/themes/vendd_thingsforrestaurants/assets/svg/chef2.svg';
    private $youtube_embed = 'https://www.youtube.com/embed/uEoS1mr9eqs?rel=0&amp;controls=0&amp;showinfo=0&amp;autoplay=1';
    private $first_page = 'edit.php?post_type=qrr_booking&page=qrr-addons';
    //private $plugin = 'quick-restaurant-reservations';
    private $plugin = 'regenerate-thumbnails';

    function __construct( $affiliate = '' ) {

        $this->affiliate = $affiliate;
        add_action( 'init', array( $this, 'init_hooks' ) );

        add_action( 'wp_ajax_qrr_upsell_installer', array( $this, 'install_qrr' ) );
        add_action( 'wp_ajax_qrr_upsell_dismiss', array( $this, 'dismiss_qrr_notice' ) );
    }

    public function init_hooks( ) {

        if ( class_exists( $this->class_exists ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        add_action( 'admin_notices', array( $this, 'activation_notice' ) );
    }

    public function activation_notice() {

        if ( $this->is_dismissed() ) {
            return;
        }
        
        ?>
        <div class="updated" id="qrr-upsell-prompt">
            <div class="qrr-upsell-logo">
                <img src="<?php echo $this->logo; ?>" width="272" height="71" alt="qrr Logo">
            </div>
            <div class="qrr-upsell-text">
                <h2>Quick Restaurant Reservations is here!</h2>

                <p>QRR is the next generation restaurant reservations plugin for WordPress.</p>
            </div>
            <div class="qrr-upsell-cta">
                <button id="qrr-upsell-prompt-btn" class="button"><?php _e( 'Install Now', 'erm' ); ?></button>
                &nbsp;<a href="#" class="learn-more" data-tube="">Learn More</a>
            </div>
            <button type="button" class="notice-dismiss" style="padding: 3px;" title="<?php _e( 'Dismiss this notice.', 'erm' ); ?>">
                <span class="screen-reader-text"><?php _e( 'Dismiss this notice.','erm' ); ?></span>
            </button>
        </div>

        <div class="qrr-upsell-modal" id="qrr-upsell-modal">
            <a class="close">
                &times;
                <span class="screen-reader-text">Close modal window</span>
            </a>
            <div class="video-wrap">
                <iframe id="qrr-upsell-modal-iframe" width="1280" height="720" src="" frameborder="0" allowfullscreen></iframe>
            </div>

            <div class="learn-more">
                <a href="<?php echo $this->learn_more_link(); ?>" target="_blank" class="button button-primary">Learn more about QRR</a>
            </div>
        </div>

        <div class="qrr-upsell-modal-backdrop" id="qrr-upsell-modal-backdrop"></div>

        <style type="text/css">
            div#qrr-upsell-prompt * {
                box-sizing: border-box;
            }

            div#qrr-upsell-prompt {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                justify-content: flex-start;
                align-content: flex-start;
                align-items: flex-start;
                position: relative;
                border: none;
                margin: 5px 0 15px;
                padding: 0 0 0 10px;
                background: #223435;
            }

            .qrr-upsell-logo {
                margin: 0;
                height: 71px;
                order: 0;
                flex: 0 1 272px;
                align-self: auto;
                padding-left: 15px;
            }

            .qrr-upsell-text {
                background: #223537;
                color: #fff;
                padding: 0;
                height: 71px;
                margin-left: -35px;
                order: 0;
                flex: 1 1 auto;
                align-self: auto;
            }

            .qrr-upsell-text h2 {
                color: #fff;
                margin: 10px 0;
            }

            .qrr-upsell-cta {
                text-align: center;
                order: 0;
                flex: 0 1 220px;
                align-self: auto;
                padding-top: 20px;
                vertical-align: middle;
                height: 71px;
                line-height: 28px;
                background: white;
            }

            .qrr-upsell-modal {
                background: #fff;
                position: fixed;
                top: 5%;
                bottom: 5%;
                right: 10%;
                left: 10%;
                display: none;
                box-shadow: 0 1px 20px 5px rgba(0, 0, 0, 0.1);
                z-index: 160000;
            }

            .qrr-upsell-modal .video-wrap {
                position: relative;
                padding-bottom: 56.25%; /* 16:9 */
                padding-top: 25px;
                height: 0;
            }

            .qrr-upsell-modal .video-wrap iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .qrr-upsell-modal .learn-more {
                position: absolute;
                bottom: 0;
                right: 10px;
                background: #fff;
                padding: 10px;
                border-radius: 3px;
            }

            .qrr-upsell-modal a.close {
                position: absolute;
                top: 20px;
                right: -60px;
                font: 300 1.71429em "dashicons" !important;
                content: '\f335';
                display: inline-block;
                padding: 10px 20px 0 20px;
                z-index: 5;
                text-decoration: none;
                height: 40px;
                cursor: pointer;
                background: #000;
                color: #fff;
                border-radius: 50%;
            }

            .qrr-upsell-modal-backdrop {
                position: fixed;
                z-index: 159999;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                min-height: 360px;
                background: #000;
                opacity: .7;
                display: none;
            }

            .qrr-upsell-modal.show,
            .qrr-upsell-modal-backdrop.show {
                display: block;
            }
        </style>

        <script type="text/javascript">
        
            (function($) {

                var wrapper = $('#qrr-upsell-prompt'),
                    modal = $('#qrr-upsell-modal'),
                    modalBackdrop = $('#qrr-upsell-modal-backdrop'),
                    iframe = $('#qrr-upsell-modal-iframe');

                wrapper.on('click', '#qrr-upsell-prompt-btn', function (e) {
                    var self = $(this);

                    e.preventDefault();
                    self.addClass('install-now updating-message');
                    self.text('<?php echo esc_js( 'Installing...' ); ?>');

                    wp.ajax.send( 'qrr_upsell_installer', {
                        data: {
                            _wpnonce: '<?php echo wp_create_nonce('qrr_upsell_installer'); ?>'
                        },

                        success: function(response) {
                            self.text('<?php echo esc_js( 'Installed' ); ?>');
                            window.location.href = '<?php echo admin_url( $this->first_page ); ?>';
                        },

                        error: function(error) {
                            self.removeClass('install-now updating-message');
                            alert( error );
                        },

                        complete: function() {
                            self.attr('disabled', 'disabled');
                            self.removeClass('install-now updating-message');
                        }
                    });
                });

                wrapper.on('click', '.notice-dismiss', function (e) {
                    e.preventDefault();

                    var self = $(this);

                    wp.ajax.send( 'qrr_upsell_dismiss' );

                    self.closest('.updated').slideUp('fast', function() {
                        self.remove();
                    });
                });

                wrapper.on('click', 'a.learn-more', function(e) {
                    e.preventDefault();

                    modal.addClass('show');
                    modalBackdrop.addClass('show');

                    iframe.attr( 'src', '<?php echo $this->youtube_embed; ?>' );
                });

                $('body').on('click', '.qrr-upsell-modal a.close', function(e) {
                    e.preventDefault();

                    modal.removeClass('show');
                    modalBackdrop.removeClass('show');

                    iframe.attr( 'src', '' );
                });
                
                
            })(jQuery);
        
        </script>
        
        <?php
    }

    public function is_dismissed() {
        return 'yes' == get_option( 'qrr_upsell_dismiss', 'no' );
    }

    public function dismiss_notice() {
        update_option( 'qrr_upsell_dismiss', 'yes' );
    }

    public function learn_more_link() {
        $link = $this->learn_more;

        if ( ! empty( $this->affiliate ) ) {
            $link = add_query_arg( array( 'ref' => $this->affiliate ), $link );
        }

        return $link;
    }

    // Plugin installation fails
    public function fail_on_error( $thing ) {
        if ( is_wp_error( $thing ) ) {
            wp_send_json_error( $thing->get_error_message() );
        }
    }

    
    // Ajax install plugin
    public function install_qrr() {
        check_ajax_referer( 'qrr_upsell_installer' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'You don\'t have permission to install the plugins' ) );
        }

        $qrr_status = $this->install_plugin( $this->plugin, $this->plugin.'.php' );
        $this->fail_on_error( $qrr_status );

        $this->dismiss_notice();

        if ( ! empty( $this->affiliate ) ) {
            update_option( '_qrr_aff_ref', $this->affiliate );
        }

        wp_send_json_success();
    }

    // Install and activate plugin
    public function install_plugin( $slug, $file ) {
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $plugin_basename = $slug . '/' . $file;

        // if exists and not activated
        if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_basename ) ) {
            return activate_plugin( $plugin_basename );
        }

        // seems like the plugin doesn't exists. Download and activate it
        $upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );

        $api      = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
        $result   = $upgrader->install( $api->download_link );

        if ( is_wp_error( $result ) ) {
            return $result;
        }

        return activate_plugin( $plugin_basename );
    }

    public function dismiss_qrr_notice() {
        $this->dismiss_notice();
        wp_send_json_success();
    }

}
