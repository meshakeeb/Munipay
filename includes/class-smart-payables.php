<?php
/**
 * Smart_Payables payment processor.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Smart_Payables class.
 */
class Smart_Payables {

	/**
	 * Checkout form instance.
	 *
	 * @var Checkout
	 */
	private $form = null;

	/**
	 * Hold request data.
	 *
	 * @var array
	 */
	private $data = [];

	/**
	 * Class constructor.
	 *
	 * @param Checkout $form Checkout instance.
	 */
	public function __construct( $form ) {
		$this->form = $form;
		$this->data = $this->get_credentials();
	}

	/**
	 * Payment upload.
	 */
	public function process() {
		$this->create_csv();

		// CSV file to attached.
		$order  = $this->form->order;
		$upload = get_template_directory() . '/tmp/order_' . $order->get_id() . '.csv';

		// Set data.
		$this->data['method_id']      = 2;
		$this->data['userfile']       = curl_file_create( $upload, 'application/csv' );
		$this->data['insert_include'] = 'multiple';

		// Attachments.
		foreach ( $order->checks as $index => $check ) {
			if ( $document = $check->get_document_path() ) { // phpcs:ignore
				$this->data[ 'insert_file_multiple[' . $index . ']' ] = curl_file_create( $document, 'application/pdf' );
			}
		}

		$response = $this->send( 'payments/upload' );
		if ( false === $response ) {
			return false;
		}

		// Save Smart Payable data for order and checks.
		update_post_meta( $order->get_id(), 'smart_payable_fileid', $response['@attributes']['fileid'] );

		$payments = $response['payments']['payment'];
		if ( isset( $payments['payment_id'] ) ) {
			$payments   = [];
			$payments[] = $response['payments']['payment'];
		}

		foreach ( $payments as $payment ) {
			$check_id = $payment['trans_id'];
			update_post_meta( $check_id, 'smart_payable_payment_id', $payment['payment_id'] );
			update_post_meta( $check_id, 'smart_payable_status', $payment['status'] );
		}

		return true;
	}

	/**
	 * Send CURL request.
	 *
	 * @param string $endpoint Endpoint to hit on the api.
	 */
	private function send( $endpoint ) {
		$curl = curl_init( $this->get_endpoint() . $endpoint );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $this->data );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $curl );
		curl_close( $curl );

		$has_error = false;
		$content   = simplexml_load_string( utf8_encode( $response ) );
		foreach ( libxml_get_errors() as $error ) {
			$has_error = true;
			$this->form->errors->add( $error->code, $error->message );
		}

		if ( $has_error ) {
			libxml_clear_errors();
			return false;
		}

		$content = \json_encode( $content );
		$content = \json_decode( $content, true );

		if ( isset( $content['@attributes'] ) && '0' === $content['@attributes']['status'] ) {
			$this->form->errors->add( 'api_error', 'Smart Payables Failed: ' . $content['error'] );
			return false;
		}

		return $content;
	}

	/**
	 * Create CSV.
	 */
	private function create_csv() {
		$order = $this->form->order;
		$user  = wp_get_current_user();

		// Filenames.
		$full   = get_template_directory() . '/tmp/order_' . $order->get_id() . '_full.csv';
		$upload = get_template_directory() . '/tmp/order_' . $order->get_id() . '.csv';

		// Write stream.
		$full_stream   = fopen( $full, 'w' );
		$upload_stream = fopen( $upload, 'w' );

		if ( false === $full_stream ) {
			die( 'Failed to open temporary file' );
		}

		// Create Headers.
		fputcsv(
			$upload_stream,
			[
				'',
				'Client Transaction ID',
				'Payee Name',
				'Reference',
				'Payment Amount',
				'Address 1',
				'Address 2',
				'City',
				'State',
				'Zip/Postal Code',
				'Country',
				'Signum code',
				'Home cost center',
				'Memo',
				'Postage Code',
				'Insert PDF Filename',
			]
		);

		fputcsv(
			$full_stream,
			[
				'',
				'Request Date',
				'Signum code',
				'Home cost center',
				'Payee Name',
				'Supplier #',
				'Email',
				'Phone',
				'Address 1',
				'Address 2',
				'City',
				'State',
				'Zip',
				'Country',
				'Reference',
				'Memo',
				'Payment Amount',
				'Cost Center',
				'Network',
				'Activity code',
				'GL',
				'% of Total',
				'Requester Name',
				'Requester Email',
				'Requester Phone',
				'Approver Name',
				'Approver Email',
				'Approver Phone',
				'Postage Code',
				'Date Needed',
				'Insert PDF File Name',
			]
		);

		$count = 0;
		foreach ( $order->checks as $check ) {
			$count++;

			// Upload CSV.
			fputcsv(
				$upload_stream,
				[
					$count,
					$check->get_id(),
					$check->get_meta( 'payee_name' ),
					$check->get_meta( 'request_reason' ),
					$check->get_amount( 'raw' ),
					$check->get_meta( 'payee_address' ),
					$check->get_meta( 'payee_address_2' ),
					$check->get_meta( 'payee_city' ),
					$check->get_meta( 'payee_state' ),
					$check->get_meta( 'payee_zipcode' ),
					$user->get( 'payment_country' ),
					$order->get_requester_signum(),
					$order->get_requester_cost_center(),
					$check->get_meta( 'request_description' ),
					$check->get_postage_code(),
					$check->get_document_name(),
				]
			);

			// Full CSV.
			$accounts = $check->get_meta( 'accounts' );
			fputcsv(
				$full_stream,
				[
					$count,
					$order->get_order_date(),
					$order->get_requester_signum(),
					$order->get_requester_cost_center(),
					$check->get_meta( 'payee_name' ),
					$check->get_meta( 'payee_number' ),
					$check->get_meta( 'payee_email' ),
					$check->get_meta( 'payee_phone' ),
					$check->get_meta( 'payee_address' ),
					$check->get_meta( 'payee_address_2' ),
					$check->get_meta( 'payee_city' ),
					$check->get_meta( 'payee_state' ),
					$check->get_meta( 'payee_zipcode' ),
					$user->get( 'payment_country' ),
					$check->get_meta( 'request_reason' ),
					$check->get_meta( 'request_description' ),
					$check->get_amount( 'raw' ),
					join( ',', wp_list_pluck( $accounts, 'cost_center' ) ),
					join( ',', wp_list_pluck( $accounts, 'network' ) ),
					join( ',', wp_list_pluck( $accounts, 'activity_code' ) ),
					join( ',', wp_list_pluck( $accounts, 'gl_code' ) ),
					join( ',', wp_list_pluck( $accounts, 'percentage' ) ),
					$order->get_requester_name(),
					$order->get_requester_email(),
					$order->get_requester_phone(),
					get_user_meta( $check->get_meta( 'approver' ), 'display_name', true ),
					$check->get_meta( 'approver_email' ),
					$check->get_meta( 'approver_phone' ),
					$check->get_postage_code(),
					$check->get_meta( 'request_delivery_date' ),
					wp_get_attachment_url( $check->get_meta( 'request_document' ) ),
				]
			);
		}

		fclose( $full_stream );
		fclose( $upload_stream );
	}

	/**
	 * Get service end-point.
	 *
	 * @return string
	 */
	private function get_endpoint() {
		return MUNIPAY_SANDBOX ? 'https://apisb.smartpayables.com/' : 'https://api.smartpayables.com/';
	}

	/**
	 * Get credentials.
	 *
	 * @return array
	 */
	private function get_credentials() {
		$credentials = [
			'sandbox'    => [
				'client_id' => '497',
				'akey'      => 'NEkJZEhKGhfLpmNaVxSrOjuCDOmnkl/nxEGnnqgDoQajMVH4IRY3Ppe7Z9OfUT3Zq6Gsrxp+909kpikVVZuvZ+hm5PtFR5hgWT/L2Pm27pN423nXTzyjN4ZcX4V+bmkq',
			],
			'production' => [
				'client_id' => '497',
				'akey'      => 'H5v735noNZX9Kq1HvhVHx/Bor+w1jUWuFkFZXLh08nWmiJOGbIYUxOigHYxeKr86CTVkqLvwAx25DANGTQrwMF0nBypimTXNzPvBcxLZOnCbQS+1MXYGn/5Ldh5vwypT',
			],
		];

		return MUNIPAY_SANDBOX ? $credentials['sandbox'] : $credentials['production'];
	}
}
