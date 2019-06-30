<?php
/**
 * CRON Jobs.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * CRON Jobs Class
 */
class Crons {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'wp_loaded', [ $this, 'register_jobs' ] );
		add_action( 'munipay_daily_tracking_finder', [ $this, 'find_tracking_number' ] );
	}

	/**
	 * Register cron jobs if not already added.
	 */
	public function register_jobs() {
		if ( ! wp_next_scheduled( 'munipay_daily_tracking_finder' ) ) {
			wp_schedule_event( time(), 'daily', 'munipay_daily_tracking_finder' );
		}
	}

	/**
	 * Find tracking number for checks.
	 */
	public function find_tracking_number() {
		$smart = Smart_Payables::create();
		foreach ( $this->get_check_ids() as $check_id ) {
			$check = new Check( $check_id );

			$smart->reset();
			$smart->data['payee'] = $check->get_meta( 'payee_name' );
			$smart->data['zip']   = $check->get_meta( 'payee_zipcode' );

			$response = $smart->send( 'payments/track_payment' );
			if ( false === $response || empty( $response ) ) {
				continue;
			}

			foreach ( $response as $payment ) {
				if ( empty( $payment['tracking'] ) ) {
					continue;
				}

				$check = $this->get_check_by_payment_id( $payment['id'] );
				if ( false === $check || ! empty( $check->get_meta( 'smart_payable_tracking' ) ) ) {
					continue;
				}

				// Update Status.
				$new_status = $payment['status_name'];
				$old_status = $check->get_meta( 'smart_payable_status' );

				if ( $old_status !== $new_status ) {
					$check->set_meta( 'smart_payable_status', $new_status );
				}

				// Update Tracking number.
				$check->set_meta( 'smart_payable_tracking', $payment['tracking'] );
			}
		}
	}

	/**
	 * Get checks ids.
	 *
	 * @return array
	 */
	private function get_check_ids() {
		add_filter( 'posts_where', [ $this, 'modify_the_posts_where' ] );
		$checks = new \WP_Query(
			[
				'posts_per_page' => -1,
				'post_type'      => 'munipay-check',
				'meta_query'     => [
					'relation' => 'OR',
					[
						'key'     => 'smart_payable_tracking',
						'compare' => 'NOT EXISTS',
					],
					[
						'key'     => 'smart_payable_tracking',
						'value'   => 'is_null',
						'compare' => '=',
					],
				],

				// Query performance optimization.
				'fields'         => 'ids',
				'no_found_rows'  => true,
			]
		);

		remove_filter( 'posts_where', [ $this, 'modify_the_posts_where' ] );

		return $checks->posts;
	}

	/**
	 * Modify where clause.
	 *
	 * @param string $clause Clause.
	 *
	 * @return string
	 */
	public function modify_the_posts_where( $clause = '' ) {
		return str_replace( "meta_value = 'is_null'", 'meta_value IS NULL', $clause );
	}

	/**
	 * Get check by payment id.
	 *
	 * @param int $payment_id Payment id.
	 *
	 * @return Check
	 */
	private function get_check_by_payment_id( $payment_id ) {
		global $wpdb;
		$check_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",
				'smart_payable_payment_id',
				$payment_id
			)
		);

		if ( is_null( $check_id ) ) {
			return false;
		}

		return new Check( $check_id );
	}
}
