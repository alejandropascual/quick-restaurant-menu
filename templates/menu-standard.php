<?php

// Template called from the post view and from the shortcode

global $post;
if ( !isset($menu_post) || empty($menu_post) ) {
    $menu_post = get_post( $post_id );
}
?>

<div class="erm_menu <?php echo ( $price_inline ? 'price_inline' : '' ); ?> <?php echo ( $show_thumbnails ? '' : 'no-thumbs' ); ?> <?php echo 'erm_menu-id-'.$menu_post->ID; ?>" id="erm_menu-id-<?php echo $menu_post->ID; ?>">

    <?php

        do_action('erm_menu_before_title_display', $post_id);

        // If it's the erm_menu post then we don't show this
        // because this is for shortcode only

        global $post;
        if ( $post->ID != $menu_post->ID ) {
            ?>
                <h1 class="erm_title"><?php echo $menu_post->post_title; ?></h1>
                <div class="erm_desc"><?php echo do_shortcode($menu_post->post_content); ?></div>
            <?php
        }

    do_action('erm_menu_after_title_display', $post_id);

    //return;
    ?>

    <?php do_action('erm_menu_before_content_display', $post_id); ?>

    <ul class="erm_menu_content">
        <?php

            // Thumb size
            $option_thumb_size = ERM()->settings->get('erm_menu_thumb_size');
            if (empty($option_thumb_size)) $option_thumb_size = 'medium';

            // Menu items
            $menu_items = get_post_meta( $post_id, '_erm_menu_items', true );
            if ( !empty($menu_items) ) {

                $menu_items = preg_split('/,/', $menu_items);

                foreach ($menu_items as $item_id) {

                    // Visible item
                    if (get_post_meta($item_id, '_erm_visible', true) != 1) continue;

                    // Query
                    $args = array(
                        'post_type' => 'erm_menu_item',
                        'p' => $item_id
                    );

                    $query_posts = new WP_Query( $args );

                    if ($query_posts->have_posts())
                    {

                        while($query_posts->have_posts()){

                            $query_posts->the_post();

                            $type = get_post_meta($item_id, '_erm_type', true);

                            if ($type == 'section' || $type == 'product')
                            {
                                do_action('erm_menu_item_before_display', $item_id);

                                switch ($type) {

                                    case 'section':
                                        include( 'menu-item-section.php' );
                                        break;
                                    case 'product':
                                        include( 'menu-item-product.php' );
                                        break;
                                    default:
                                        break;

                                }

                                do_action('erm_menu_item_after_display', $item_id);
                            }

                        }

                        wp_reset_postdata();
                    }

                }
            }


        ?>
    </ul>

    <?php do_action('erm_menu_after_content_display', $post_id); ?>

    <div class="erm_footer_desc"><?php

        // Allow footer to have shortcodes
        echo do_shortcode( get_post_meta( $menu_post->ID, '_erm_footer_menu', true ) );

    ?></div>

</div>
