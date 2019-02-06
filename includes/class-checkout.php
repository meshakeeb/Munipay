<?php
/**
 * Checkout form.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

use WP_Error;
use Munipay\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout class.
 */
class Checkout {

	use Hooker;

	/**
	 * Hold errors.
	 *
	 * @var WP_Error
	 */
	public $errors = null;

	/**
	 * Hold order for screen.
	 *
	 * @var Order
	 */
	public $order = false;

	/**
	 * Hold form data.
	 *
	 * @var array
	 */
	public $data = [];

	/**
	 * The class constructor.
	 */
	public function __construct() {
		$this->errors = new WP_Error;
		add_shortcode( 'checkout', [ $this, 'form' ] );
		$this->action( 'template_redirect', 'save' );
		$this->action( 'order_completed', 'new_order_notification' );
		$this->action( 'order_completed', 'clean_order_data', 999 );
	}

	/**
	 * Render form.
	 */
	public function form() {
		ob_start();

		$this->get_template( 'header' );

		if ( isset( $_GET['completed'] ) ) {
			$this->get_template( 'thank-you' );
			return ob_get_clean();
		}

		?>

		<div class="container mt-5 mb-5">

			<?php Form::display_errors( $this->errors ); ?>

			<h3 class="mb-5"><?php esc_html_e( 'Checkout', 'munipay' ); ?></h3>

			<form action="" method="post" class="needs-validation" novalidate>

				<div class="row">

					<div class="col-md-4 offset-md-1 order-md-2 mb-4">

						<h4 class="d-flex justify-content-between align-items-center mb-3">
							<span class="text-muted"><?php esc_html_e( 'Your cart', 'munipay' ); ?></span>
							<span class="badge badge-secondary badge-pill"><?php echo count( $this->order->checks ); ?></span>
						</h4>

						<ul class="list-group">
							<?php foreach ( $this->order->checks as $check ) : ?>
							<li class="list-group-item d-flex justify-content-between lh-condensed">
								<div>
									<h6 class="my-0"><?php echo $check->get_meta( 'payee_name' ); ?></h6>
									<small class="text-muted"><em><?php echo esc_html_x( 'for', 'checkout form', 'munipay' ); ?></em> <?php echo $check->get_meta( 'request_reason' ); ?></small>
								</div>
								<span class="text-muted"><?php echo $check->get_amount(); ?></span>
							</li>
							<?php endforeach; ?>

							<li class="list-group-item d-flex justify-content-between bg-light">
								<div class="text-success">
									<h6 class="my-0"><?php esc_html_e( 'Delivery Charges', 'munipay' ); ?></h6>
								</div>
								<span class="text-success"><?php echo $this->order->get_delivery_charges(); ?></span>
							</li>

							<li class="list-group-item d-flex justify-content-between bg-light">
								<div class="text-success">
									<h6 class="my-0"><?php esc_html_e( 'Transation Charges', 'munipay' ); ?></h6>
								</div>
								<span class="text-success"><?php echo $this->order->get_transation_charges(); ?></span>
							</li>

							<li class="list-group-item d-flex justify-content-between">
								<span><?php esc_html_e( 'Total (USD)', 'munipay' ); ?></span>
								<strong><?php echo $this->order->get_total(); ?></strong>
							</li>

						</ul>

						<?php wp_nonce_field( 'munipay_checkout_payment' ); ?>
						<input type="hidden" name="action" value="munipay_checkout">
						<button class="btn btn-primary btn-lg btn-block mt-4" type="submit"><?php esc_html_e( 'Place Order', 'munipay' ); ?></button>

					</div>

					<div class="col-md-7 order-md-1">

						<?php $this->get_template( 'billing-form' ); ?>

					</div>

				</div>

			</form>

		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Save form.
	 */
	public function save() {
		$this->order = Order::get_current_order();

		if ( empty( $_POST['action'] ) || 'munipay_checkout' !== $_POST['action'] || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'munipay_checkout_payment' ) ) {
			return;
		}

		$user_id = get_current_user_id();
		if ( $user_id <= 0 ) {
			return;
		}

		if ( false === $this->set_values() ) {
			return;
		}

		if ( false === $this->do_payment() ) {
			return;
		}

		if ( false === $this->do_smart_payables() ) {
			return;
		}

		$this->order->completed();
		wp_safe_redirect(
			add_query_arg(
				[
					'completed' => '1',
					'order'     => $this->order->get_id(),
				]
			)
		);
		exit;
	}

	/**
	 * New order notification email.
	 *
	 * @param Order $order Order completed.
	 */
	public function new_order_notification( $order ) {

	}

	/**
	 * Clean after order completed.
	 *
	 * @param Order $order Order completed.
	 */
	public function clean_order_data( $order ) {
		$full   = get_template_directory() . '/tmp/order_' . $order->get_id() . '_full.csv';
		$upload = get_template_directory() . '/tmp/order_' . $order->get_id() . '.csv';

		// Delete CSV files.
		@unlink( $full );
		@unlink( $upload );
	}

	/**
	 * Get values from $_POST.
	 *
	 * @return bool
	 */
	private function set_values() {
		$valid   = true;
		$user_id = get_current_user_id();
		$fields  = [ 'payment_address', 'payment_address_2', 'payment_country', 'payment_state', 'payment_city', 'payment_zipcode', 'payment_cc_number', 'payment_cc_expiration', 'payment_cc_cvv' ];

		foreach ( $fields as $field ) {
			if ( empty( $_POST[ $field ] ) && 'payment_address_2' !== $field ) {
				/* translators: field name */
				$this->errors->add( $field . '_error', sprintf( __( '<strong>WARNING</strong>: %s cannot be empty.', 'munipay' ), $field ) );
				$valid = false;
				continue;
			}

			$this->data[ $field ] = sanitize_text_field( $_POST[ $field ] );

			if ( ! in_array( $field, [ 'payment_cc_number', 'payment_cc_expiration', 'payment_cc_cvv' ], true ) ) {
				update_user_meta( $user_id, $field, $this->data[ $field ] );
			}
		}

		return $valid;
	}

	/**
	 * Process the payment.
	 *
	 * @return bool
	 */
	private function do_payment() {
		$authorize = new Authorize( $this );
		$authorize->set_credit_card(
			[
				'ccv'    => $this->data['payment_cc_cvv'],
				'number' => $this->data['payment_cc_number'],
				'expire' => $this->data['payment_cc_expiration'],
			]
		);

		return $authorize->process();
	}

	/**
	 * Process the smart payable upload.
	 *
	 * @return bool
	 */
	private function do_smart_payables() {
		$this->errors->errors = [];

		$smart = new Smart_Payables( $this );
		return $smart->process();
	}

	/**
	 * Get template part
	 *
	 * @param string $template Template name.
	 */
	private function get_template( $template ) {
		include locate_template( 'templates/checkout/' . $template . '.php' );
	}
}
