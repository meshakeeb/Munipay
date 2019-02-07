<?php
/**
 * User Registration
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
 * Registration class.
 */
class Registration {

	use Hooker;

	/**
	 * The class constructor.
	 */
	public function __construct() {

		/**
		 * Add -----------------------------------------------------
		 */

		// Add fields on registration page.
		$this->action( 'register_form', 'add' );

		// Add fields on backend new user page.
		$this->filter( 'user_new_form', 'add_on_new_user' );

		// Add fields on author profile page.
		$this->filter( 'user_contactmethods', 'add_on_profile' );

		/**
		 * Validation ----------------------------------------------
		 */

		// Add validation for registration page.
		$this->filter( 'registration_errors', 'validate' );

		// Add validation for backend new user page.
		$this->action( 'user_profile_update_errors', 'validate_new_user', 10, 3 );

		/**
		 * Save Fields ---------------------------------------------
		 */

		// Finally, save our extra registration user meta.
		$this->action( 'user_register', 'save' );
		$this->action( 'edit_user_created_user', 'save' );

		$this->filter( 'login_redirect', 'login_redirect' );
	}

	/**
	 * Login redirect.
	 *
	 * @return string
	 */
	public function login_redirect() {
		return home_url();
	}

	/**
	 * Add new fields.
	 */
	public function add() {
		?>
		<div class="row">
		<?php foreach ( self::get_fields() as $id => $field ) { ?>
			<p class="col-<?php echo $field['grid']; ?>">
				<label for="<?php echo $id; ?>"><?php echo $field['title']; ?><br/>
					<input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo isset( $_POST[ $id ] ) ? esc_attr( $_POST[ $id ] ) : ''; ?>" class="input">
				</label>
			</p>
		<?php } ?>
		</div>
		<?php
	}

	/**
	 * Add new fields on profile page for editing.
	 *
	 * @param array $fields The profile fields.
	 *
	 * @return array The profile fields with additional contact methods.
	 */
	public function add_on_profile( $fields ) {
		$fields = self::get_fields();
		unset( $fields['first_name'], $fields['last_name'] );

		foreach ( $fields as $id => $field ) {
			$fields[ $id ] = $field['title'];
		}

		return $fields;
	}

	/**
	 * Add new fields on backend registration page.
	 *
	 * @param string $operation What operation is being performed.
	 */
	public function add_on_new_user( $operation ) {
		if ( 'add-new-user' !== $operation ) {
			// $operation may also be 'add-existing-user'
			return;
		}

		$fields = self::get_fields();
		unset( $fields['first_name'], $fields['last_name'] );
		?>
		<table class="form-table">
		<?php foreach ( $fields as $id => $field ) { ?>
			<tr>
				<th>
					<label for="<?php echo $id; ?>"><?php echo $field['title']; ?></label>
				</th>
				<td>
					<input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo isset( $_POST[ $id ] ) ? esc_attr( $_POST[ $id ] ) : ''; ?>" class="regular-text">
				</td>
			</tr>
		<?php } ?>
		</table>
		<?php
	}

	/**
	 * Validate fields data.
	 *
	 * @param WP_Error $errors An object containing any errors encountered during registration.
	 *
	 * @return WP_Error
	 */
	public function validate( $errors ) {
		foreach ( self::get_fields() as $id => $field ) {
			if ( empty( $_POST[ $id ] ) ) {
				/* translators: field name */
				$errors->add( $id . '_error', sprintf( __( '<strong>ERROR</strong>: Please enter %s.', 'munipay' ), $field['title'] ) );
			}
		}

		return $errors;
	}

	/**
	 * Validate fields data.
	 *
	 * @param WP_Error $errors An object containing any errors encountered during registration.
	 * @param boolean  $update Whether this is a user update.
	 *
	 * @return WP_Error
	 */
	public function validate_new_user( $errors, $update ) {
		foreach ( self::get_fields() as $id => $field ) {
			if ( empty( $_POST[ $id ] ) ) {
				/* translators: field name */
				$errors->add( $id . '_error', sprintf( __( '<strong>ERROR</strong>: Please enter %s.', 'munipay' ), $field['title'] ) );
			}
		}

		return $errors;
	}

	/**
	 * After a new user got registered save extra data.
	 *
	 * @param int $user_id User ID.
	 */
	public function save( $user_id ) {
		foreach ( self::get_fields() as $id => $field ) {
			if ( ! empty( $_POST[ $id ] ) ) {
				update_user_meta( $user_id, $id, sanitize_text_field( $_POST[ $id ] ) );
			}
		}
	}

	/**
	 * Get custom fields.
	 *
	 * @return array
	 */
	public static function get_fields() {
		return [
			'first_name'  => [
				'title' => esc_html__( 'First Name', 'munipay' ),
				'grid'  => 6,
			],

			'last_name'   => [
				'title' => esc_html__( 'Last Name', 'munipay' ),
				'grid'  => 6,
			],

			'phone'       => [
				'title' => esc_html__( 'Phone', 'munipay' ),
				'grid'  => 6,
			],

			'signum'      => [
				'title' => esc_html__( 'Signum', 'munipay' ),
				'grid'  => 6,
			],

			'cost_center' => [
				'title' => esc_html__( 'Cost Center', 'munipay' ),
				'grid'  => 12,
			],
		];
	}
}
