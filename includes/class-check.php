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
	 * Create order.
	 */
	public function create() {
		$values   = $this->get_values();
		$title    = [
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
					'request_document'        => $values['request_document'],

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

		$this->set_id( $order_id );
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
	 * @return string
	 */
	public function get_delivery_method( $context = 'view' ) {
		$method  = $this->get_meta( 'request_delivery_method' );
		$methods = [
			'1' => 'USPS Priority - 2 Day',
			'2' => 'USPS Priority Express Overnight',
			'3' => 'Bundle',
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
