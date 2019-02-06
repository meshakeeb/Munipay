<?php
/**
 * Single Check edit template.
 *
 * @package Munipay
 */

use Munipay\Form;
use Munipay\Profile;

$check = $this->current_check;
?>
<div class="jumbotron p-4 order-check order-check-<?php echo $check->get_id(); ?>">

	<div class="order-check-header" id="<?php $check->get_html_id( 'check-heading' ); ?>">

		<h5 class="order-check-title d-flex align-items-center justify-content-between mb-0 collapsed" data-toggle="collapse" data-target="#<?php $check->get_html_id( 'order-check' ); ?>" aria-expanded="false" aria-controls="<?php $check->get_html_id( 'order-check' ); ?>">
			<span class="order-check-title-text"><?php $check->get_box_title(); ?></span>
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
						'name'  => 'payee_name',
						'title' => esc_html__( 'Payee Name*', 'munipay' ),
						'value' => $check->get_meta( 'payee_name' ),
					]
				);

				Form::text(
					[
						'name'  => 'payee_number',
						'title' => esc_html__( 'Payee Number', 'munipay' ),
						'value' => $check->get_meta( 'payee_number' ),
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::text(
					[
						'name'  => 'payee_address',
						'title' => esc_html__( 'Delivery address for check*', 'munipay' ),
						'value' => $check->get_meta( 'payee_address' ),
					]
				);

				Form::text(
					[
						'name'  => 'payee_email',
						'title' => esc_html__( 'Payee Email', 'munipay' ),
						'value' => $check->get_meta( 'payee_email' ),
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::text(
					[
						'name'  => 'payee_address_2',
						'title' => esc_html__( 'Address 2', 'munipay' ),
						'value' => $check->get_meta( 'payee_address_2' ),
					]
				);

				Form::text(
					[
						'name'  => 'payee_phone',
						'title' => esc_html__( 'Phone', 'munipay' ),
						'value' => $check->get_meta( 'payee_phone' ),
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::text(
					[
						'name'  => 'payee_city',
						'title' => esc_html__( 'City*', 'munipay' ),
						'value' => $check->get_meta( 'payee_city' ),
					]
				);

				Form::text(
					[
						'name'  => 'payee_state',
						'title' => esc_html__( 'State*', 'munipay' ),
						'value' => $check->get_meta( 'payee_state' ),
					]
				);

				Form::text(
					[
						'name'  => 'payee_zipcode',
						'title' => esc_html__( 'Zip Code*', 'munipay' ),
						'value' => $check->get_meta( 'payee_zipcode' ),
					]
				);

				?>

			</div>

			<h5 class="pt-5 pb-3"><?php esc_html_e( 'Request Details', 'munipay' ); ?></h5>

			<div class="row">

				<?php

				Form::select(
					[
						'name'    => 'request_reason',
						'title'   => esc_html__( 'Reason (printed on stub)*', 'munipay' ),
						'value'   => $check->get_meta( 'request_reason' ),
						'options' => [
							'Vendor payment'    => esc_html__( 'Vendor payment', 'munipay' ),
							'Municipal payment' => esc_html__( 'Municipal payment', 'munipay' ),
							'Tax payment'       => esc_html__( 'Tax payment', 'munipay' ),
							'Other'             => esc_html__( 'Other', 'munipay' ),
						],
					]
				);

				Form::text(
					[
						'name'  => 'request_description',
						'title' => esc_html__( 'Memo (printed on stub) - 300-character limit', 'munipay' ),
						'value' => $check->get_meta( 'request_description' ),
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::text(
					[
						'name'  => 'request_reason_2',
						'title' => esc_html__( 'Notes (printed on check) - 25-character limit', 'munipay' ),
						'value' => $check->get_meta( 'request_reason_2' ),
					]
				);

				Form::text(
					[
						'name'  => 'request_amount',
						'title' => esc_html__( 'Amount*', 'munipay' ),
						'value' => $check->get_meta( 'request_amount' ),
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::select(
					[
						'name'    => 'request_delivery_method',
						'title'   => esc_html__( 'Delivery method*', 'munipay' ),
						'value'   => $check->get_meta( 'request_delivery_method' ),
						'class'   => 'form-control request-delivery-method',
						'options' => [
							'1' => esc_html__( 'USPS Priority - 2 Day ($15)', 'munipay' ),
							'2' => esc_html__( 'USPS Priority Express Overnight ($45)', 'munipay' ),
							'3' => esc_html__( 'UPS - 2 Day - Bundle ($36)', 'munipay' ),
						],
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::text(
					[
						'name'  => 'request_delivery_date',
						'title' => esc_html__( 'Projected delivery date*', 'munipay' ),
						'value' => $check->get_meta( 'request_delivery_date' ),
						'class' => 'form-control js-datepicker',
					]
				);

				Form::file(
					[
						'name'   => 'request_document',
						'title'  => esc_html__( 'Document(s) to be mailed with check*', 'munipay' ),
						'accept' => 'application/pdf',
						'value'  => $check->get_meta( 'request_document' ),
					]
				);

				?>

			</div>

			<h5 class="pt-5 pb-3 bundle-fields"><?php esc_html_e( 'Bundle Package Info', 'munipay' ); ?></h5>

			<div class="row bundle-fields">

				<?php

				Form::select(
					[
						'name'    => 'bundle_contents',
						'title'   => esc_html__( 'Bundle Check Contents', 'munipay' ),
						'value'   => $check->get_meta( 'bundle_contents' ),
						'options' => [
							'loose'    => esc_html__( 'Loose', 'munipay' ),
							'envelope' => esc_html__( 'Envelope', 'munipay' ),
						],
					]
				);

				Form::text(
					[
						'name'  => 'bundle_mailto',
						'title' => esc_html__( 'Bundle Mail To', 'munipay' ),
						'value' => $check->get_meta( 'bundle_mailto' ),
					]
				);

				Form::text(
					[
						'name'  => 'bundle_address',
						'title' => esc_html__( 'Bundle Address', 'munipay' ),
						'value' => $check->get_meta( 'bundle_address' ),
					]
				);

				?>

			</div>

			<div class="row pt-4 bundle-fields">

				<?php

				Form::text(
					[
						'name'  => 'bundle_city',
						'title' => esc_html__( 'Bundle City', 'munipay' ),
						'value' => $check->get_meta( 'bundle_city' ),
					]
				);

				Form::text(
					[
						'name'  => 'bundle_state',
						'title' => esc_html__( 'Bundle State', 'munipay' ),
						'value' => $check->get_meta( 'bundle_state' ),
					]
				);

				Form::text(
					[
						'name'  => 'bundle_zip',
						'title' => esc_html__( 'Bundle Zipcode', 'munipay' ),
						'value' => $check->get_meta( 'bundle_zip' ),
					]
				);

				?>

			</div>

			<h5 class="pt-5 pb-3"><?php esc_html_e( 'Approvals', 'munipay' ); ?></h5>

			<div class="row">

				<?php

				Form::select(
					[
						'name'    => 'approver',
						'title'   => esc_html__( 'Approver*', 'munipay' ),
						'value'   => $check->get_meta( 'approver' ),
						'options' => Profile::get_users_choice(),
					]
				);

				Form::text(
					[
						'name'  => 'approved_date',
						'title' => esc_html__( 'Date approved*', 'munipay' ),
						'value' => $check->get_meta( 'approved_date' ),
						'class' => 'form-control js-datepicker',
					]
				);

				?>

			</div>

			<div class="row pt-4">

				<?php

				Form::text(
					[
						'name'  => 'approver_email',
						'title' => esc_html__( 'Email*', 'munipay' ),
						'value' => $check->get_meta( 'approver_email' ),
					]
				);

				Form::text(
					[
						'name'  => 'approver_phone',
						'title' => esc_html__( 'Phone*', 'munipay' ),
						'value' => $check->get_meta( 'approver_phone' ),
					]
				);

				?>

			</div>

			<h5 class="pt-5"><?php esc_html_e( 'Internal Accounting Information', 'munipay' ); ?></h5>

			<p>
				<?php esc_html_e( 'You can allocate this check amount (payment, transaction, delivery) by percentage to as many cost centers as needed. Click + for additional field.', 'munipay' ); ?>
			</p>

			<div class="check-accounts">

				<?php foreach ( $check->get_meta( 'accounts' ) as $index => $account ) : ?>
				<div class="row pt-4 check-account">

					<?php

					Form::text(
						[
							'name'  => 'accounts[' . $index . '][cost_center]',
							'title' => esc_html__( 'Cost center', 'munipay' ),
							'value' => $account['cost_center'],
						]
					);

					Form::text(
						[
							'name'  => 'accounts[' . $index . '][network]',
							'title' => esc_html__( 'Network', 'munipay' ),
							'value' => $account['network'],
						]
					);

					Form::text(
						[
							'name'  => 'accounts[' . $index . '][activity_code]',
							'title' => esc_html__( 'Activity Code', 'munipay' ),
							'value' => $account['activity_code'],
						]
					);

					Form::text(
						[
							'name'  => 'accounts[' . $index . '][gl_code]',
							'title' => esc_html__( 'GL code*', 'munipay' ),
							'value' => $account['gl_code'],
						]
					);

					Form::text(
						[
							'name'  => 'accounts[' . $index . '][percentage]',
							'title' => esc_html__( '% of total*', 'munipay' ),
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
				<input type="hidden" name="check_id" value="<?php echo $check->get_id(); ?>">
				<button type="button" class="btn btn-primary btn-lg order-check-save"><span><?php echo $check->get_id() ? esc_html__( 'Update Check Request', 'munipay' ) : esc_html__( 'Save Check Request', 'munipay' ); ?></span></button>
			</div>

		</form>

	</div>

</div>
