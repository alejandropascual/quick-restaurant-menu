<li class="erm_section">

    <?php do_action('erm_menu_item_section_before_display', $item_id); ?>

    <h2 class="erm_section_title"><?php the_title(); ?></h2>
    <div class="erm_section_desc"><?php the_content(); ?></div>

    <?php do_action('erm_menu_item_section_after_display', $item_id); ?>

</li>