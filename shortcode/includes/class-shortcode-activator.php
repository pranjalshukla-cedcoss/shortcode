<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Shortcode
 * @subpackage Shortcode/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Shortcode
 * @subpackage Shortcode/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Shortcode_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function shortcode_activate() {



		$args = array(
			'post_type' => 'page',
			'post_status' => 'publish',
			'post_title'  => 'Shop',
		);
		$args1 = array(
			'post_type' => 'page',
			'post_status' => 'trash',
			'post_title'  => 'Shop',
		);

		$a = get_page_by_title( $args['post_title'] );
		$a1 = get_page_by_title( $args1['post_title'] );
		if ( null == $a ) {
			$args = array(
				'post_type' => 'page',
				'post_title' => 'Shop',
				'post_status' => 'publish',
				//'post_content' => '[products cat="cloths" show="10"]',
			);
			wp_insert_post( $args, true );
			//add_action( 'admin_menu', 'add_menu_item' );
		} elseif ( null != $a1 ) {
			$args = array(
				'post_type' => 'page',
				'post_title' => 'Shop',
				'post_status' => 'publish',
				//'post_content' => '[products cat="cloths" show="10"]',
			);
			wp_insert_post( $args, true );
			wp_delete_post( $a1->ID, true );
		}

		// $args = array (
		// 	'post_type' => 'page',
		// 	'post_status' => 'publish',
		// 	'post_title'  => 'new',
		// );

		// $data = get_post( $args );
		// print_r( $data );


	}
	// public function add_menu_item() {
	// 	add_menu_page( 'Shop', 'primary',  );
	//   }

}
