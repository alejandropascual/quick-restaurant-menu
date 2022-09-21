<?php
// HEADER -------------------------------------
do_action('erm_menu_before_title_display', $post_id);

// If it's the erm_menu post then we don't show this
// because this is for shortcode only
global $post;
if ( $post->ID != $menu_post->ID ) {
	?>
	<h1 class="erm_title"><?php echo esc_html($menu_post->post_title); ?></h1>
	<div class="erm_desc"><?php echo do_shortcode($menu_post->post_content); ?></div>
	<?php
}

do_action('erm_menu_after_title_display', $post_id);
