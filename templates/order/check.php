<?php
/**
 * Single Check edit template.
 *
 * @package Aheto
 */

use Munipay\Form;

$check = $this->current_check;
?>
<div class="jumbotron p-4 order-check order-check-<?php echo $check->get_id(); ?>">

	<div class="order-check-header" id="<?php $check->get_html_id( 'check-heading' ); ?>">

		<h5 class="order-check-title d-flex align-items-center justify-content-between mb-0 collapsed" data-toggle="collapse" data-target="#<?php $check->get_html_id( 'order-check' ); ?>" aria-expanded="false" aria-controls="<?php $check->get_html_id( 'order-check' ); ?>">
			<?php $check->get_box_title(); ?>
			<span class="dashicons-stack">
				<span class="dashicons dashicons-arrow-up-alt2"></span>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</span>
		</h5>

	</div>

	<div id="<?php $check->get_html_id( 'order-check' ); ?>" class="mt-3 collapse" aria-labelledby="<?php $check->get_html_id( 'check-heading' ); ?>" data-parent="#orders">

		<form class="">

			<div class="row">

				<?php

				Form::text(
					[
						'id'    => 'payee_name',
						'title' => 'Payee Name*',
						'value' => $check->get_meta( 'payee_name' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_number',
						'title' => 'Payee Number',
						'value' => $check->get_meta( 'payee_number' ),
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
						'value' => $check->get_meta( 'payee_address' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_email',
						'title' => 'Payee Email',
						'value' => $check->get_meta( 'payee_email' ),
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
						'value' => $check->get_meta( 'payee_address_2' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_phone',
						'title' => 'Phone',
						'value' => $check->get_meta( 'payee_phone' ),
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
						'value' => $check->get_meta( 'payee_city' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_state',
						'title' => 'State*',
						'value' => $check->get_meta( 'payee_state' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_zipcode',
						'title' => 'Zip Code*',
						'value' => $check->get_meta( 'payee_zipcode' ),
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
						'value'   => $check->get_meta( 'request_reason' ),
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
						'value' => $check->get_meta( 'request_description' ),
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
						'value' => $check->get_meta( 'request_reason_2' ),
					]
				);

				Form::text(
					[
						'id'    => 'request_amount',
						'title' => 'Amount*',
						'value' => $check->get_meta( 'request_amount' ),
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::select(
					[
						'id'      => 'request_delivery_method',
						'title'   => 'Delivery method*',
						'value'   => $check->get_meta( 'request_delivery_method' ),
						'options' => [
							'1' => 'USPS Priority - 2 Day ($15)',
							'2' => 'USPS Priority Express Overnight ($45)',
							'3' => 'BUNDLE ($36)',
						],
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::text(
					[
						'id'    => 'request_delivery_date',
						'title' => 'Projected delivery date*',
						'value' => $check->get_meta( 'request_delivery_date' ),
					]
				);

				Form::file(
					[
						'id'     => 'request_document',
						'title'  => 'Document(s) to be mailed with check*',
						'accept' => 'application/pdf',
						'value'  => $check->get_meta( 'request_document' ),
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
						'value'   => $check->get_meta( 'approver' ),
						'options' => [
							'Shakeeb' => 'Shakeeb Ahmed',
						],
					]
				);

				Form::text(
					[
						'id'    => 'approved_date',
						'title' => 'Date approved*',
						'value' => $check->get_meta( 'approved_date' ),
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
						'value' => $check->get_meta( 'approver_email' ),
					]
				);

				Form::text(
					[
						'id'    => 'approver_phone',
						'title' => 'Phone*',
						'value' => $check->get_meta( 'approver_phone' ),
					]
				);

				?>

			</div>

			<h5 class="pt-5">Internal Accounting Information</h5>

			<p>
				You can allocate this check amount (payment, transaction, delivery) by percentage to as many cost centers as needed. Click + for additional field.
			</p>

			<div class="check-accounts">

				<?php foreach ( $check->get_meta( 'accounts' ) as $index => $account ) : ?>
				<div class="row pt-4 check-account">

					<?php

					Form::text(
						[
							'id'    => 'account_cost_center_' . $index,
							'name'  => 'accounts[' . $index . '][cost_center]',
							'title' => 'Cost center',
							'value' => $account['cost_center'],
						]
					);

					Form::text(
						[
							'id'    => 'account_network_' . $index,
							'name'  => 'accounts[' . $index . '][network]',
							'title' => 'Network',
							'value' => $account['network'],
						]
					);

					Form::text(
						[
							'id'    => 'account_activity_code_' . $index,
							'name'  => 'accounts[' . $index . '][activity_code]',
							'title' => 'Activity Code',
							'value' => $account['activity_code'],
						]
					);

					Form::text(
						[
							'id'    => 'account_gl_code_' . $index,
							'name'  => 'accounts[' . $index . '][gl_code]',
							'title' => 'GL code*',
							'value' => $account['gl_code'],
						]
					);

					Form::text(
						[
							'id'    => 'account_percentage_' . $index,
							'name'  => 'accounts[' . $index . '][percentage]',
							'title' => '% of total*',
							'value' => $account['percentage'],
						]
					);

					?>

					<div class="col-auto">
						<button type="button" class="btn btn-primary btn-sm mt-1 order-check-account-add"><span class="dashicons dashicons-plus-alt"></span></button>
						<button type="button" class="btn btn-danger btn-sm mt-1 order-check-account-remove"><span class="dashicons dashicons-trash"></span></button>
					</div>

				</div>
				<?php endforeach; ?>

			</div>

			<div class="text-center mt-5">
				<button type="button" class="btn btn-primary btn-lg order-check-save">Save Check Request</button>
			</div>

		</form>

	</div>

</div>
