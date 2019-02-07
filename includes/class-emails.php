<?php
/**
 * Emails
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

defined( 'ABSPATH' ) || exit;

/**
 * Emails class.
 */
class Emails {

	use \Munipay\Traits\Hooker;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		$this->action( 'munipay_order_completed', 'admin_new_order' );
		$this->action( 'munipay_order_completed', 'user_new_order', 10, 2 );
		$this->action( 'munipay_payment_status_updated', 'status_updated', 10, 3 );
		$this->action( 'munipay_payment_tracking_found', 'tracking_number_generated', 10, 3 );
	}

	/**
	 * Send admin new order notification.
	 *
	 * @param Order $order Order instance.
	 *
	 * @return bool success
	 */
	public function admin_new_order( $order ) {
		$subject = sprintf( 'Ericsson check requests for %s', $order->get_order_date() );

		foreach ( $this->get_admins() as $user ) {
			$message = $this->get_message(
				'admin-new-order',
				[
					'user'  => $user,
					'order' => $order,
				]
			);
			$this->send(
				$user->get( 'user_email' ),
				$subject,
				$message,
				'',
				[ get_template_directory() . '/tmp/order_' . $order->get_id() . '_full.csv' ]
			);
		}

		return true;
	}

	/**
	 * Send user new order notification.
	 *
	 * @param Order   $order Order instance.
	 * @param WP_User $user  User instance.
	 *
	 * @return bool success
	 */
	public function user_new_order( $order, $user ) {
		$subject = sprintf( 'Credit card charges for check requests from %s', $order->get_order_date() );
		$message = $this->get_message( 'user-new-order', [ 'order' => $order ] );

		return $this->send(
			$user->get( 'user_email' ),
			$subject,
			$message,
			'',
			[ get_template_directory() . '/tmp/order_' . $order->get_id() . '_full.csv' ]
		);
	}

	/**
	 * Send user status update notification.
	 *
	 * @param Check  $check      Check instance.
	 * @param string $old_status Check old status.
	 * @param string $new_status Check new status.
	 *
	 * @return bool success
	 */
	public function status_updated( $check, $old_status, $new_status ) {
		$user    = get_user_by( 'ID', $check->get_object()->post_author );
		$subject = sprintf( 'Status update for check # %s', $check->get_id() );
		$message = $this->get_message( 'user-status-update', compact( 'check', 'old_status', 'new_status', 'user' ) );

		return $this->send(
			$user->get( 'user_email' ),
			$subject,
			$message
		);
	}

	/**
	 * Send user tracking number notification.
	 *
	 * @param Check  $check     Check instance.
	 * @param string $tracking Tracking number.
	 * @param array  $payment  Payment information.
	 *
	 * @return bool success
	 */
	public function tracking_number_generated( $check, $tracking, $payment ) {
		$user    = get_user_by( 'ID', $check->get_object()->post_author );
		$subject = sprintf( 'Tracking number for check # %s', $check->get_id() );
		$message = $this->get_message( 'user-tracking-number', compact( 'check', 'tracking', 'payment', 'user' ) );

		return $this->send(
			$user->get( 'user_email' ),
			$subject,
			$message
		);
	}

	/**
	 * Get email content type.
	 *
	 * @return string
	 */
	public function get_content_type() {
		return 'text/html';
	}

	/**
	 * Send an email.
	 *
	 * @param string $to Email to.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $headers Email headers.
	 * @param array  $attachments Email attachments.
	 * @return bool success
	 */
	private function send( $to, $subject, $message, $headers = '', $attachments = [] ) {
		$this->filter( 'wp_mail_content_type', 'get_content_type' );

		$return = wp_mail( $to, $subject, $message, $headers, $attachments );

		$this->remove_filter( 'wp_mail_content_type', 'get_content_type' );

		return $return;
	}

	/**
	 * Get template part
	 *
	 * @param string $template Template name.
	 * @param array  $args     Arguments to use.
	 */
	private function get_message( $template, $args ) {
		extract( $args ); // phpcs:ignore

		ob_start();
		include locate_template( 'templates/emails/' . $template . '.php' );

		return ob_get_clean();
	}

	/**
	 * Get admins.
	 *
	 * @return array
	 */
	private function get_admins() {
		$dan = get_user_by( 'email', 'dan@boltmedia.ca' );
		if ( defined( 'MUNIPAY_SANDBOX' ) && MUNIPAY_SANDBOX ) {
			return [ $dan ];
		}

		return get_users(
			[
				'role'    => 'administrator',
				'exclude' => [ $dan->ID ],
			]
		);
	}
}
