<?php
/**
 * Review request.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

use Munipay\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Review_Order class.
 */
class Review_Order {

	use Hooker;

	/**
	 * Hold order for screen.
	 *
	 * @var Order
	 */
	protected $order = false;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		add_shortcode( 'review_order', [ $this, 'review' ] );
	}

	/**
	 * Render review.
	 */
	public function review() {
		ob_start();

		$this->order = Order::get_current_order();
		$this->get_template( 'header' );
		?>

		<div class="container mt-5 mb-5">

			<h3 class="mb-5"><?php esc_html_e( 'Review Requests', 'munipay' ); ?></h3>

			<div class="row">

				<div class="col-4">
					<?php $this->get_template( 'requester' ); ?>
				</div>

				<div class="col-6 offset-2">

					<?php if ( $this->order->has_checks() ) : ?>
					<ul class="list-group">
						<?php
						foreach ( $this->order->checks as $check ) {
							$this->current_check = $check;
							$this->get_template( 'check' );
						}
						?>

						<?php if ( $this->order->has_bundle() ) : ?>
						<li class="list-group-item d-flex justify-content-between bg-light">
							<div class="text-success">
								<h6 class="my-0"><?php esc_html_e( 'Bundle Delivery Charges', 'munipay' ); ?></h6>
							</div>
							<span class="text-success">$36.00</span>
						</li>
						<?php endif; ?>

					</ul>

					<div class="d-flex justify-content-between p-4">
						<div>
							<h4 class="my-0"><?php esc_html_e( 'Total Charge', 'munipay' ); ?></h4>
						</div>
						<h3 class="my-0" id="order-total-amount"><?php echo $this->order->get_total(); ?></h3>
					</div>
					<?php else : ?>
						<?php esc_html_e( 'No checks added to the order yet.', 'munipay' ); ?><br>
					<div class="mt-2">
						<a href="<?php echo home_url( '/enter-check' ); ?>" class="btn btn-warning btn-lg"><?php esc_html_e( 'Enter Checks', 'munipay' ); ?></a>
					</div>
					<?php endif; ?>

				</div>

			</div>

			<div class="text-right my-5">
				<?php if ( $this->order->has_checks() ) : ?>
				<a href="<?php echo home_url( '/checkout' ); ?>" class="btn btn-warning btn-lg"><?php esc_html_e( 'Checkout', 'munipay' ); ?></a>
				<?php endif; ?>
			</div>

		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get template part
	 *
	 * @param string $template Template name.
	 */
	private function get_template( $template ) {
		include locate_template( 'templates/review/' . $template . '.php' );
	}
}
