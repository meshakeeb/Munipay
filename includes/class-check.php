<?php
/**
 * User Check
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Check class.
 */
class Check extends Data {

	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'munipay-check';

	/**
	 * Core data for this object. Name value pairs (name + default value).
	 *
	 * @var array
	 */
	protected $defaults = [
		'order_id'                => 0,
		'requester_id'            => 0,

		// Payee.
		'payee_name'              => '',
		'payee_email'             => '',
		'payee_number'            => '',
		'payee_address'           => '',
		'payee_address_2'         => '',
		'payee_phone'             => '',
		'payee_city'              => '',
		'payee_state'             => '',
		'payee_zipcode'           => '',

		// Request.
		'request_reason'          => '',
		'request_description'     => '',
		'request_reason_2'        => '',
		'request_amount'          => '',
		'request_delivery_method' => '',
		'request_delivery_date'   => '',
		'request_document'        => '',

		// Approver.
		'approver'                => '',
		'approved_date'           => '',
		'approver_email'          => '',
		'approver_phone'          => '',

		// Accounts.
		'accounts'                => [
			[
				'cost_center'   => '',
				'network'       => '',
				'activity_code' => '',
				'gl_code'       => '',
				'percentage'    => '',
			],
		],
	];

	/**
	 * Get the check if ID is passed, oterwise the check is new and empty.
	 *
	 * @param int $check Check to read.
	 */
	public function __construct( $check = 0 ) {
		if ( is_numeric( $check ) && $check > 0 ) {
			$this->set_id( $check );
		}

		$this->read();
	}

	/**
	 * Create check.
	 */
	public function create() {
		$values = $this->get_values();
		$title  = [
			__( 'Request', 'munipay' ),
			$values['payee_name'],
			$this->format_price( $values['request_amount'] ),
		];

		$check_id = wp_insert_post(
			[
				'post_title'  => join( ' &ndash; ', array_filter( $title ) ),
				'post_author' => $values['requester_id'],
				'post_parent' => $values['order_id'],
				'post_status' => 'publish',
				'post_type'   => $this->object_type,
				'meta_input'  => [
					// Payee.
					'payee_name'              => $values['payee_name'],
					'payee_email'             => $values['payee_email'],
					'payee_number'            => $values['payee_number'],
					'payee_address'           => $values['payee_address'],
					'payee_address_2'         => $values['payee_address_2'],
					'payee_phone'             => $values['payee_phone'],
					'payee_city'              => $values['payee_city'],
					'payee_state'             => $values['payee_state'],
					'payee_zipcode'           => $values['payee_zipcode'],

					// Request.
					'request_reason'          => $values['request_reason'],
					'request_description'     => $values['request_description'],
					'request_reason_2'        => $values['request_reason_2'],
					'request_amount'          => $values['request_amount'],
					'request_delivery_method' => $values['request_delivery_method'],
					'request_delivery_date'   => $values['request_delivery_date'],

					// Approver.
					'approver'                => $values['approver'],
					'approved_date'           => $values['approved_date'],
					'approver_email'          => $values['approver_email'],
					'approver_phone'          => $values['approver_phone'],

					// Acounts.
					'accounts'                => $values['accounts'],
				],
			]
		);

		if ( false === $check_id ) {
			return;
		}

		$this->set_id( $check_id );
		$this->handle_upload();
	}

	/**
	 * Update check.
	 */
	public function update() {
		$values = $this->get_values();
		$title  = [
			__( 'Request', 'munipay' ),
			$values['payee_name'],
			$this->format_price( $values['request_amount'] ),
		];

		wp_update_post(
			[
				'ID'         => $this->get_id(),
				'post_title' => join( ' &ndash; ', array_filter( $title ) ),
			]
		);
		$meta_input = [
			// Payee.
			'payee_name'              => $values['payee_name'],
			'payee_email'             => $values['payee_email'],
			'payee_number'            => $values['payee_number'],
			'payee_address'           => $values['payee_address'],
			'payee_address_2'         => $values['payee_address_2'],
			'payee_phone'             => $values['payee_phone'],
			'payee_city'              => $values['payee_city'],
			'payee_state'             => $values['payee_state'],
			'payee_zipcode'           => $values['payee_zipcode'],

			// Request.
			'request_reason'          => $values['request_reason'],
			'request_description'     => $values['request_description'],
			'request_reason_2'        => $values['request_reason_2'],
			'request_amount'          => $values['request_amount'],
			'request_delivery_method' => $values['request_delivery_method'],
			'request_delivery_date'   => $values['request_delivery_date'],

			// Approver.
			'approver'                => $values['approver'],
			'approved_date'           => $values['approved_date'],
			'approver_email'          => $values['approver_email'],
			'approver_phone'          => $values['approver_phone'],

			// Acounts.
			'accounts'                => $values['accounts'],
		];

		foreach ( $meta_input as $meta_key => $meta_value ) {
			update_post_meta( $this->get_id(), $meta_key, $meta_value );
		}
		$this->handle_upload();
	}

	/**
	 * Handle file upload.
	 */
	private function handle_upload() {
		if ( ! isset( $_FILES['request_document'] ) || empty( $_FILES['request_document']['name'] ) ) {
			return;
		}

		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		// Delete previous upload.
		if ( $attach_id = get_post_meta( $this->get_id(), 'request_document', true ) ) { // phpcs:ignore
			wp_delete_attachment( $attach_id, true );
		}

		// Upload new document.
		$document = wp_handle_upload( $_FILES['request_document'], array( 'test_form' => false ) );
		if ( $document && ! isset( $document['error'] ) ) {
			$wp_upload_dir = wp_upload_dir();
			$attachment    = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $document['file'] ),
				'post_mime_type' => $document['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $document['file'] ) ),
			);

			$attach_id = wp_insert_attachment( $attachment, $document['file'], $this->get_id() );
			update_post_meta( $this->get_id(), 'request_document', $attach_id );
		}
	}

	/**
	 * Get ceck status.
	 *
	 * @return string
	 */
	public function get_status() {
		$status = $this->get_meta( 'smart_payable_status' );

		return $status ? $status : esc_html__( 'Not Submitted', 'munipay' );
	}

	/**
	 * Get tracking number.
	 */
	public function get_tracking_number() {
		$status = $this->get_meta( 'smart_payable_tracking' );
		if ( ! $status ) {
			return;
		}

		echo '<span class="badge badge-success font-weight-normal">Tracking # ' . $status . '</span>';
	}

	/**
	 * Get box title for accordion.
	 */
	public function get_box_title() {
		if ( $this->get_id() > 0 ) {
			echo $this->object->post_title;
			return;
		}

		_e( 'Request', 'munipay' );
	}

	/**
	 * Get check amount.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return mixed
	 */
	public function get_amount( $context = 'view' ) {
		return $this->format_price( $this->get_meta( 'request_amount' ), $context );
	}

	/**
	 * Get check transaction_fee.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return mixed
	 */
	public function get_transaction_fee( $context = 'view' ) {
		$amount = $this->get_meta( 'request_amount' );
		$amount = ( $amount * 6 ) / 100;

		return $this->format_price( $amount, $context );
	}

	/**
	 * Get delivery method.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return string
	 */
	public function get_delivery_method( $context = 'view' ) {
		$method  = $this->get_meta( 'request_delivery_method' );
		$methods = [
			'1' => esc_html__( 'USPS Priority - 2 Day', 'munipay' ),
			'2' => esc_html__( 'USPS Priority Express Overnight', 'munipay' ),
			'3' => esc_html__( 'Bundle', 'munipay' ),
		];

		return 'view' === $context ? $methods[ $method ] : $method;
	}

	/**
	 * Get delivery fee.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return mixed
	 */
	public function get_delivery_fee( $context = 'view' ) {
		$method  = $this->get_meta( 'request_delivery_method' );
		$methods = [
			'1' => 15,
			'2' => 45,
			'3' => 36,
		];

		return $this->format_price( $methods[ $method ], $context );
	}

	/**
	 * Get postage code for smart payable.
	 *
	 * @return int
	 */
	public function get_postage_code() {
		$method  = $this->get_meta( 'request_delivery_method' );
		$methods = [
			'1' => 3,
			'2' => 8,
			'3' => 8,
		];

		return $methods[ $method ];
	}

	/**
	 * Get attached file path.
	 *
	 * @return string
	 */
	public function get_document_path() {
		$attachment = $this->get_meta( 'request_document' );

		return get_attached_file( $attachment );
	}

	/**
	 * Get filename only.
	 *
	 * @return string
	 */
	public function get_document_name() {
		return \basename( $this->get_document_path() );
	}

	/**
	 * Get check total amount.
	 *
	 * @param string $context Context: view or raw.
	 *
	 * @return mixed
	 */
	public function get_total( $context = 'view' ) {
		$total  = 0;
		$total += $this->get_amount( 'raw' );
		$total += $this->get_delivery_fee( 'raw' );
		$total += $this->get_transaction_fee( 'raw' );

		return $this->format_price( $total, $context );
	}

	/**
	 * Delete check.
	 *
	 * @param int $id Check id to delete.
	 */
	public static function delete( $id ) {
		wp_delete_post( $id, true );
	}
}
