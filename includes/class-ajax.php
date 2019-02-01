<?php
/**
 * Ajax functionality for the plugin.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */
namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax Class
 */
class Ajax {

	use \Munipay\Traits\Ajax;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->ajax( 'create_order', 'create_order' );
		$this->ajax( 'create_check', 'create_check' );
		$this->ajax( 'delete_check', 'delete_check' );
	}

	/**
	 * Create order.
	 */
	public function create_order() {
		$this->verify_nonce( 'munipay_security_salt' );

		// Create Order
		$order    = new Order( 0 );
		$order_id = $order->save();

		$this->success(
			[
				'orderID' => $order_id,
				'message' => 'Order successfully saved.',
			]
		);
	}

	/**
	 * Create Check.
	 */
	public function create_check() {
		$this->verify_nonce( 'munipay_security_salt' );

		// Create Check
		$check    = new Check( 0 );
		$check_id = $check->save();

		$this->success(
			[
				'checkID' => $check_id,
				'message' => 'Check successfully saved.',
			]
		);
	}

	/**
	 * Delete Check.
	 */
	public function delete_check() {
		$this->verify_nonce( 'munipay_security_salt' );

		// Delete Check.
		Check::delete( $_POST['check_id'] );

		// Get order total.
		$order = new Order( $_POST['order_id'] );
		unset( $order->checks[0] );

		$this->success(
			[
				'orderTotal' => $order->get_total(),
				'message'    => 'Check successfully delete.',
			]
		);
	}
}
