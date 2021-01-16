<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcode
 * @subpackage Shortcode/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace shortcode_public.
 *
 * @package    Shortcode
 * @subpackage Shortcode/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Shortcode_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function s_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, SHORTCODE_DIR_URL . 'public/css/shortcode-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function s_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, SHORTCODE_DIR_URL . 'public/js/shortcode-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 's_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}

	public function add_new_custom_post() {

		register_post_type( 'product_key',
			array(
				'labels'            => array(
					'name'          => __( 'Products', 'convert-text' ),
					'singular_name' => __( 'Product', 'convert-text' ),
				),
				'description'   => 'All products list here.',
				//'taxonomies'    => array( 'product_category', 'product_tag' ),
				'public'        => true,
				'show_ui'        => true,
				'capability_type'  => 'post',
				'menu_position' => 5,
				'supports'      => array( 'title', 'thumbnail', 'editor', 'page-attributes'),
				'has_archive'   => true,
				'rewrite'       => true,
				'rewrite'     => array( 'slug' => 'products' ),
				'add_new'            => _x( 'Add New', 'Events' ),
				'add_new_item'       => __( 'Add New Events' ),
				'edit_item'          => __( 'Edit Events' ),
				'new_item'           => __( 'New Events' ),
				'all_items'          => __( 'All Events' ),
				'view_item'          => __( 'View Events' ),
				'search_items'       => __( 'Search Events' ),
				'not_found'          => __( 'No Events found' ),
				'not_found_in_trash' => __( 'No Events found in the Trash' ), 
				'parent_item_colon'  => '',
				'menu_name'          => 'Products',
				'menu_icon'          => 'dashicons-products',
			)
		);
		register_taxonomy( 'product_category',
			'product_key',
			array(
				'labels'      => array(
					'name'          => __( 'Product Categories', 'convert-text' ),
					'singular_name' => __( 'Product Category', 'convert-text' ),
				),
				'public'      => true,
				'rewrite'     => array( 'slug' => 'product-categories' ),
				'show_in_rest' => true,
				'hierarchical' => true,
				'show_ui'      => true,
				'publicly_queryable' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud' => true,
				'rewrite' => true,
				'query_var' => true
			)
		);

	}
	public function add_new_shortcodes() {
		add_shortcode( 'products', array( $this, 'products_listing') );
	}

	public function products_listing( $atts ) {
		// $atts = [], $content = null, $shortcode_tag
		// do something to $content
		// always return
		$args = shortcode_atts( array(
			'post_type'        => 'product_key',
			'post_status'      => 'publish',
			'cat'              => 'cloths',
			'show'             => '10',
		), $atts );
		// if ( $args['post_type'] === 'product_key' ) {
		// 	$args = array(
		// 		'post_type'        => 'product_key',
		// 		'post_status'      => 'publish',
		// 		'product_category' => $args['cat'],
		// 		'posts_per_page'   => $args['show'],
		// 	);

		// }
		$args = array( 
			'post_type'        => 'product_key',
			'post_status'      => 'publish',
			'posts_count'   => $args['show'],
			'tax_query'        => array(
				array(
					'taxonomy'   => 'product_category',
					'field'        => 'slug',
					'terms'        => $args['cat'],
				),
			),
		);
		$the_query = new WP_Query( $args );
		while ( $the_query->have_posts() ) :

			$the_query->the_post();
			get_template_part( 'template-parts/content/content-excerpt' );

		endwhile;

	}

	/**
	 * Apply_template.
	 *
	 * @param mixed $template Template.
	 * @return mixed
	 */
	public function apply_template( $template ) {
		if ( is_page( 'Shop' )  ) {
			$new_template = SHORTCODE_DIR_PATH . 'public/template/custom_template.php';
			if ( '' != $new_template ) {
				return $new_template;
			}
		}
		if( is_singular( 'product_key' ) ) {
			$new_template = SHORTCODE_DIR_PATH . 'public/template/single-product_key.php';
			if ( '' != $new_template ) {
				return $new_template;
			}
		}
		return $template;
	}

}
