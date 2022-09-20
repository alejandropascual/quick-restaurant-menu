<?php
/**
 * Settings
 *
 * From Easy Digital Downloads, changed and added sections to tabs
 * @package     ERM
 * @subpackage  Admin
 * @copyright   Copyright (c) 2022, Alejandro Pascual
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Class ERM_Settings
 *
 * @since 1.0
 */
class ERM_Settings {

	private $options;
	private $set_name;

	public function __construct( $name = 'erm_settings' )
	{
		$this->set_name = $name;
		$this->options = get_option( $this->set_name, array() );
		add_action( 'admin_init', array( $this, 'register_settings') );
	}

	public function register_settings() {

		if ( false == get_option( $this->set_name ) ) {
			add_option( $this->set_name );
		}

		foreach( $this->list_of_settings() as $tab => $settings ) {

			// Manage tab with several sections inside (name_) underscore at the end means sub settings
			if (preg_match('/.+_$/', $tab)) {

				foreach($settings as $sec=>$sub_settings) {
					$this->add_settings_section( $tab.$sec );
					$this->add_settings_fields( $tab.$sec, $sub_settings );
				}
			}
			else {
				$this->add_settings_section( $tab);
				$this->add_settings_fields( $tab, $settings );
			}
		}

		register_setting( $this->set_name, $this->set_name, array( $this, 'sanitize') );
	}

	public function add_settings_section( $tab ) {

		add_settings_section(
			$this->set_name . '_' . $tab,
			__return_null(),
			'__return_false',
			$this->set_name . '_' . $tab
		);
	}

	public function add_settings_fields( $tab, $settings ) {

		foreach( $settings as $key => $option ) {

			$name = isset( $option['name'] ) ? $option['name'] : '';

			$callback = is_callable( array($this, 'show_'.$option['type']) ) ? array($this, 'show_'.$option['type']) : array($this, 'show_missing');

			add_settings_field(
				$this->set_name . '[' . $key . ']',
				$name,
				$callback,
				$this->set_name . '_' . $tab, // Page
				$this->set_name . '_' . $tab, // Section
				array(
					'id'      => $key,
					'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
					'name'    => isset( $option['name'] ) ? $option['name'] : null,
					'section' => $tab,
					'size'    => isset( $option['size'] ) ? $option['size'] : null,
					'options' => isset( $option['options'] ) ? $option['options'] : '',
					'std'     => isset( $option['std'] ) ? $option['std'] : '',
					'callback'=> isset( $option['callback'] ) ? $option['callback'] : '',
					'options' => isset( $option['options'] ) ? $option['options'] : ''
				)
			);
		}
	}

	public function list_of_settings() {

		$list = array(
			'general' => apply_filters( 'erm_settings_general', // Tab general
				array(
					/*'erm_menu_slug' => array(
						'name' => __( 'Menu slug', 'erm' ),
						'desc' => __( 'Use this for changing the slug for the menu Custom Post Type. By default is qr_menu.' , 'erm' ),
						'type' => 'text',
						'size' => 'medium',
						'std' => 'qr_menu'
					),*/
					'erm_currency' => array(
						'name' => __( 'Currency', 'erm' ),
						'desc' => __( 'Add this character to the price. Leave it blank if you don\'t want to display.' , 'erm' ),
						'type' => 'text',
						'size' => 'small',
						'std' => ''
					),
					'erm_currency_position' => array(
						'name' => __( 'Currency position', 'erm' ),
						'desc' => __( '' , 'erm' ),
						'type' => 'radio',
						'size' => 'regular',
						'std' => 'before',
						'options' => array(
							'before' => __('Before price', 'erm'),
							'after' => __('After price', 'erm')
						)
					),
					/*'erm_show_dashboard_menu_items' => array(
						'name' => __( 'Show Menu Items', 'erm' ),
						'desc' => __( 'Display the Dashboard MENU for post type "Menu Items"' , 'erm' ),
						'type' => 'checkbox',
						'size' => '',
						'std' => ''
					),*/
					'erm_menu_thumb_size' => array(
						'name' => __( 'Menu items thumbnail size', 'erm' ),
						'desc' => __( '' , 'erm' ),
						'type' => 'select_size',
						'size' => 'regular',
						'std' => ''
					),
					'erm_custom_css' => array(
						'name' => __( 'Custom CSS', 'erm' ),
						'desc' => __( '' , 'erm' ),
						'type' => 'textarea',
						'size' => 'large',
						'std' => ''
					),
					'erm_custom_css_display' => array(
						'name' => __( 'Insert Custom CSS', 'erm' ),
						'desc' => __( 'Inject the CSS in the Front End' , 'erm' ),
						'type' => 'checkbox',
						'size' => '',
						'std' => ''
					)
				)
			)
		);

		return apply_filters( 'erm_registered_settings', $list );
	}

	public function get( $key , $default = false ) {
		return empty( $this->options[$key] ) ? $default : $this->options[$key];
	}

	public function delete( $key )
	{
		if (isset($this->options[$key]) ) { unset($this->options[$key]);
		}
		$options = get_option(ERM_SETTINGS);
		unset($options[$key]);
		update_option(ERM_SETTINGS, $options);
	}

	public function get_all( $key ) {
		return $this->options;
	}

	public function sanitize( $input ) {

		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return $input;
		}

		// Get tab & section
		parse_str( sanitize_text_field($_POST['_wp_http_referer']), $referrer );

		$saved    = get_option( $this->set_name, array() );
		if( ! is_array( $saved ) ) {
			$saved = array();
		}

		// Get list of settings
		$settings = $this->list_of_settings();
		$tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general'; // TAB, First key by default
		$section  = isset( $referrer['section'] ) ? $referrer['tab'] : ''; // SECTION

		$input = $input ? $input : array();

		// Sanitize tab section
		$input = apply_filters( 'erm_settings_' . $tab . $section . '_sanitize', $input );


		// Ensure checkbox is passed
		if( !empty($settings[$tab]) ) {

			// Has sections inside tab
			if ( preg_match('/.+_$/', $tab ) ) {
				$comprobar = $settings[$tab][$section];
			}
			// No sections inside tab
			else {
				$comprobar = $settings[ $tab ];
			}

			foreach ( $comprobar as $key => $setting ) {
				// Single checkbox
				if ( isset( $settings[ $tab ][ $key ][ 'type' ] ) && 'checkbox' == $settings[ $tab ][ $key ][ 'type' ] ) {
					$input[ $key ] = ! empty( $input[ $key ] );
				}
				// Multicheck list
				if ( isset( $settings[ $tab ][ $key ][ 'type' ] ) && 'multicheck' == $settings[ $tab ][ $key ][ 'type' ] ) {
					if( empty( $input[ $key ] ) ) {
						$input[ $key ] = array();
					}
				}
			}

		}

		// Loop each input to be saved and sanitize
		foreach( $input as $key => $value ) {

			// With sections inside tab
			if ( preg_match('/.+_$/', $tab ) ) {
				$type = isset( $settings[$tab][$section][$key]['type'] ) ? $settings[$tab][$section][$key]['type'] : false;
			}
			// No sections inside tab
			else {
				$type = isset( $settings[$tab][$key]['type'] ) ? $settings[$tab][$key]['type'] : false;
			}

			// Specific sanitize. Ex. erm_settings_sanitize_textarea
			$input[$key]  = apply_filters( ERM_SETTINGS.'_sanitize_'.$type , $value, $key );

			// General sanitize
			$input[$key]  = apply_filters( ERM_SETTINGS.'_sanitize' , $value, $key );
		}

		add_settings_error( 'erm-notices', '', __( 'Settings updated.', 'erm' ), 'updated' );

		return array_merge( $saved, $input );
	}

	// Show fields, depends on type
	//-------------------------------------

	/**
	 * Not found callback function
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_missing( $args ) {

		printf( __( 'The callback function for setting <strong>%s</strong> is missing.', 'erm' ), $args['id'] );
	}

	public function show_esc_label($name, $desc)
	{
		echo '<label for="'.esc_attr($name).'"> '  . esc_html($desc). '</label>';
	}

	/**
	 * Checkbox field
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_checkbox( $args )
	{
		$checked = isset($this->options[$args['id']]) ? checked(1, $this->options[$args['id']], false) : '';
		$name = "{$this->set_name}[{$args['id']}]";
		$desc = $args['desc'];

		echo '<input type="checkbox" id="'.esc_attr($name).'" name="'.esc_attr($name).'" value="1" ' . esc_attr($checked) . '/>';
		$this->show_esc_label($name, $desc);
	}

	/**
	 * Show text field
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_text( $args ) {

		if (isset($this->options[ $args['id'] ]) ) {
			$value = $this->options[$args['id']];
		} else {
			$value = isset($args['std']) ? $args['std'] : '';
		}

		$size = ( isset($args['size']) && ! is_null($args['size']) ) ? $args['size'] : 'regular';
		$name = "{$this->set_name}[{$args['id']}]";
		$desc = $args['desc'];


		echo '<input type="text" class="' . esc_attr($size) . '-text" id="'.esc_attr($name).'" name="'.esc_attr($name).'" value="' . esc_attr(stripslashes($value)) . '"/>';
		$this->show_esc_label($name, $desc);
	}

	/**
	 * Show textarea
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_textarea( $args ) {

		if (isset($this->options[ $args['id'] ]) ) {
			$value = $this->options[$args['id']];
		} else {
			$value = isset($args['std']) ? $args['std'] : '';
		}

		$size = ( isset($args['size']) && ! is_null($args['size']) ) ? $args['size'] : 'regular';
		$name = "{$this->set_name}[{$args['id']}]";
		$desc = $args['desc'];

		echo '<textarea class="'.esc_attr($size).'-text" cols="50" rows="10" id="'.esc_attr($name).'" name="'.esc_attr($name).'">' .
		     esc_textarea(stripslashes($value)) .
		     '</textarea>';
		echo '<div class="qrr-settings-desc">'.esc_html($desc).'</div>';
	}

	/**
	 * Radio field
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_radio( $args ) {

		foreach( $args['options'] as $key => $value ) {
			$checked = false;
			if (isset($this->options[ $args['id'] ]) && $this->options[ $args['id'] ] == $key ) {
				$checked = true;
			} else if (!isset($this->options[ $args['id'] ]) && isset($args['std']) && $args['std'] == $key ) {
				$checked = true;
			}

			$name = "{$this->set_name}[{$args['id']}]";
			$id = "{$this->set_name}[{$args['id']}][{$key}]";
			$desc = $args['desc'];

			echo '<input name="'.esc_attr($name).'" id="'.esc_attr($id).'" type="radio" value="' . esc_attr($key) . '" ' . checked(true, $checked, false) . '/>&nbsp;';
			echo '<label for="'.esc_attr($id).'">' . esc_html($value) . '</label><br/>';
		}
		echo '<p class="description">' . esc_html($desc) . '</p>';
	}

	/**
	 * Select image size field
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_select_size( $args )
	{

		if (isset($this->options[ $args['id'] ]) ) {
			$value = $this->options[$args['id']];
		} else {
			$value = isset($args['std']) ? $args['std'] : '';
		}

		$sizes = erm_get_image_sizes();
		$size = ( isset($args['size']) && ! is_null($args['size']) ) ? $args['size'] : 'regular';
		$name = "{$this->set_name}[{$args['id']}]";
		$desc = $args['desc'];

		echo '<select name="'.esc_attr($name).'" id="'.esc_attr($name).'" >';
		foreach($sizes as $key => $dim) {
			$selected =  ($key == $value ? 'selected': '');
			$size = $key.'('.$dim['width'].'x'.$dim['height'].')';
			echo '<option value="'.esc_attr($key).'" '.esc_attr($selected).'>'.esc_html($size).'</option>';
		}
		echo '</option>';
		echo '<p class="description">' . esc_html($desc) . '</p>';
	}

	/**
	 * Show description
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_desc( $args )
	{

		echo'<p>'.esc_html($args['desc']).'</p>';
	}

	/**
	 * Call a callback function, name has to be in $args['callback']
	 *
	 * @since 1.0
	 * @param $args
	 */
	public function show_callback( $args )
	{
		$func = $args['callback'];
		if (is_callable($func)) {
			call_user_func( $func, $args );
		}
	}
}

/**
 * Sanitize text field
 *
 * @since 1.0
 * @param $value
 * @return mixed
 */
function erm_settings_sanitize_text( $value )
{
	return trim( $value );
}
add_filter('erm_settings_sanitize_text','erm_settings_sanitize_text');

/**
 * Sanitize textarea field
 *
 * @since 1.0
 * @param $value
 * @return mixed
 */
function erm_settings_sanitize_textarea( $value )
{
	return $value;
}
add_filter('erm_settings_sanitize_textarea','erm_settings_sanitize_textarea');

