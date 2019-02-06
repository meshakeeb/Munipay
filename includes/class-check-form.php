<?php
/**
 * Checks entry form.
 *
 * @since      1.0.0
 * @package    Munipay
 * @subpackage Munipay\Core
 * @author     BoltMedia <info@boltmedia.ca>
 */

namespace Munipay;

use stdClass;
use WP_Error;
use Munipay\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Check_Form class.
 */
class Check_Form {

	use Hooker;

	/**
	 * Hold errors.
	 *
	 * @var WP_Error
	 */
	private $errors = null;

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
		$this->errors = new WP_Error;
		add_shortcode( 'check_form', [ $this, 'form' ] );
	}

	/**
	 * Render form.
	 */
	public function form() {
		ob_start();

		$this->order = Order::get_current_order();
		$this->get_template( 'header' );

		Form::display_errors( $this->errors );
		?>

		<div class="container mt-5 mb-5">

			<?php $this->get_template( 'requester' ); ?>

			<?php $this->get_template( 'bundle-info' ); ?>

			<h3 class="mb-3"><?php esc_html_e( 'Requests', 'munipay' ); ?></h3>

			<div id="orders" class="order-accordion">
				<?php
				foreach ( $this->order->checks as $check ) {
					$this->current_check = $check;
					$this->get_template( 'check' );
				}
				?>
			</div>

			<div class="text-center my-5">

				<div class="d-none">
					<?php $this->current_check = new Check( 0 ); ?>
					<?php $this->get_template( 'check' ); ?>
				</div>

				<button type="button" class="button btn btn-primary btn-lg order-request-add"><span><?php esc_html_e( 'Add New Request', 'munipay' ); ?></span></button>
				<a href="<?php echo home_url( '/review-checks' ); ?>" class="btn btn-warning btn-lg"><?php esc_html_e( 'Review Request', 'munipay' ); ?></a>

			</div>

		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get check with bundle info.
	 *
	 * @return Check
	 */
	public function get_check_with_bundle_info() {
		foreach ( $this->order->checks as $check ) {
			if ( $check->get_meta( 'bundle_mailto' ) ) {
				return $check;
			}
		}

		return null;
	}

	/**
	 * Get template part
	 *
	 * @param string $template Template name.
	 */
	private function get_template( $template ) {
		include locate_template( 'templates/order/' . $template . '.php' );
	}
}
