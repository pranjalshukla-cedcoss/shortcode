<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcode
 * @subpackage Shortcode/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Shortcode
 * @subpackage Shortcode/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Shortcode_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function s_admin_enqueue_styles( $hook ) {

		wp_enqueue_style( 'mwb-s-select2-css', SHORTCODE_DIR_URL . 'admin/css/shortcode-select2.css', array(), time(), 'all' );

		wp_enqueue_style( $this->plugin_name, SHORTCODE_DIR_URL . 'admin/css/shortcode-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function s_admin_enqueue_scripts( $hook ) {

		wp_enqueue_script( 'mwb-s-select2', SHORTCODE_DIR_URL . 'admin/js/shortcode-select2.js', array( 'jquery' ), time(), false );

		wp_register_script( $this->plugin_name . 'admin-js', SHORTCODE_DIR_URL . 'admin/js/shortcode-admin.js', array( 'jquery', 'mwb-s-select2' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name . 'admin-js',
			's_admin_param',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'reloadurl' => admin_url( 'admin.php?page=shortcode_menu' ),
			)
		);

		wp_enqueue_script( $this->plugin_name . 'admin-js' );
	}

	/**
	 * Adding settings menu for shortcode.
	 *
	 * @since    1.0.0
	 */
	public function s_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'shortcode' ), __( 'MakeWebBetter', 'shortcode' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), SHORTCODE_DIR_URL . 'admin/images/mwb-logo.png', 15 );
			$s_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $s_menus ) && ! empty( $s_menus ) ) {
				foreach ( $s_menus as $s_key => $s_value ) {
					add_submenu_page( 'mwb-plugins', $s_value['name'], $s_value['name'], 'manage_options', $s_value['menu_link'], array( $s_value['instance'], $s_value['function'] ) );
				}
			}
		}
	}


	/**
	 * shortcode s_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function s_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'            => __( 'shortcode', 'shortcode' ),
			'slug'            => 'shortcode_menu',
			'menu_link'       => 'shortcode_menu',
			'instance'        => $this,
			'function'        => 's_options_menu_html',
		);
		return $menus;
	}


	/**
	 * shortcode mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require SHORTCODE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * shortcode admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function s_options_menu_html() {

		include_once SHORTCODE_DIR_PATH . 'admin/partials/shortcode-admin-display.php';
	}

	/**
	 * shortcode admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $s_settings_general Settings fields.
	 */
	public function s_admin_general_settings_page( $s_settings_general ) {
		$s_settings_general = array(
			array(
				'title' => __( 'Text Field Demo', 'shortcode' ),
				'type'  => 'text',
				'description'  => __( 'This is text field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_text_demo',
				'value' => '',
				'class' => 's-text-class',
				'placeholder' => __( 'Text Demo', 'shortcode' ),
			),
			array(
				'title' => __( 'Number Field Demo', 'shortcode' ),
				'type'  => 'number',
				'description'  => __( 'This is number field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_number_demo',
				'value' => '',
				'class' => 's-number-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Password Field Demo', 'shortcode' ),
				'type'  => 'password',
				'description'  => __( 'This is password field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_password_demo',
				'value' => '',
				'class' => 's-password-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Textarea Field Demo', 'shortcode' ),
				'type'  => 'textarea',
				'description'  => __( 'This is textarea field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_textarea_demo',
				'value' => '',
				'class' => 's-textarea-class',
				'rows' => '5',
				'cols' => '10',
				'placeholder' => __( 'Textarea Demo', 'shortcode' ),
			),
			array(
				'title' => __( 'Select Field Demo', 'shortcode' ),
				'type'  => 'select',
				'description'  => __( 'This is select field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_select_demo',
				'value' => '',
				'class' => 's-select-class',
				'placeholder' => __( 'Select Demo', 'shortcode' ),
				'options' => array(
					'INR' => __( 'Rs.', 'shortcode' ),
					'USD' => __( '$', 'shortcode' ),
				),
			),
			array(
				'title' => __( 'Multiselect Field Demo', 'shortcode' ),
				'type'  => 'multiselect',
				'description'  => __( 'This is multiselect field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_multiselect_demo',
				'value' => '',
				'class' => 's-multiselect-class mwb-defaut-multiselect',
				'placeholder' => __( 'Multiselect Demo', 'shortcode' ),
				'options' => array(
					'INR' => __( 'Rs.', 'shortcode' ),
					'USD' => __( '$', 'shortcode' ),
				),
			),
			array(
				'title' => __( 'Checkbox Field Demo', 'shortcode' ),
				'type'  => 'checkbox',
				'description'  => __( 'This is checkbox field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_checkbox_demo',
				'value' => '',
				'class' => 's-checkbox-class',
				'placeholder' => __( 'Checkbox Demo', 'shortcode' ),
			),

			array(
				'title' => __( 'Radio Field Demo', 'shortcode' ),
				'type'  => 'radio',
				'description'  => __( 'This is radio field demo follow same structure for further use.', 'shortcode' ),
				'id'    => 's_radio_demo',
				'value' => '',
				'class' => 's-radio-class',
				'placeholder' => __( 'Radio Demo', 'shortcode' ),
				'options' => array(
					'yes' => __( 'YES', 'shortcode' ),
					'no' => __( 'NO', 'shortcode' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 's_button_demo',
				'button_text' => __( 'Button Demo', 'shortcode' ),
				'class' => 's-button-class',
			),
		);
		return $s_settings_general;
	}

	/**
	 * Add_new_custom_post
	 *
	 * @return void
	 */


}
