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
		add_action( 'init', [ $this, 'status_update' ] );
		$this->ajax( 'create_order', 'create_order' );
		$this->ajax( 'update_check', 'update_check' );
		$this->ajax( 'delete_check', 'delete_check' );
	}

	/**
	 * Update check status.
	 *
	 * @link http://mydomain.com/?action=status_update&&id=&status=Processing&transaction_id
	 */
	public function status_update() {

		if ( ! isset( $_GET['action'] ) || 'status_update' !== $_GET['action'] ) {
			return;
		}

		if ( ! isset( $_GET['id'], $_GET['status'], $_GET['transaction_id'] ) ) {
			return;
		}

		$check             = new Check( absint( $_GET['transaction_id'] ) );
		$payment_id        = absint( $_GET['id'] );
		$stored_payment_id = absint( $check->get_meta( 'smart_payable_payment_id' ) );

		if ( $payment_id !== $stored_payment_id ) {
			return;
		}

		$check->set_meta( 'smart_payable_status', $_GET['status'] );
		do_action( 'payment_status_updated', $check, $_GET['status'] );

		// Get Track info.
		$smart                = new Smart_Payables( null );
		$smart->data['payee'] = $check->get_meta( 'payee_name' );
		$smart->data['zip']   = $check->get_meta( 'payee_zipcode' );

		$response = $smart->send( 'payments/track_payment' );
		if ( false === $response ) {
			return;
		}

		foreach ( $response as $payment ) {
			if ( absint( $payment['id'] ) === $payment_id ) {
				$check->set_meta( 'smart_payable_tracking', $payment['tracking'] );
				do_action( 'payment_tracking_found', $check, $payment );
				break;
			}
		}
	}

	/**
	 * Create order.
	 */
	public function create_order() {
		$this->verify_nonce( 'munipay_security_salt' );

		// Create Order.
		$order = new Order( 0 );
		$order->save();

		$this->success(
			[
				'orderID' => $order->get_id(),
				'message' => 'Order successfully saved.',
			]
		);
	}

	/**
	 * Create Check.
	 */
	public function update_check() {
		$this->verify_nonce( 'munipay_security_salt' );

		// Create Check.
		$check = new Check( $_POST['check_id'] );
		$check->save();

		$this->success(
			[
				'checkID' => $check->get_id(),
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

		$this->success(
			[
				'orderTotal' => $order->get_total(),
				'message'    => 'Check successfully delete.',
			]
		);
	}
}
