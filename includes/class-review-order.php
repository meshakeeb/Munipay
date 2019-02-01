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

			<h3 class="mb-5">Review Requests</h3>

			<div class="row">

				<div class="col-4">
					<?php $this->get_template( 'requester' ); ?>
				</div>

				<div class="col-6 offset-2">

					<ul class="list-group">
						<?php
						foreach ( $this->order->checks as $check ) {
							$this->current_check = $check;
							$this->get_template( 'check' );
						}
						?>
					</ul>

					<div class="d-flex justify-content-between p-4">
						<div>
							<h4 class="my-0">Total Charge</h4>
						</div>
						<h3 class="my-0" id="order-total-amount"><?php echo $this->order->get_total(); ?></h3>
					</div>

				</div>

			</div>

			<div class="text-right my-5">
				<a href="<?php echo home_url( '/checkout' ); ?>" class="btn btn-warning btn-lg">Checkout</a>
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
