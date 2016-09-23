<?php
global $post;
if ( !isset($menu_post) || empty($menu_post) ) {
    $menu_post = get_post( $post_id );
}
?>

<div class="erm_menu<?php echo ( $show_thumbnails ? '' : ' no-thumbs' ); ?> <?php echo 'erm_menu-id-'.$menu_post->ID; ?>" id="erm_menu-id-<?php echo $menu_post->ID; ?>">

    <?php
        // If it's the erm_menu post then we don't show this
        // because this is for shortcode only
        global $post;
        if ( $post->ID != $menu_post->ID ) {
            ?>
                <h1 class="erm_title"><?php echo $menu_post->post_title; ?></h1>
                <div class="erm_desc"><?php echo do_shortcode($menu_post->post_content); ?></div>
            <?php
        }
    //return;
    ?>


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

                    // Get the post item
                    $the_post = get_post($item_id);
                    $type = get_post_meta($item_id, '_erm_type', true);

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
                }
            }


        ?>
    </ul>

    <div class="erm_footer_desc"><?php

        // Allow footer to have shortcodes
        echo do_shortcode( get_post_meta( $menu_post->ID, '_erm_footer_menu', true ) );

    ?></div>

</div>
