<div class="erm_product_price">
    <?php

    $prices = get_post_meta( $item_id, '_erm_prices', true );

    do_action('erm_mnu_item_prices_before_display', $item_id);

    if ( !empty($prices) ) { ?>
        <ul>
            <?php foreach( $prices as $price ) { ?>
                <li>
                    <span class="name"><?php echo $price['name']; ?></span>
                    <span class="price"><?php echo apply_filters('erm_filter_price', $price['value'], $item_id); ?></span>
                </li>
            <?php } ?>
        </ul>
    <?php
    }

    do_action('erm_mnu_item_prices_after_display', $item_id);

    ?>

</div>