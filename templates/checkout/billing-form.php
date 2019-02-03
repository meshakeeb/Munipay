<?php
/**
 * Checkout billing form.
 *
 * @package Aheto
 */

use Munipay\Form;

$order        = $this->order;
$current_user = wp_get_current_user();
?>
<h4 class="mb-3"><?php esc_html_e( 'Billing address', 'munipay' ); ?></h4>

<div class="mb-3">

	<label for="requester_name"><?php esc_html_e( 'Requester Name', 'munipay' ); ?></label>

	<input type="text" class="form-control" id="requester_name" name="requester_name" value="<?php echo $order->get_requester_name(); ?>" required>

	<div class="invalid-feedback">
		<?php esc_html_e( 'Valid requester name is required.', 'munipay' ); ?>
	</div>

</div>

<div class="row">

	<div class="col-md-6 mb-3">

		<label for="requester_email"><?php esc_html_e( 'Email', 'munipay' ); ?></label>

		<div class="input-group">

			<div class="input-group-prepend">
				<span class="input-group-text">@</span>
			</div>

			<input type="text" class="form-control" id="requester_email" name="requester_email" value="<?php echo $order->get_requester_email(); ?>" required>

			<div class="invalid-feedback">
				<?php esc_html_e( 'Valid email is required.', 'munipay' ); ?>
			</div>

		</div>

	</div>

	<div class="col-md-6 mb-3">

		<label for="requester_phone"><?php esc_html_e( 'Phone', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="requester_phone" name="requester_phone" value="<?php echo $order->get_requester_phone(); ?>" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Valid phone is required.', 'munipay' ); ?>
		</div>

	</div>

</div>

<div class="row">

	<div class="col-md-6 mb-3">

		<label for="requester_signum"><?php esc_html_e( 'Signum', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="requester_signum" name="requester_signum" value="<?php echo $order->get_requester_signum(); ?>" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Valid signum is required.', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-6 mb-3">

		<label for="requester_cost_center"><?php esc_html_e( 'Cost Center', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="requester_cost_center" name="requester_cost_center" value="<?php echo $order->get_requester_cost_center(); ?>" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Valid cost center is required.', 'munipay' ); ?>
		</div>

	</div>

</div>

<div class="mb-3">

	<label for="payment_address"><?php esc_html_e( 'Address', 'munipay' ); ?></label>

	<?php
	Form::text(
		[
			'id'          => 'payment_address',
			'required'    => '',
			'placeholder' => '1234 Main St',
			'value'       => $current_user->get( 'payment_address' ),
		],
		true
	);
	?>

	<div class="invalid-feedback">
		<?php esc_html_e( 'Please enter your shipping address.', 'munipay' ); ?>
	</div>

</div>

<div class="mb-3">

	<label for="payment_address_2"><?php esc_html_e( 'Address 2', 'munipay' ); ?> <span class="text-muted"><?php esc_html_e( '(Optional)', 'munipay' ); ?></span></label>

	<?php
	Form::text(
		[
			'id'          => 'payment_address_2',
			'placeholder' => 'Apartment or suite',
			'value'       => $current_user->get( 'payment_address_2' ),
		],
		true
	);
	?>

</div>

<div class="row">

	<input type="hidden" name="payment_country" value="US">

	<div class="col-md-5 mb-3">

		<label for="payment_state"><?php esc_html_e( 'State', 'munipay' ); ?></label>

		<?php
		Form::select(
			[
				'id'       => 'payment_state',
				'required' => '',
				'class'    => 'custom-select d-block w-100',
				'value'    => $current_user->get( 'payment_state' ),
				'options'  => [
					''   => esc_html__( 'Choose...', 'munipay' ),
					'AL' => esc_html__( 'Alabama', 'munipay' ),
					'AK' => esc_html__( 'Alaska', 'munipay' ),
					'AZ' => esc_html__( 'Arizona', 'munipay' ),
					'AR' => esc_html__( 'Arkansas', 'munipay' ),
					'CA' => esc_html__( 'California', 'munipay' ),
					'CO' => esc_html__( 'Colorado', 'munipay' ),
					'CT' => esc_html__( 'Connecticut', 'munipay' ),
					'DE' => esc_html__( 'Delaware', 'munipay' ),
					'DC' => esc_html__( 'District Of Columbia', 'munipay' ),
					'FL' => esc_html__( 'Florida', 'munipay' ),
					'GA' => esc_html__( 'Georgia', 'munipay' ),
					'HI' => esc_html__( 'Hawaii', 'munipay' ),
					'ID' => esc_html__( 'Idaho', 'munipay' ),
					'IL' => esc_html__( 'Illinois', 'munipay' ),
					'IN' => esc_html__( 'Indiana', 'munipay' ),
					'IA' => esc_html__( 'Iowa', 'munipay' ),
					'KS' => esc_html__( 'Kansas', 'munipay' ),
					'KY' => esc_html__( 'Kentucky', 'munipay' ),
					'LA' => esc_html__( 'Louisiana', 'munipay' ),
					'ME' => esc_html__( 'Maine', 'munipay' ),
					'MD' => esc_html__( 'Maryland', 'munipay' ),
					'MA' => esc_html__( 'Massachusetts', 'munipay' ),
					'MI' => esc_html__( 'Michigan', 'munipay' ),
					'MN' => esc_html__( 'Minnesota', 'munipay' ),
					'MS' => esc_html__( 'Mississippi', 'munipay' ),
					'MO' => esc_html__( 'Missouri', 'munipay' ),
					'MT' => esc_html__( 'Montana', 'munipay' ),
					'NE' => esc_html__( 'Nebraska', 'munipay' ),
					'NV' => esc_html__( 'Nevada', 'munipay' ),
					'NH' => esc_html__( 'New Hampshire', 'munipay' ),
					'NJ' => esc_html__( 'New Jersey', 'munipay' ),
					'NM' => esc_html__( 'New Mexico', 'munipay' ),
					'NY' => esc_html__( 'New York', 'munipay' ),
					'NC' => esc_html__( 'North Carolina', 'munipay' ),
					'ND' => esc_html__( 'North Dakota', 'munipay' ),
					'OH' => esc_html__( 'Ohio', 'munipay' ),
					'OK' => esc_html__( 'Oklahoma', 'munipay' ),
					'OR' => esc_html__( 'Oregon', 'munipay' ),
					'PA' => esc_html__( 'Pennsylvania', 'munipay' ),
					'RI' => esc_html__( 'Rhode Island', 'munipay' ),
					'SC' => esc_html__( 'South Carolina', 'munipay' ),
					'SD' => esc_html__( 'South Dakota', 'munipay' ),
					'TN' => esc_html__( 'Tennessee', 'munipay' ),
					'TX' => esc_html__( 'Texas', 'munipay' ),
					'UT' => esc_html__( 'Utah', 'munipay' ),
					'VT' => esc_html__( 'Vermont', 'munipay' ),
					'VA' => esc_html__( 'Virginia', 'munipay' ),
					'WA' => esc_html__( 'Washington', 'munipay' ),
					'WV' => esc_html__( 'West Virginia', 'munipay' ),
					'WI' => esc_html__( 'Wisconsin', 'munipay' ),
					'WY' => esc_html__( 'Wyoming', 'munipay' ),
					'AA' => esc_html__( 'Armed Forces (AA)', 'munipay' ),
					'AE' => esc_html__( 'Armed Forces (AE)', 'munipay' ),
					'AP' => esc_html__( 'Armed Forces (AP)', 'munipay' ),
				],
			],
			true
		);
		?>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Please select a valid state.', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-4 mb-3">

		<label for="payment_city"><?php esc_html_e( 'City', 'munipay' ); ?></label>

		<?php
		Form::text(
			[
				'id'          => 'payment_city',
				'required'    => '',
				'placeholder' => 'City',
				'value'       => $current_user->get( 'payment_city' ),
			],
			true
		);
		?>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Please provide a city.', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-3 mb-3">

		<label for="payment_zipcode"><?php esc_html_e( 'Zip Code', 'munipay' ); ?></label>

		<?php
		Form::text(
			[
				'id'          => 'payment_zipcode',
				'required'    => '',
				'placeholder' => 'Zipcode',
				'value'       => $current_user->get( 'payment_zipcode' ),
			],
			true
		);
		?>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Zip code required.', 'munipay' ); ?>
		</div>

	</div>

</div>

<hr class="mb-4">

<h4 class="mb-3"><?php esc_html_e( 'Payment', 'munipay' ); ?></h4>

<div class="row">

	<div class="col-md-6 mb-3">

		<label for="payment_cc_name"><?php esc_html_e( 'Name on card', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="payment_cc_name" name="payment_cc_name" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Name on card is required', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-6 mb-3">

		<label for="payment_cc_number"><?php esc_html_e( 'Credit card number', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="payment_cc_number" name="payment_cc_number" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Credit card number is required', 'munipay' ); ?>
		</div>

	</div>

</div>

<div class="row">

	<div class="col-md-6 mb-3">

		<label for="payment_cc_expiration"><?php esc_html_e( 'Expiration', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="payment_cc_expiration" name="payment_cc_expiration" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Expiration date required', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-6 mb-3">

		<label for="payment_cc_cvv"><?php esc_html_e( 'CVV', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="payment_cc_cvv" name="payment_cc_cvv" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Security code required', 'munipay' ); ?>
		</div>

	</div>

</div>
