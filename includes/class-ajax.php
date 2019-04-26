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
		add_action( 'init', [ $this, 'download_report' ] );
		$this->ajax( 'create_order', 'create_order' );
		$this->ajax( 'update_check', 'update_check' );
		$this->ajax( 'delete_check', 'delete_check' );
	}

	/**
	 * Download CSV.
	 */
	public function download_report() {
		if ( ! isset( $_POST['download_report'] ) ) {
			return;
		}

		header( 'Content-Type: application/csv' );
		header( 'Content-Disposition: attachment; filename=munipay-report-' . date( 'Y-m-d-H-i-s' ) . '.csv' );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		$smart   = Smart_Payables::create();
		$reports = $smart->get_reports();
		if ( false === $reports['response'] ) {
			return;
		}

		$payments = $reports['response']['payments']['payment'];
		if ( isset( $payments['payment_id'] ) ) {
			$payments   = [];
			$payments[] = $reports['response']['payments']['payment'];
		}
		$payments = array_reverse( $payments );

		echo join( ',', array_keys( $this->clean_row( $payments[0] ) ) ) . PHP_EOL;

		foreach ( $payments as $payment ) {
			echo join( ',', $this->clean_row( $payment ) ) . PHP_EOL;
		}

		exit;
	}

	/**
	 * Clean report row.
	 *
	 * @param array $row Row to clean.
	 *
	 * @return array
	 */
	private function clean_row( $row ) {
		unset(
			$row['@attributes'],
			$row['method_id'],
			$row['date_exported']
		);

		foreach ( [ 'date_sent', 'trans_id', 'address2' ] as $col ) {
			if ( is_array( $row[ $col ] ) ) {
				unset( $row[ $col ] );
			}
		}

		return $row;
	}

	/**
	 * Update check status.
	 *
	 * @link http://mydomain.com/?action=status_update&id=&status=Processing&transaction_id
	 */
	public function status_update() {

		if ( ! isset( $_GET['action'] ) || 'status_update' !== $_GET['action'] ) {
			return;
		}

		if ( ! isset( $_GET['id'], $_GET['status'], $_GET['transaction_id'] ) ) {
			return;
		}

		$check = new Check( absint( $_GET['transaction_id'] ) );
		if ( empty( $check->get_object() ) ) {
			wp_send_json( [ 'error' => 'No check found' ] );
		}
		$payment_id        = absint( $_GET['id'] );
		$stored_payment_id = absint( $check->get_payment_id() );

		if ( $payment_id !== $stored_payment_id ) {
			wp_send_json( [ 'error' => 'Payment id didn\'t matched' ] );
		}

		$new_status = $_GET['status'];
		$old_status = $check->get_meta( 'smart_payable_status' );
		$check->set_meta( 'smart_payable_status', $new_status );
		do_action( 'munipay_payment_status_updated', $check, $old_status, $new_status );

		// Check number.
		if ( isset( $_GET['check_number'] ) && ! empty( $_GET['check_number'] ) ) {
			$check->set_meta( 'smart_payable_check_number', absint( $_GET['check_number'] ) );
		}

		// Get Track info.
		$smart                = Smart_Payables::create();
		$smart->data['payee'] = $check->get_meta( 'payee_name' );
		$smart->data['zip']   = $check->get_meta( 'payee_zipcode' );

		$message  = 'Status updated to ' . $new_status . '.';
		$response = $smart->send( 'payments/track_payment' );
		if ( false === $response ) {
			wp_send_json( [ 'message' => $message ] );
		}

		foreach ( $response as $payment ) {
			if ( absint( $payment['id'] ) === $payment_id ) {
				$check->set_meta( 'smart_payable_tracking', $payment['tracking'] );
				do_action( 'munipay_payment_tracking_found', $check, $payment['tracking'], $payment );
				$message .= ' Tracking number found # ' . $payment['tracking'];
				break;
			}
		}

		wp_send_json( [ 'message' => $message ] );
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

		if ( ! isset( $_POST['order_id'] ) ) {
			$this->success(
				[
					'message' => 'Check successfully delete.',
				]
			);
		}

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
