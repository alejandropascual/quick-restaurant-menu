<?php

global $post;

if ( !isset($menu_post) || empty($menu_post) ) {
	$menu_post = get_post( $post_id);
}

?>

<div class="erm_menu <?php echo 'erm_menu-id-'.$menu_post->ID; ?>"
     id="erm_menu-id-<?php echo $menu_post->ID; ?>"
>
    <?php
    // HEADER -------------------------------------
    include 'menu-header.php';

    // CONTENT -------------------------------------
    include 'menu-content.php';

    // FOOTER -------------------------------------
    include 'menu-footer.php';
    ?>

</div>
