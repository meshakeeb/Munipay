<?php
/**
 * Single Check edit template.
 *
 * @package Aheto
 */

use Munipay\Form;
?>
<div class="jumbotron p-4">

	<div id="check-heading-1">

		<h5 class="order-title d-flex align-items-center justify-content-between mb-0" data-toggle="collapse" data-target="#order-check-1" aria-expanded="true" aria-controls="order-check-1">
			Check Requester
			<span class="dashicons-stack">
				<span class="dashicons dashicons-arrow-up-alt2"></span>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</span>
		</h5>

	</div>

	<div id="order-check-1" class="mt-3" aria-labelledby="check-heading-1" data-parent="#orders">

		<div class="row">

			<?php

			Form::text(
				[
					'id'    => 'payee_name',
					'title' => 'Payee Name*',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'payee_number',
					'title' => 'Payee Number',
					'value' => '',
				]
			);

			?>

		</div>

		<div class="row pt-4">

			<?php

			Form::text(
				[
					'id'    => 'payee_address',
					'title' => 'Delivery address for check*',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'payee_email',
					'title' => 'Payee Email',
					'value' => '',
				]
			);

			?>

		</div>

		<div class="row pt-4">

			<?php

			Form::text(
				[
					'id'    => 'payee_address_2',
					'title' => 'Address 2',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'payee_phone',
					'title' => 'Phone',
					'value' => '',
				]
			);

			?>

		</div>

		<div class="row pt-4">

			<?php

			Form::text(
				[
					'id'    => 'payee_city',
					'title' => 'City*',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'payee_state',
					'title' => 'Phone*',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'payee_zipcode',
					'title' => 'Zip Code*',
					'value' => '',
				]
			);

			?>

		</div>

		<h5 class="pt-5 pb-3">Request Details</h5>

		<div class="row">

			<?php

			Form::select(
				[
					'id'      => 'request_reason',
					'title'   => 'Reason (printed on stub)*',
					'value'   => '',
					'options' => [
						'Vendor payment'    => 'Vendor payment',
						'Municipal payment' => 'Municipal payment',
						'Tax payment'       => 'Tax payment',
						'Other'             => 'Other',
					],
				]
			);

			Form::text(
				[
					'id'    => 'request_description',
					'title' => 'Memo (printed on stub) - 300-character limit',
					'value' => '',
				]
			);

			?>

		</div>

		<div class="row pt-4">

			<?php

			Form::text(
				[
					'id'    => 'request_reason_2',
					'title' => 'Notes (printed on check) - 25-character limit',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'request_amount',
					'title' => 'Amount*',
					'value' => '',
				]
			);

			?>

		</div>

		<div class="row pt-4">

			<?php

			Form::text(
				[
					'id'    => 'request_deliverydate',
					'title' => 'Projected delivery date*',
					'value' => '',
				]
			);

			Form::file(
				[
					'id'     => 'request_document',
					'title'  => 'Document(s) to be mailed with check*',
					'accept' => 'application/pdf',
					'value'  => '',
				]
			);

			?>

		</div>

		<h5 class="pt-5 pb-3">Approvals</h5>

		<div class="row">

			<?php

			Form::select(
				[
					'id'      => 'approver',
					'title'   => 'Approver*',
					'value'   => '',
					'options' => [
						'Shakeeb' => 'Shakeeb Ahmed',
					],
				]
			);

			Form::text(
				[
					'id'    => 'approved_date',
					'title' => 'Date approved*',
					'value' => '',
				]
			);

			?>

		</div>

		<div class="row pt-4">

			<?php

			Form::text(
				[
					'id'    => 'approver_email',
					'title' => 'Email*',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'approver_phone',
					'title' => 'Phone*',
					'value' => '',
				]
			);

			?>

		</div>

		<h5 class="pt-5">Internal Accounting Information</h5>

		<p>
			You can allocate this check amount (payment, transaction, delivery) by percentage to as many cost centers as needed. Click + for additional field.
		</p>

		<div class="row pt-4">

			<?php

			Form::text(
				[
					'id'    => 'account_cost_center',
					'title' => 'Cost center',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'account_network',
					'title' => 'Network',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'account_activity_code',
					'title' => 'Activity Code',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'account_gl_code',
					'title' => 'GL code*',
					'value' => '',
				]
			);

			Form::text(
				[
					'id'    => 'account_percentage',
					'title' => '% of total*',
					'value' => '',
				]
			);

			?>

			<div class="col-auto">
				<button type="button" class="btn btn-primary btn-sm mt-1 order-account-add">Add</button>
			</div>

		</div>

		<div class="text-center mt-5">
			<button type="button" class="btn btn-primary btn-lg">Save Check Request</button>
		</div>

	</div>

</div>
