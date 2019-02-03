<?php
/**
 * Checkout billing form.
 *
 * @package Aheto
 */

$order = $this->order;
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

	<input type="text" class="form-control" id="payment_address" name="payment_address" placeholder="1234 Main St" required>

	<div class="invalid-feedback">
		<?php esc_html_e( 'Please enter your shipping address.', 'munipay' ); ?>
	</div>

</div>

<div class="mb-3">

	<label for="payment_address_2"><?php esc_html_e( 'Address 2', 'munipay' ); ?> <span class="text-muted"><?php esc_html_e( '(Optional)', 'munipay' ); ?></span></label>

	<input type="text" class="form-control" id="payment_address_2" name="payment_address_2" placeholder="Apartment or suite">

</div>

<div class="row">

	<input type="hidden" name="payment_country" value="US">

	<div class="col-md-5 mb-3">

		<label for="payment_state"><?php esc_html_e( 'State', 'munipay' ); ?></label>

		<select class="custom-select d-block w-100" id="payment_state" name="payment_state" required>
			<option value=""><?php esc_html_e( 'Choose...', 'munipay' ); ?></option>
			<option value="AL"><?php esc_html_e( 'Alabama', 'munipay' ); ?></option>
			<option value="AK"><?php esc_html_e( 'Alaska', 'munipay' ); ?></option>
			<option value="AZ"><?php esc_html_e( 'Arizona', 'munipay' ); ?></option>
			<option value="AR"><?php esc_html_e( 'Arkansas', 'munipay' ); ?></option>
			<option value="CA"><?php esc_html_e( 'California', 'munipay' ); ?></option>
			<option value="CO"><?php esc_html_e( 'Colorado', 'munipay' ); ?></option>
			<option value="CT"><?php esc_html_e( 'Connecticut', 'munipay' ); ?></option>
			<option value="DE"><?php esc_html_e( 'Delaware', 'munipay' ); ?></option>
			<option value="DC"><?php esc_html_e( 'District Of Columbia', 'munipay' ); ?></option>
			<option value="FL"><?php esc_html_e( 'Florida', 'munipay' ); ?></option>
			<option value="GA"><?php esc_html_e( 'Georgia', 'munipay' ); ?></option>
			<option value="HI"><?php esc_html_e( 'Hawaii', 'munipay' ); ?></option>
			<option value="ID"><?php esc_html_e( 'Idaho', 'munipay' ); ?></option>
			<option value="IL"><?php esc_html_e( 'Illinois', 'munipay' ); ?></option>
			<option value="IN"><?php esc_html_e( 'Indiana', 'munipay' ); ?></option>
			<option value="IA"><?php esc_html_e( 'Iowa', 'munipay' ); ?></option>
			<option value="KS"><?php esc_html_e( 'Kansas', 'munipay' ); ?></option>
			<option value="KY"><?php esc_html_e( 'Kentucky', 'munipay' ); ?></option>
			<option value="LA"><?php esc_html_e( 'Louisiana', 'munipay' ); ?></option>
			<option value="ME"><?php esc_html_e( 'Maine', 'munipay' ); ?></option>
			<option value="MD"><?php esc_html_e( 'Maryland', 'munipay' ); ?></option>
			<option value="MA"><?php esc_html_e( 'Massachusetts', 'munipay' ); ?></option>
			<option value="MI"><?php esc_html_e( 'Michigan', 'munipay' ); ?></option>
			<option value="MN"><?php esc_html_e( 'Minnesota', 'munipay' ); ?></option>
			<option value="MS"><?php esc_html_e( 'Mississippi', 'munipay' ); ?></option>
			<option value="MO"><?php esc_html_e( 'Missouri', 'munipay' ); ?></option>
			<option value="MT"><?php esc_html_e( 'Montana', 'munipay' ); ?></option>
			<option value="NE"><?php esc_html_e( 'Nebraska', 'munipay' ); ?></option>
			<option value="NV"><?php esc_html_e( 'Nevada', 'munipay' ); ?></option>
			<option value="NH"><?php esc_html_e( 'New Hampshire', 'munipay' ); ?></option>
			<option value="NJ"><?php esc_html_e( 'New Jersey', 'munipay' ); ?></option>
			<option value="NM"><?php esc_html_e( 'New Mexico', 'munipay' ); ?></option>
			<option value="NY"><?php esc_html_e( 'New York', 'munipay' ); ?></option>
			<option value="NC"><?php esc_html_e( 'North Carolina', 'munipay' ); ?></option>
			<option value="ND"><?php esc_html_e( 'North Dakota', 'munipay' ); ?></option>
			<option value="OH"><?php esc_html_e( 'Ohio', 'munipay' ); ?></option>
			<option value="OK"><?php esc_html_e( 'Oklahoma', 'munipay' ); ?></option>
			<option value="OR"><?php esc_html_e( 'Oregon', 'munipay' ); ?></option>
			<option value="PA"><?php esc_html_e( 'Pennsylvania', 'munipay' ); ?></option>
			<option value="RI"><?php esc_html_e( 'Rhode Island', 'munipay' ); ?></option>
			<option value="SC"><?php esc_html_e( 'South Carolina', 'munipay' ); ?></option>
			<option value="SD"><?php esc_html_e( 'South Dakota', 'munipay' ); ?></option>
			<option value="TN"><?php esc_html_e( 'Tennessee', 'munipay' ); ?></option>
			<option value="TX"><?php esc_html_e( 'Texas', 'munipay' ); ?></option>
			<option value="UT"><?php esc_html_e( 'Utah', 'munipay' ); ?></option>
			<option value="VT"><?php esc_html_e( 'Vermont', 'munipay' ); ?></option>
			<option value="VA"><?php esc_html_e( 'Virginia', 'munipay' ); ?></option>
			<option value="WA"><?php esc_html_e( 'Washington', 'munipay' ); ?></option>
			<option value="WV"><?php esc_html_e( 'West Virginia', 'munipay' ); ?></option>
			<option value="WI"><?php esc_html_e( 'Wisconsin', 'munipay' ); ?></option>
			<option value="WY"><?php esc_html_e( 'Wyoming', 'munipay' ); ?></option>
			<option value="AA"><?php esc_html_e( 'Armed Forces (AA)', 'munipay' ); ?></option>
			<option value="AE"><?php esc_html_e( 'Armed Forces (AE)', 'munipay' ); ?></option>
			<option value="AP"><?php esc_html_e( 'Armed Forces (AP)', 'munipay' ); ?></option>
		</select>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Please select a valid state.', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-4 mb-3">

		<label for="payment_city"><?php esc_html_e( 'City', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="payment_city" name="payment_city" placeholder="City" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Please provide a city.', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-3 mb-3">

		<label for="payment_zipcode"><?php esc_html_e( 'Zip Code', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="payment_zipcode" name="payment_zipcode" placeholder="" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Zip code required.', 'munipay' ); ?>
		</div>

	</div>

</div>

<hr class="mb-4">

<h4 class="mb-3"><?php esc_html_e( 'Payment', 'munipay' ); ?></h4>

<div class="row">

	<div class="col-md-6 mb-3">

		<label for="cc-name"><?php esc_html_e( 'Name on card', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="cc-name" placeholder="" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Name on card is required', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-6 mb-3">

		<label for="cc-number"><?php esc_html_e( 'Credit card number', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="cc-number" placeholder="" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Credit card number is required', 'munipay' ); ?>
		</div>

	</div>

</div>

<div class="row">

	<div class="col-md-6 mb-3">

		<label for="cc-expiration"><?php esc_html_e( 'Expiration', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="cc-expiration" placeholder="" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Expiration date required', 'munipay' ); ?>
		</div>

	</div>

	<div class="col-md-6 mb-3">

		<label for="cc-cvv"><?php esc_html_e( 'CVV', 'munipay' ); ?></label>

		<input type="text" class="form-control" id="cc-cvv" placeholder="" required>

		<div class="invalid-feedback">
			<?php esc_html_e( 'Security code required', 'munipay' ); ?>
		</div>

	</div>

</div>
