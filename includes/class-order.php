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
	public  $checks = array();

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
}
