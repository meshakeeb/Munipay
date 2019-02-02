<?php
/**
 * Checkout billing form.
 *
 * @package Aheto
 */

$order = $this->order;
?>
<h4 class="mb-3"><?php esc_html_e( 'Billing address', 'munipay' ); ?></h4>

<form class="needs-validation" novalidate>

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

		<input type="text" class="form-control" id="payment_address_2" name="payment_address" placeholder="Apartment or suite">

	</div>

	<div class="row">

		<div class="col-md-5 mb-3">

			<label for="payment_state"><?php esc_html_e( 'State', 'munipay' ); ?></label>

			<select class="custom-select d-block w-100" id="payment_state" name="payment_state" required>
				<option value=""><?php esc_html_e( 'Choose...', 'munipay' ); ?></option>
				<option><?php esc_html_e( 'United States', 'munipay' ); ?></option>
			</select>

			<div class="invalid-feedback">
				<?php esc_html_e( 'Please select a valid state.', 'munipay' ); ?>
			</div>

		</div>

		<div class="col-md-4 mb-3">

			<label for="state"><?php esc_html_e( 'State', 'munipay' ); ?></label>

			<select class="custom-select d-block w-100" id="state" required>
				<option value=""><?php esc_html_e( 'Choose...', 'munipay' ); ?></option>
				<option><?php esc_html_e( 'California', 'munipay' ); ?></option>
			</select>

			<div class="invalid-feedback">
				<?php esc_html_e( 'Please provide a valid state.', 'munipay' ); ?>
			</div>

		</div>

		<div class="col-md-3 mb-3">

			<label for="zip"><?php esc_html_e( 'Zip', 'munipay' ); ?></label>

			<input type="text" class="form-control" id="zip" placeholder="" required>

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

			<small class="text-muted"><?php esc_html_e( 'Full name as displayed on card', 'munipay' ); ?></small>

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

		<div class="col-md-3 mb-3">

			<label for="cc-expiration"><?php esc_html_e( 'Expiration', 'munipay' ); ?></label>

			<input type="text" class="form-control" id="cc-expiration" placeholder="" required>

			<div class="invalid-feedback">
				<?php esc_html_e( 'Expiration date required', 'munipay' ); ?>
			</div>

		</div>

		<div class="col-md-3 mb-3">

			<label for="cc-cvv"><?php esc_html_e( 'CVV', 'munipay' ); ?></label>

			<input type="text" class="form-control" id="cc-cvv" placeholder="" required>

			<div class="invalid-feedback">
				<?php esc_html_e( 'Security code required', 'munipay' ); ?>
			</div>

		</div>

	</div>

	<hr class="mb-4">

	<button class="btn btn-primary btn-lg btn-block" type="submit"><?php esc_html_e( 'Continue to checkout', 'munipay' ); ?></button>

</form>
