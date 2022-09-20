<?php
global $post;

$js_dir  = ERM_PLUGIN_URL . 'assets/js/';

$menu_items = erm_get_menu_items_data($post->ID);
$erm_vars = array(
	'menu_id'       => $post->ID,
	'menu_items'    => $menu_items,
	'notices' => array(
		'alert_delete' => __('Are you sure to delete?', 'erm'),
		'alert_confirm' => __('Yes, delete it!', 'erm')
	)
)

?>

<script>
    window.erm_vars = <?php echo json_encode($erm_vars); ?>
</script>

<div id="app"></div>
<div id="modal"></div>

<script type="module" crossorigin src="<?php echo $js_dir.'erm_menu/manifest.js'; ?>"></script>
<script type="module" crossorigin src="<?php echo $js_dir.'erm_menu/vendor.js'; ?>"></script>
<script type="module" crossorigin src="<?php echo $js_dir.'erm_menu/main.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo $js_dir.'erm_menu/style.css'; ?>">
