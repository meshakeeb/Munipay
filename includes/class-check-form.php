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
	 * The class constructor.
	 */
	public function __construct() {
		$this->errors = new WP_Error;
		$this->action( 'template_redirect', 'save' );

		add_shortcode( 'check_form', [ $this, 'form' ] );
	}

	/**
	 * Render form.
	 */
	public function form() {
		ob_start();

		Form::display_errors( $this->errors );

		$this->get_template( 'header' );
		?>

		<form class="container mt-5 mb-5">

			<?php $this->get_template( 'requester' ); ?>

			<h3 class="mb-3">Requests</h3>

			<div id="orders" class="order-accordion">
				<?php $this->get_template( 'check' ); ?>
			</div>

		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Save forms.
	 */
	public function save() {
		if ( isset( $_POST['munipay_save_profile'] ) && check_admin_referer( 'munipay_save_profile', 'security' ) ) {
			$this->save_profile();
		}
	}

	/**
	 * Save profile.
	 */
	public function save_profile() {
		$user = wp_get_current_user();
		foreach ( Registration::get_fields() as $id => $field ) {
			if ( empty( $_POST[ $id ] ) ) {
				/* translators: field name */
				$this->errors->add( $id . '_error', sprintf( __( '<strong>WARNING</strong>: %s cannot be empty. Revert to old value.', 'munipay' ), $field['title'] ) );
				continue;
			}

			update_user_meta( $user->ID, $id, sanitize_text_field( $_POST[ $id ] ) );
		}

		// Email.
		if ( empty( $_POST['user_email'] ) ) {
			/* translators: field name */
			$this->errors->add( 'user_email_error', __( '<strong>WARNING</strong>: Email cannot be empty. Revert to old value.', 'munipay' ) );
			return;
		}

		if ( ! is_email( $_POST['user_email'] ) ) {
			/* translators: field name */
			$this->errors->add( 'invalid_email', __( '<strong>WARNING</strong>: The email address isn&#8217;t correct.', 'munipay' ) );
			return;
		}

		$user->user_email = $_POST['user_email'];
		wp_update_user( $user );
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
