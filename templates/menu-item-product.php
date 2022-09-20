<?php
$visible = get_post_meta( $item_id, '_erm_visible', true );
if ( !$visible ) return;

$has_thumbnail = has_post_thumbnail( $item_id );
?>

<div class="erm_product <?php echo ( ($has_thumbnail && $show_thumbnails) ? 'with_image' : 'no_image'); ?>">

    <?php
    if ( $has_thumbnail && $show_thumbnails ) {
        include 'menu-item-product-image.php';
    }
    ?>

    <div class="erm_title_price_desc">

        <div class="erm_title_price">
            <h3 class="erm_product_title"><?php the_title() ?></h3>
            <?php include 'menu-item-product-price.php'; ?>
        </div>

        <?php include 'menu-item-product-desc.php'; ?>

    </div>

</div>


