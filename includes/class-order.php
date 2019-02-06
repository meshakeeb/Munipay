<?php
/**
 * User Order
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Order class.
 */
class Order extends Data {

	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'munipay-order';

	/**
	 * Core data for this object. Name value pairs (name + default value).
	 *
	 * @var array
	 */
	protected $defaults = array(
		'requester_id'          => 0,
		'request_date'          => '',
		'requester_email'       => '',
		'requester_name'        => '',
		'requester_phone'       => '',
		'requester_signum'      => '',
		'requester_cost_center' => '',
	);

	/**
	 * Order checks will be stored here, sometimes before they persist in the DB.
	 *
	 * @var array
	 */
	public $checks = array();

	/**
	 * Get the order if ID is passed, oterwise the check is new and empty.
	 *
	 * @param int $order Order to read.
	 */
	public function __construct( $order = 0 ) {
		if ( is_numeric( $order ) && $order > 0 ) {
			$this->set_id( $order );
		}

		$this->read();
	}

	/**
	 * Read post.
	 */
	public function read() {
		$this->current_user = wp_get_current_user();

		if ( $this->get_id() < 1 ) {
			return;
		}

		$this->object = WP_Post::get_instance( $this->get_id() );

		// Get checks.
		$check_ids = get_posts(
			[
				'fields'         => 'ids',
				'posts_per_page' => -1,
				'post_type'      => 'munipay-check',
				'post_parent'    => $this->get_id(),
			]
		);

		foreach ( $check_ids as $id ) {
			$this->checks[] = new Check( $id );
		}
	}

	/**
	 * Create order.
	 */
	public function create() {
		$values   = $this->get_values();
		$order_id = wp_insert_post(
			[
				'post_author' => $values['requester_id'],
				'post_date'   => date( 'Y-m-d H:i:s', strtotime( $values['request_date'] ) ),
				'post_title'  => $values['request_name'],
				'post_status' => 'publish',
				'post_type'   => $this->object_type,
				'meta_input'  => [
					'requester_email'       => $values['requester_email'],
					'requester_name'        => $values['requester_name'],
					'requester_phone'       => $values['requester_phone'],
					'requester_signum'      => $values['requester_signum'],
					'requester_cost_center' => $values['requester_cost_center'],
				],
			]
		);

		if ( false === $order_id ) {
			return;
		}

		// Update title and slug.
		wp_update_post(
			[
				'ID'         => $order_id,
				'post_title' => 'Order # ' . $order_id,
				'post_name'  => 'order-' . $order_id,
			]
		);

		$this->set_id( $order_id );
		update_user_meta( $values['requester_id'], 'current_order', $order_id );
	}

	/**
	 * Mark order completed.
	 */
	public function completed() {
		delete_user_meta( $this->current_user->ID, 'current_order' );
		do_action( 'order_completed', $this );
	}

	/**
	 * Get order date.
	 *
	 * @return string
	 */
	public function get_order_date() {
		return $this->get_id() > 1 ? mysql2date( get_option( 'date_format' ), $this->object->post_date ) : date( get_option( 'date_format' ) );
	}

	/**
	 * Get requester email.
	 *
	 * @return string
	 */
	public function get_requester_email() {
		return $this->get_id() > 1 ? $this->get_meta( 'requester_email' ) : $this->current_user->get( 'user_email' );
	}

	/**
	 * Get requester name.
	 *
	 * @return string
	 */
	public function get_requester_name() {
		return $this->get_id() > 1 ? $this->get_meta( 'requester_name' ) : $this->current_user->get( 'display_name' );
	}

	/**
	 * Get requester phone.
	 *
	 * @return string
	 */
	public function get_requester_phone() {
		return $this->get_id() > 1 ? $this->get_meta( 'requester_phone' ) : $this->current_user->get( 'phone' );
	}

	/**
	 * Get requester signum.
	 *
	 * @return string
	 */
	public function get_requester_signum() {
		return $this->get_id() > 1 ? $this->get_meta( 'requester_signum' ) : $this->current_user->get( 'signum' );
	}

	/**
	 * Get requester cost_center.
	 *
	 * @return string
	 */
	public function get_requester_cost_center() {
		return $this->get_id() > 1 ? $this->get_meta( 'requester_cost_center' ) : $this->current_user->get( 'cost_center' );
	}

	/**
	 * Get order total amount.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return mixed
	 */
	public function get_total( $context = 'view' ) {
		$total = 0;
		foreach ( $this->checks as $check ) {
			$total += $check->get_total( 'raw' );
		}

		return $this->format_price( $total, $context );
	}

	/**
	 * Get order delivery charges.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return mixed
	 */
	public function get_delivery_charges( $context = 'view' ) {
		$total = 0;
		foreach ( $this->checks as $check ) {
			$total += $check->get_delivery_fee( 'raw' );
		}

		return $this->format_price( $total, $context );
	}

	/**
	 * Get order transaction charges.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return mixed
	 */
	public function get_transaction_charges( $context = 'view' ) {
		$total = 0;
		foreach ( $this->checks as $check ) {
			$total += $check->get_transaction_fee( 'raw' );
		}

		return $this->format_price( $total, $context );
	}

	/**
	 * Has any checks.
	 *
	 * @return boolean
	 */
	public function has_checks() {
		return ! empty( $this->checks );
	}

	/**
	 * Get current in-progress order for current user.
	 *
	 * @return Order
	 */
	public static function get_current_order() {
		$current_user = wp_get_current_user();
		return new Order( $current_user->current_order );
	}
}
