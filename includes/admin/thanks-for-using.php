<?php

function erm_get_purchase_link( $url = '', $campaign = 'free' ) {
    if ( $url == '' ) {
        $url = 'https://thingsforrestaurants.com/downloads/quick-restaurant-menu-pro/';
    }
    $url = add_query_arg( array( 'utm_medium' => 'link', 'utm_campaign' => $campaign, 'utm_source' => urlencode( home_url() ) ), $url );
    return apply_filters( 'erm_get_purchase_link', esc_url_raw( $url ) );
}


add_action('erm_before_settings_page', 'erm_before_settings_page');

function erm_before_settings_page() {
    ?>

    <div class="about-wrap">
        <h1>Thanks for using <a href="https://thingsforrestaurants.com/downloads/quick-restaurant-menu-plugin/">Quick Restaurant Menu</a>!</h1>
        <div class="about-text">The future of <a href="https://thingsforrestaurants.com/downloads/quick-restaurant-menu-plugin-pro/">Quick Restaurant Menu</a> relies on happy customers supporting ThingsForRestaurants by purchasing upgraded versions. If you like this free version of QRM please consider <a href="<?php echo erm_get_purchase_link(); ?>">purchasing the PRO version</a>.</div>
    </div>

    <style>
        .about-wrap .notice,
        .about-wrap div.error,
        .about-wrap div.updated,
        .wrap .notice,
        .wrap div.error,
        .wrap div.updated,
        .update-nag {
            display: none!important;
        }
        .about-wrap h1 {
            margin: .2em 200px 0 0;
            padding: 0;
            color: #32373c;
            line-height: 1.2em;
            font-size: 2.8em;
            font-weight: 400;
        }
        .about-wrap h1 a {
            color: inherit;
            text-decoration: none;
        }
        .about-wrap .about-text {
            margin: 1em 200px 1em 0;
            min-height: 60px;
            color: #555d66;
            font-weight: 400;
            line-height: 1.6em;
            font-size: 19px;
        }
    </style>

    <?php
}