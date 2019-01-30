<?php
/**
 * Register Post Type.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */
namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Post Types Class
 */
class Post_Types {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_check' ] );
		add_action( 'init', [ $this, 'register_order' ] );
	}

	/**
	 * Register Check post types.
	 */
	public function register_check() {
		$labels = [
			'name'           => esc_html_x( 'Checks', 'Post Type General Name', 'munipay' ),
			'singular_name'  => esc_html_x( 'Check', 'Post Type Singular Name', 'munipay' ),
			'menu_name'      => esc_html__( 'Checks', 'munipay' ),
			'name_admin_bar' => esc_html__( 'Checks', 'munipay' ),
			'add_new_item'   => esc_html__( 'Add New Check', 'munipay' ),
			'new_item'       => esc_html__( 'New Check', 'munipay' ),
			'edit_item'      => esc_html__( 'Edit Check', 'munipay' ),
			'update_item'    => esc_html__( 'Update Check', 'munipay' ),
			'view_item'      => esc_html__( 'View Check', 'munipay' ),
			'search_items'   => esc_html__( 'Search Check', 'munipay' ),
		];

		$args = [
			'label'               => esc_html__( 'Check', 'munipay' ),
			'labels'              => $labels,
			'description'         => esc_html__( 'To create checks.', 'munipay' ),
			'supports'            => [ 'title' ],
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-format-aside',
			'rewrite'             => false,
			'capability_type'     => 'post',
			'exclude_from_search' => true,
			'show_in_rest'        => true,
		];

		register_post_type( 'munipay-check', $args );

		// Metas.
		register_post_meta(
			'munipay-check',
			'payee_name',
			[
				'type'   => 'string',
				'single' => true,
			]
		);
	}

	/**
	 * Register Order post types.
	 */
	public function register_order() {
		$labels = [
			'name'           => esc_html_x( 'Orders', 'Post Type General Name', 'munipay' ),
			'singular_name'  => esc_html_x( 'Order', 'Post Type Singular Name', 'munipay' ),
			'menu_name'      => esc_html__( 'Orders', 'munipay' ),
			'name_admin_bar' => esc_html__( 'Orders', 'munipay' ),
			'add_new_item'   => esc_html__( 'Add New Order', 'munipay' ),
			'new_item'       => esc_html__( 'New Order', 'munipay' ),
			'edit_item'      => esc_html__( 'Edit Order', 'munipay' ),
			'update_item'    => esc_html__( 'Update Order', 'munipay' ),
			'view_item'      => esc_html__( 'View Order', 'munipay' ),
			'search_items'   => esc_html__( 'Search Order', 'munipay' ),
		];

		$args = [
			'label'               => esc_html__( 'Order', 'munipay' ),
			'labels'              => $labels,
			'description'         => esc_html__( 'To create orders.', 'munipay' ),
			'supports'            => [ 'title' ],
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-clipboard',
			'rewrite'             => false,
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		];

		register_post_type( 'munipay-order', $args );
	}
}
