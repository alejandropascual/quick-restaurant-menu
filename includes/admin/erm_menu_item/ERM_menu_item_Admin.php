<?php

// Exit if accessed directly
if (! defined('ABSPATH') ) { exit; }

class ERM_menu_item_Admin
{
	public function __construct()
	{
		add_filter('manage_erm_menu_item_posts_columns', array($this, 'columns'));
		add_action('manage_erm_menu_item_posts_custom_column', array($this, 'columns_display'), 10, 2);
	}

	/**
	 * Table Post type erm_menu_item add columns
	 *
	 * @since 1.0
	 * @param $defaults
	 * @return array
	 */
	function columns( $defaults ) {
		$result = array();
		$result['cb'] = $defaults['cb'];
		$result['thumb'] = __('Image', 'erm');
		unset( $defaults['cb'] );
		foreach( $defaults as $key => $value ) {
			$result[ $key ] = $value;
		}
		return $result;
	}

	/**
	 * Table Post type erm_menu_item Show columns
	 *
	 * @since 1.0
	 * @param $column_name
	 * @param $post_id
	 */
	function columns_display( $column_name, $post_id ) {

		if ( $column_name == 'thumb' ) {
			if ( get_post_meta( $post_id, '_erm_type', true ) == 'section' ) {
				echo '<strong>'.__('SECTION', 'erm').'</strong>';
			} else {
				$image_id = get_post_thumbnail_id( $post_id );
				if ($image_id) {
					$src = erm_get_image_src( $image_id );
					echo '<a href="'.get_edit_post_link($post_id).'"><img src="'.$src.'" style="width:80px;height:auto;"></a>';
				}
			}
		}
	}
}

new ERM_menu_item_Admin();
