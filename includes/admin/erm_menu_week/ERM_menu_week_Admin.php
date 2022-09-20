<?php

// Exit if accessed directly
if (! defined('ABSPATH') ) { exit; }

class ERM_menu_week_Admin
{
	public function __construct()
	{
		add_filter('manage_erm_menu_week_posts_columns', array($this, 'columns'));
		add_action('manage_erm_menu_week_posts_custom_column', array($this, 'columns_display'), 10, 2);

		add_action( 'add_meta_boxes', array($this, 'add_meta_boxes') );
	}

	function add_meta_boxes()
	{
		add_meta_box( 'erm_menu_weekly', __( 'Weekly menu rules', 'erm' ), array($this, 'menu_week_meta_box'), 'erm_menu_week', 'normal', 'high' );
		add_meta_box( 'erm_menu_weekly_shortcode', __( 'Shortcode', 'erm' ), array($this, 'menu_week_shortcode_meta_box'), 'erm_menu_week', 'side' );

		add_action( 'erm_render_menu_week_meta_box', array($this, 'render_menu_week_meta_box'), 10 );
		add_action( 'erm_render_menu_week_shortcode_meta_box', array($this, 'render_menu_week_shortcode_meta_box'), 10 );
	}

	/**
	 * Table Post type erm_menu_week add columns
	 *
	 * @since 1.0
	 * @param $defaults
	 * @return array
	 */
	function columns( $defaults ) {
		$result = array();
		$result['cb'] = $defaults['cb'];
		$result['title'] = $defaults['title'];
		$result['shortcode'] = __('Shortcode', 'erm');
		unset( $defaults['cb'] );
		unset( $defaults['title'] );
		foreach( $defaults as $key => $value ) {
			$result[ $key ] = $value;
		}
		return $result;
	}

	/**
	 * Table Post type erm_menu_week Show columns
	 *
	 * @since 1.0
	 * @param $column_name
	 * @param $post_id
	 */
	function columns_display( $column_name, $post_id ) {
		if ( $column_name == 'shortcode' ) {
			echo '[erm_menu_week id='.$post_id.']';
		}
	}

	function menu_week_meta_box()
	{
		global $post;
		do_action( 'erm_render_menu_week_meta_box', $post->ID );
	}

	function render_menu_week_meta_box($post_id)
	{
		include ERM_PLUGIN_DIR.'/includes/admin/erm_menu_week/metabox-menu-week.php';
	}

	function menu_week_shortcode_meta_box()
	{
		global $post;
		do_action( 'erm_render_menu_week_shortcode_meta_box', $post->ID );
	}

	function render_menu_week_shortcode_meta_box(){
		global $post;

		$sc = '[erm_menu_week id='.$post->ID.']';
		echo '<p>'.__('Use this shortcode for displaying the menu in other pages','erm').'</p>';
		echo '<div style="background-color: #F1F1F1;padding: 10px;font-size: 16px;">'; print_r( $sc ); echo '</div>';
	}
}

new ERM_menu_week_Admin();
