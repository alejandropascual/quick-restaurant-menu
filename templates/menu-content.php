<?php
do_action('erm_menu_before_content_display', $post_id);

$option_thumb_size = ERM()->settings->get('erm_menu_thumb_size');
if (empty($option_thumb_size)) $option_thumb_size = 'medium';

$menu_items = get_post_meta( $post_id, '_erm_menu_items', true );

?>

<div class="erm_menu_content">

    <?php
        if ( !empty($menu_items) )
        {
	        $menu_items = preg_split('/,/', $menu_items);
	        foreach ($menu_items as $item_id)
            {
		        // Is Visible item ?
		        if (get_post_meta($item_id, '_erm_visible', true) != 1) {
                    continue;
                }

                // Query
		        $query_posts = new WP_Query( array(
			        'post_type' => 'erm_menu_item',
			        'p' => $item_id
		        ) );

		        if ($query_posts->have_posts())
                {
	                while($query_posts->have_posts())
	                {
		                $query_posts->the_post();
		                $type = get_post_meta($item_id, '_erm_type', true);
		                if ($type == 'section' || $type == 'product')
		                {
			                do_action('erm_menu_item_before_display', $item_id);
			                if ($type == 'product'){
				                include( 'menu-item-product.php' );
			                } else if ($type == 'section'){
				                include( 'menu-item-section.php' );
			                }
			                do_action('erm_menu_item_after_display', $item_id);
		                }
                    }
	                wp_reset_postdata();
                }

            }
        }
    ?>

</div>

<?php
do_action('erm_menu_after_content_display', $post_id);
