<?php
/**
 * User Check_Form
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

		Form::display_errors( $this->errors );

		$this->set_current_order();
		$this->get_template( 'header' );
		?>

		<div class="container mt-5 mb-5">

			<?php $this->get_template( 'requester' ); ?>

			<h3 class="mb-3">Requests</h3>

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

				<button type="button" class="btn btn-primary btn-lg order-request-add">Add New Request</button>

			</div>

		</div>
		<?php
		return ob_get_clean();
	}

	private function set_current_order() {
		$current_user = wp_get_current_user();
		$this->order  = new Order( $current_user->current_order );
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
