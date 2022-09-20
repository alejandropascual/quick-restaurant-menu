<?php

$image_id = get_post_thumbnail_id( $item_id );

$src_thumb = erm_get_image_src( $image_id, $option_thumb_size );
$src_full = erm_get_image_src( $image_id, 'full' );
$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
$post_image = get_post( $image_id );
$caption = $post_image->post_excerpt;
$desc = $post_image->post_content;


$html_image =  '<a class="image-popup" style="width: 140px;" target="_blank" href="'.$src_full.'" data-caption="'.esc_attr($caption).'" data-desc="'.esc_attr($desc).'">
                <img class="erm_product_image" alt="'.esc_attr($alt).'" src="'.$src_thumb.'">
              </a>';

echo apply_filters( 'erm_menu_item_image_display', $html_image, $item_id );
