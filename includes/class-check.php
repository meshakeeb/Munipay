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
		'status'                  => 'waiting',
		'date_created'            => null,
		'date_modified'           => null,
		'total'                   => 0,
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
	 * Meta fields.
	 *
	 * @var array
	 */
	private $metas = [
		// Payee.
		'payee_name',
		'payee_email',
		'payee_number',
		'payee_address',
		'payee_address_2',
		'payee_phone',
		'payee_city',
		'payee_state',
		'payee_zipcode',

		// Request.
		'request_reason',
		'request_description',
		'request_reason_2',
		'request_amount',
		'request_delivery_method',
		'request_delivery_date',
		'request_document',

		// Approver.
		'approver',
		'approved_date',
		'approver_email',
		'approver_phone',

		// Accounts.
		'accounts',
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
	 * Get box title for accordion.
	 */
	public function get_box_title() {
		$parts = [
			__( 'Request', 'munipay' ),
			$this->get_meta( 'payee_name' ),
			$this->format_price( $this->get_meta( 'request_amount' ) ),
		];

		echo join( ' &ndash; ', array_filter( $parts ) );
	}
}
