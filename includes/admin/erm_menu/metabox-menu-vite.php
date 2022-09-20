<?php
ob_start();
require ERM_PLUGIN_DIR .  'assets/js/erm_menu/index.html';
$output = ob_get_clean();

$index_js_version = '';
$index_css_version = '';

if (preg_match('#/assets/index\.(.+)\.js#', $output, $matches)){
	$index_js_version = $matches[1];
}
if (preg_match('#/assets/index\.(.+)\.css#', $output, $matches)){
	$index_css_version = $matches[1];
}

$js_dir  = ERM_PLUGIN_URL . 'assets/js/';

?>
<div id="app"></div>
<div id="modal"></div>

<script type="module" crossorigin src="<?php echo $js_dir.'erm_menu/index.'.$index_js_version; ?>.js"></script>
<link rel="stylesheet" href="<?php echo $js_dir.'erm_menu/index.'.$index_css_version; ?>.css">
