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
						'title' => esc_html__( 'Payee Name*', 'munipay' ),
						'value' => $check->get_meta( 'payee_name' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_number',
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
						'id'    => 'payee_address',
						'title' => esc_html__( 'Delivery address for check*', 'munipay' ),
						'value' => $check->get_meta( 'payee_address' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_email',
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
						'id'    => 'payee_address_2',
						'title' => esc_html__( 'Address 2', 'munipay' ),
						'value' => $check->get_meta( 'payee_address_2' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_phone',
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
						'id'    => 'payee_city',
						'title' => esc_html__( 'City*', 'munipay' ),
						'value' => $check->get_meta( 'payee_city' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_state',
						'title' => esc_html__( 'State*', 'munipay' ),
						'value' => $check->get_meta( 'payee_state' ),
					]
				);

				Form::text(
					[
						'id'    => 'payee_zipcode',
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
						'id'      => 'request_reason',
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
						'id'    => 'request_description',
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
						'id'    => 'request_reason_2',
						'title' => esc_html__( 'Notes (printed on check) - 25-character limit', 'munipay' ),
						'value' => $check->get_meta( 'request_reason_2' ),
					]
				);

				Form::text(
					[
						'id'    => 'request_amount',
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
						'id'      => 'request_delivery_method',
						'title'   => esc_html__( 'Delivery method*', 'munipay' ),
						'value'   => $check->get_meta( 'request_delivery_method' ),
						'options' => [
							'1' => esc_html__( 'USPS Priority - 2 Day ($15)', 'munipay' ),
							'2' => esc_html__( 'USPS Priority Express Overnight ($45)', 'munipay' ),
							'3' => esc_html__( 'BUNDLE ($36)', 'munipay' ),
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
						'title' => esc_html__( 'Projected delivery date*', 'munipay' ),
						'value' => $check->get_meta( 'request_delivery_date' ),
					]
				);

				Form::file(
					[
						'id'     => 'request_document',
						'title'  => esc_html__( 'Document(s) to be mailed with check*', 'munipay' ),
						'accept' => 'application/pdf',
						'value'  => $check->get_meta( 'request_document' ),
					]
				);

				?>

			</div>

			<h5 class="pt-5 pb-3"><?php esc_html_e( 'Approvals', 'munipay' ); ?></h5>

			<div class="row">

				<?php

				Form::select(
					[
						'id'      => 'approver',
						'title'   => esc_html__( 'Approver*', 'munipay' ),
						'value'   => $check->get_meta( 'approver' ),
						'options' => [
							'Shakeeb' => 'Shakeeb Ahmed',
						],
					]
				);

				Form::text(
					[
						'id'    => 'approved_date',
						'title' => esc_html__( 'Date approved*', 'munipay' ),
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
						'title' => esc_html__( 'Email*', 'munipay' ),
						'value' => $check->get_meta( 'approver_email' ),
					]
				);

				Form::text(
					[
						'id'    => 'approver_phone',
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
							'id'    => 'account_cost_center_' . $index,
							'name'  => 'accounts[' . $index . '][cost_center]',
							'title' => esc_html__( 'Cost center', 'munipay' ),
							'value' => $account['cost_center'],
						]
					);

					Form::text(
						[
							'id'    => 'account_network_' . $index,
							'name'  => 'accounts[' . $index . '][network]',
							'title' => esc_html__( 'Network', 'munipay' ),
							'value' => $account['network'],
						]
					);

					Form::text(
						[
							'id'    => 'account_activity_code_' . $index,
							'name'  => 'accounts[' . $index . '][activity_code]',
							'title' => esc_html__( 'Activity Code', 'munipay' ),
							'value' => $account['activity_code'],
						]
					);

					Form::text(
						[
							'id'    => 'account_gl_code_' . $index,
							'name'  => 'accounts[' . $index . '][gl_code]',
							'title' => esc_html__( 'GL code*', 'munipay' ),
							'value' => $account['gl_code'],
						]
					);

					Form::text(
						[
							'id'    => 'account_percentage_' . $index,
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
