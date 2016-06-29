<div class="erm_product_price">
    <?php
    $prices = get_post_meta( $item_id, '_erm_prices', true );
    if ( !empty($prices) ) { ?>
        <ul>
            <?php foreach( $prices as $price ) { ?>
                <li>
                    <span class="name"><?php echo $price['name']; ?></span>
                    <span class="price"><?php echo apply_filters('erm_filter_price', $price['value']); ?></span>
                </li>
            <?php } ?>
        </ul>
    <?php }
    ?>
</div>