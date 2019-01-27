<?php
/**
 * User Profile
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
 * Profile class.
 */
class Profile {

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

		add_shortcode( 'profile_account', [ $this, 'profile' ] );
	}

	/**
	 * Render profile page.
	 */
	public function profile() {
		$this->user = wp_get_current_user();
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
		?>
		<div class="jumbotron py-4 px-2">

			<h3 class="pl-3 pb-2">Personal Information</h3>

			<form action="" method="post" class="container">

				<div class="row">

					<?php $this->render_field( 'first_name', 'First Name' ); ?>
					<?php $this->render_field( 'last_name', 'Last Name' ); ?>

				</div>

				<div class="row pt-4">

					<?php $this->render_field( 'user_email', 'Email' ); ?>
					<?php $this->render_field( 'phone', 'Phone' ); ?>

				</div>

				<div class="row pt-4">

					<?php $this->render_field( 'signum', 'Signum' ); ?>
					<?php $this->render_field( 'cost_center', 'Cost Center' ); ?>

				</div>

				<div class="pt-4 text-right">
					<?php wp_nonce_field( 'munipay_save_profile', 'security', false ); ?>
					<button type="submit" class="btn btn-primary mb-2" name="munipay_save_profile">Save Changes</button>
				</div>

			</form>

		</div>

		<div class="jumbotron py-4 px-2">

			<h3 class="pl-3 pb-2">Your Password</h3>

			<form action="" method="post" class="container">

				<div class="row">

					<?php $this->render_field( 'user_login', 'Username' ); ?>
					<?php $this->render_field( 'password_current', 'Current Password' ); ?>

				</div>

				<div class="row pt-4">

					<?php $this->render_field( 'password_1', 'New Password' ); ?>
					<?php $this->render_field( 'password_2', 'Re-Enter New Password' ); ?>

				</div>

				<div class="pt-4 text-right">
					<?php wp_nonce_field( 'munipay_save_profile', 'security', false ); ?>
					<button type="submit" class="btn btn-primary mb-2" name="munipay_change_password">Change Password</button>
				</div>

			</form>

		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render field.
	 *
	 * @param string $id    Field id.
	 * @param string $title Field title.
	 */
	private function render_field( $id, $title ) {
		?>
		<div class="col">
			<input type="text" class="form-control" name="<?php echo $id; ?>" placeholder="<?php echo $title; ?>" value="<?php echo $this->user->get( $id ); ?>">
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

		if ( isset( $_POST['munipay_change_password'] ) && check_admin_referer( 'munipay_save_profile', 'security' ) ) {
			$this->change_password();
		}
	}

	/**
	 * Change Password.
	 */
	public function change_password() {
		$current_user = wp_get_current_user();

		// Passowrd fields.
		$pass_cur  = ! empty( $_POST['password_current'] ) ? $_POST['password_current'] : '';
		$pass1     = ! empty( $_POST['password_1'] ) ? $_POST['password_1'] : '';
		$pass2     = ! empty( $_POST['password_2'] ) ? $_POST['password_2'] : '';
		$save_pass = true;

		if ( ! empty( $pass_cur ) && empty( $pass1 ) && empty( $pass2 ) ) {
			$this->errors->add( 'error', __( 'Please fill out all password fields.', 'munipay' ) );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && empty( $pass_cur ) ) {
			$this->errors->add( 'error', __( 'Please enter your current password.', 'munipay' ) );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && empty( $pass2 ) ) {
			$this->errors->add( 'error', __( 'Please re-enter your password.', 'munipay' ) );
			$save_pass = false;
		} elseif ( ( ! empty( $pass1 ) || ! empty( $pass2 ) ) && $pass1 !== $pass2 ) {
			$this->errors->add( 'error', __( 'New passwords do not match.', 'munipay' ) );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && ! wp_check_password( $pass_cur, $current_user->user_pass, $current_user->ID ) ) {
			$this->errors->add( 'error', __( 'Your current password is incorrect.', 'munipay' ) );
			$save_pass = false;
		}

		if ( ! $save_pass ) {
			return;
		}

		wp_set_password( $pass1, $current_user->ID );
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
