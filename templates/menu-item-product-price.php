<div class="erm_product_price">
	<?php

	$prices = get_post_meta( $item_id, '_erm_prices', true );

	do_action('erm_mnu_item_prices_before_display', $item_id);

	if ( !empty($prices) ) { ?>
		<div class="erm_product_list_prices">
			<?php foreach( $prices as $price ) { ?>
				<div class="erm_product_list_price">
					<span class="price_name"><?php echo esc_html($price['name']); ?></span>
					<span class="price_price"><?php echo apply_filters('erm_filter_price', esc_html($price['value']), $item_id); ?></span>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	do_action('erm_mnu_item_prices_after_display', $item_id);

	?>

</div>
