<?php
$visible = get_post_meta( $item_id, '_erm_visible', true );
if ( !$visible ) return;
?>

<?php $has_thumbnail = has_post_thumbnail( $item_id ); ?>

<li class="erm_product <?php echo ( ($has_thumbnail && $show_thumbnails) ? 'with_image' : 'no_image'); ?>">

    <?php
    if ( $has_thumbnail && $show_thumbnails ) {

        $image_id = get_post_thumbnail_id( $item_id );
        $src_thumb = erm_get_image_src( $image_id, $option_thumb_size );
        $src_full = erm_get_image_src( $image_id, 'full' );
        $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
        $post_image = get_post( $image_id );
        $caption = $post_image->post_excerpt;
        $desc = $post_image->post_content;

        echo '<a class="image-popup" target="_blank" href="'.$src_full.'" data-caption="'.esc_attr($caption).'" data-desc="'.esc_attr($desc).'">
                <img class="erm_product_image" alt="'.esc_attr($alt).'" src="'.$src_thumb.'">
              </a>';

    } else {

        //echo '<div class="erm_product_image"></div>';

    }
    ?>

    <h3 class="erm_product_title"><?php echo $the_post->post_title; ?></h3>

    <?php

    if ( $price_position == 'top' ) {

        include 'menu-item-product-price.php';
        include 'menu-item-product-desc.php';

    } else if ( $price_position == 'bottom' ) {

        include 'menu-item-product-desc.php';
        include 'menu-item-product-price.php';

    }

    ?>

    <div class="clear"></div>

</li>