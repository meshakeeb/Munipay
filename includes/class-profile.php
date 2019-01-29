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
		ob_start();

		Form::display_errors( $this->errors );

		$current_user = wp_get_current_user();
		?>
		<div class="jumbotron py-4 px-2">

			<h3 class="pl-3 pb-2">Personal Information</h3>

			<form action="" method="post" class="container">

				<div class="row">

					<?php

					Form::text(
						[
							'id'    => 'first_name',
							'title' => 'First Name',
							'value' => $current_user->get( 'first_name' ),
						]
					);

					Form::text(
						[
							'id'    => 'last_name',
							'title' => 'Last Name',
							'value' => $current_user->get( 'last_name' ),
						]
					);

					?>

				</div>

				<div class="row pt-4">

					<?php

					Form::text(
						[
							'id'    => 'user_email',
							'title' => 'Email',
							'value' => $current_user->get( 'user_email' ),
						]
					);

					Form::text(
						[
							'id'    => 'phone',
							'title' => 'Phone',
							'value' => $current_user->get( 'phone' ),
						]
					);

					?>

				</div>

				<div class="row pt-4">

					<?php

					Form::text(
						[
							'id'    => 'signum',
							'title' => 'Signum',
							'value' => $current_user->get( 'signum' ),
						]
					);

					Form::text(
						[
							'id'    => 'cost_center',
							'title' => 'Cost Center',
							'value' => $current_user->get( 'cost_center' ),
						]
					);

					?>

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

					<?php

					Form::text(
						[
							'id'    => 'user_login',
							'title' => 'Username',
							'value' => $current_user->get( 'user_login' ),
						]
					);

					Form::text(
						[
							'id'    => 'password_current',
							'title' => 'Current Password',
						]
					);

					?>

				</div>

				<div class="row pt-4">

					<?php

					Form::text(
						[
							'id'    => 'password_1',
							'title' => 'New Password',
						]
					);

					Form::text(
						[
							'id'    => 'password_2',
							'title' => 'Re-Enter New Password',
						]
					);

					?>

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
