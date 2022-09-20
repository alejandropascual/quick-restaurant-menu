<div class="erm_footer_desc"><?php

	// Allow footer to have shortcodes
	echo do_shortcode( get_post_meta( $menu_post->ID, '_erm_footer_menu', true ) );

?></div>
