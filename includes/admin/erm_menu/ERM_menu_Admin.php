<?php

// Exit if accessed directly
if (! defined('ABSPATH') ) { exit; }

class ERM_menu_Admin
{
	public function __construct()
	{
		add_filter('manage_erm_menu_posts_columns', array($this, 'columns'));
		add_action('manage_erm_menu_posts_custom_column', array($this, 'columns_display'), 10, 2);

		add_action( 'add_meta_boxes', array($this, 'add_meta_boxes') );

		add_action( 'save_post', array($this, 'save_footer') );
	}

	function add_meta_boxes()
	{
		add_meta_box( 'erm_menu_items', __( 'Menu Items', 'erm' ), array($this, 'menu_meta_box'), 'erm_menu', 'normal', 'high' );
		add_meta_box( 'erm_footer_item', __( 'Footer Menu', 'erm' ), array($this, 'footer_meta_box'), 'erm_menu', 'normal', 'high' );

		add_action( 'erm_meta_box_menu_items', array($this, 'render_menu_meta_box'), 10 );
		add_action( 'erm_meta_box_footer', array($this,'render_footer_meta_box'), 10);
	}

	/**
	 * Table Post type erm_menu add columns
	 *
	 * @since 2.0
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
	 * Table Post type erm_menu Show columns
	 *
	 * @since 1.0
	 * @param $column_name
	 * @param $post_id
	 */
	function columns_display( $column_name, $post_id ) {
		if ( $column_name == 'shortcode' ) {
			echo '[erm_menu id='.$post_id.']';
		}
	}

	/**
	 * Meta box
	 *
	 * @since 2.0
	 */
	function menu_meta_box()
	{
		global $post;
		do_action( 'erm_meta_box_menu_items', $post->ID );
	}

	/**
	 * Meta box
	 *
	 * @since 2.0
	 */
	function footer_meta_box()
	{
		global $post;
		do_action( 'erm_meta_box_footer', $post->ID );
	}

	function render_menu_meta_box($post_id)
	{
		include ERM_PLUGIN_DIR.'/includes/admin/erm_menu/metabox-menu.php';
	}

	function render_footer_meta_box($post_id)
	{
		$content = get_post_meta( $post_id, '_erm_footer_menu', true );

		wp_editor( $content, '_erm_footer_menu', array(
			'wpautop'       => true,
			'media_buttons' => false,
			//'textarea_name' => 'meta_biography',
			'textarea_rows' => 10,
			'teeny'         => true
		) );

		wp_nonce_field( 'erm_footer_metabox_nonce', 'erm_footer_metabox_nonce' );
	}

	function save_footer( $post_id )
	{
		// Check if our nonce is set.
		if ( ! isset( $_POST['erm_footer_metabox_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['erm_footer_metabox_nonce'], 'erm_footer_metabox_nonce' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'erm_menu' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		// Make sure that it is set.
		if ( ! isset( $_POST['_erm_footer_menu'] ) ) {
			return;
		}

		// Sanitize user input.
		//$my_data = sanitize_text_field( $_POST['_erm_footer_menu'] );
		$my_data = $_POST['_erm_footer_menu'];

		// Update the meta field in the database.
		update_post_meta( $post_id, '_erm_footer_menu', $my_data );
	}
}

new ERM_menu_Admin();
