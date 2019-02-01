<?php
/**
 * Checkout billing form.
 *
 * @package Aheto
 */

$order = $this->order;
?>
<h4 class="mb-3">Billing address</h4>

<form class="needs-validation" novalidate>

	<div class="mb-3">

		<label for="requester_name">Requester Name</label>

		<input type="text" class="form-control" id="requester_name" name="requester_name" value="<?php echo $order->get_requester_name(); ?>" required>

		<div class="invalid-feedback">
			Valid requester name is required.
		</div>

	</div>

	<div class="row">

		<div class="col-md-6 mb-3">

			<label for="requester_email">Email</label>

			<div class="input-group">

				<div class="input-group-prepend">
					<span class="input-group-text">@</span>
				</div>

				<input type="text" class="form-control" id="requester_email" name="requester_email" value="<?php echo $order->get_requester_email(); ?>" required>

				<div class="invalid-feedback">
					Valid email is required.
				</div>

			</div>

		</div>

		<div class="col-md-6 mb-3">

			<label for="requester_phone">Phone</label>

			<input type="text" class="form-control" id="requester_phone" name="requester_phone" value="<?php echo $order->get_requester_phone(); ?>" required>

			<div class="invalid-feedback">
				Valid phone is required.
			</div>

		</div>

	</div>

	<div class="row">

		<div class="col-md-6 mb-3">

			<label for="requester_signum">Signum</label>

			<input type="text" class="form-control" id="requester_signum" name="requester_signum" value="<?php echo $order->get_requester_signum(); ?>" required>

			<div class="invalid-feedback">
				Valid signum is required.
			</div>

		</div>

		<div class="col-md-6 mb-3">

			<label for="requester_cost_center">Cost Center</label>

			<input type="text" class="form-control" id="requester_cost_center" name="requester_cost_center" value="<?php echo $order->get_requester_cost_center(); ?>" required>

			<div class="invalid-feedback">
				Valid cost center is required.
			</div>

		</div>

	</div>

	<div class="mb-3">

		<label for="payment_address">Address</label>

		<input type="text" class="form-control" id="payment_address" name="payment_address" placeholder="1234 Main St" required>

		<div class="invalid-feedback">
			Please enter your shipping address.
		</div>

	</div>

	<div class="mb-3">

		<label for="payment_address_2">Address 2 <span class="text-muted">(Optional)</span></label>

		<input type="text" class="form-control" id="payment_address_2" name="payment_address" placeholder="Apartment or suite">

	</div>

	<div class="row">

		<div class="col-md-5 mb-3">

			<label for="payment_state">State</label>

			<select class="custom-select d-block w-100" id="payment_state" name="payment_state" required>
				<option value="">Choose...</option>
				<option>United States</option>
			</select>

			<div class="invalid-feedback">
				Please select a valid state.
			</div>

		</div>

		<div class="col-md-4 mb-3">

			<label for="state">State</label>

			<select class="custom-select d-block w-100" id="state" required>
				<option value="">Choose...</option>
				<option>California</option>
			</select>

			<div class="invalid-feedback">
				Please provide a valid state.
			</div>

		</div>

		<div class="col-md-3 mb-3">

			<label for="zip">Zip</label>

			<input type="text" class="form-control" id="zip" placeholder="" required>

			<div class="invalid-feedback">
				Zip code required.
			</div>

		</div>

	</div>

	<hr class="mb-4">

	<h4 class="mb-3">Payment</h4>

	<div class="row">

		<div class="col-md-6 mb-3">

			<label for="cc-name">Name on card</label>

			<input type="text" class="form-control" id="cc-name" placeholder="" required>

			<small class="text-muted">Full name as displayed on card</small>

			<div class="invalid-feedback">
				Name on card is required
			</div>

		</div>

		<div class="col-md-6 mb-3">

			<label for="cc-number">Credit card number</label>

			<input type="text" class="form-control" id="cc-number" placeholder="" required>

			<div class="invalid-feedback">
				Credit card number is required
			</div>

		</div>

	</div>

	<div class="row">

		<div class="col-md-3 mb-3">

			<label for="cc-expiration">Expiration</label>

			<input type="text" class="form-control" id="cc-expiration" placeholder="" required>

			<div class="invalid-feedback">
				Expiration date required
			</div>

		</div>

		<div class="col-md-3 mb-3">

			<label for="cc-cvv">CVV</label>

			<input type="text" class="form-control" id="cc-cvv" placeholder="" required>

			<div class="invalid-feedback">
				Security code required
			</div>

		</div>

	</div>

	<hr class="mb-4">

	<button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>

</form>
