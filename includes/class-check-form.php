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
	 * Current user.
	 *
	 * @var WP_User
	 */
	private $user = null;

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

		if ( ! empty( $this->errors->errors ) ) {
			?>
			<div class="alert alert-danger" role="alert">
				<ul class="list-unstyled mb-0">
					<?php echo '<li>' . join( '</li><li>', $this->errors->get_error_messages() ) . '</li>'; ?>
				</ul>
			</div>
			<?php
		}

		$this->user = wp_get_current_user();
		?>
		<div class="jumbotron py-4 px-2 bg-warning">

			<div class="container">

				<p class="h4 font-weight-normal">
					Use this page to enter check requests throughout the business day. After you’ve entered a request, click the blue SAVE CHECK REQUEST button to save it and open another form.
				</p>

				<p class="h5 font-weight-normal pt-3">
					(You can make edits to requests at any time. Click the plus sign to open a request, make your changes and then click UPDATE to save.)
				</p>

				<p class="h5 font-weight-normal pt-3">
					When you’ve entered all the requests for the day and are ready to order, click the orange ADD CHECK REQUESTS TO CART button to go to the Review page.
				</p>

				<div class="alert alert-secondary mt-4" role="alert">

					<p class="h5 font-weight-normal">
						Remember to complete the entire check ordering process by 7 p.m. CT. If you have questions or need customer support, please email mana.sourcing@ericsson.com or call 469-266-5389.
					</p>

				</div>

			</div>

		</div>

		<form class="container mt-5">

			<div class="jumbotron p-4">

				<h5 class="pb-3">Check Requester</h5>

				<div class="row">

					<div class="col">
						<input type="text" class="form-control" disabled="disabled" name="request_date" placeholder="Date" value="<?php echo date( get_option( 'date_format' ) ); ?>">
						<small class="form-text pl-2 text-muted">Date</small>
					</div>

					<?php $this->render_text( 'requester_email', 'Email', $this->user->user_email ); ?>

				</div>

				<div class="row pt-4">

					<?php $this->render_text( 'requester_name', 'Requester Name', $this->user->user_login ); ?>
					<?php $this->render_text( 'requester_phone', 'Phone', $this->user->phone ); ?>

				</div>

				<div class="row pt-4">

					<?php $this->render_text( 'requester_signum', 'Signum', $this->user->signum ); ?>
					<?php $this->render_text( 'requester_cost_center', 'Cost center', $this->user->get( 'cost_center' ) ); ?>

				</div>

			</div>

		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render field.
	 *
	 * @param string $id    Field id.
	 * @param string $title Field title.
	 * @param string $value Field value.
	 */
	private function render_text( $id, $title, $value = '' ) {
		?>
		<div class="col">
			<input type="text" class="form-control" name="<?php echo $id; ?>" placeholder="<?php echo $title; ?>" value="<?php echo $value; ?>">
			<small class="form-text pl-2 text-muted"><?php echo $title; ?></small>
		</div>
		<?php
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
}
