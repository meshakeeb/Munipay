<?php
/**
 * The template for displaying all single posts
 *
 * @since   1.0.0
 * @package Munipay
 * @author  BoltMedia <info@boltmedia.ca>
 */

use Munipay\Form;
use Munipay\Smart_Payables;

wp_enqueue_style( 'jquery-ui-base', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css' );
wp_enqueue_script( 'jquery-ui-datepicker' );

get_header();

$smart   = Smart_Payables::create();
$reports = $smart->get_reports();
?>
<div class="container mt-5 mb-5">

	<h3 class="mb-5"><?php esc_html_e( 'Reports', 'munipay' ); ?></h3>

	<form method="post">

		<div class="form-inline">

			<div class="input-group mb-2 mr-sm-2">
				<div class="input-group-prepend">
					<div class="input-group-text"><?php esc_html_e( 'Search By', 'munipay' ); ?></div>
				</div>
				<?php

				Form::select(
					[
						'name'    => 'field_name',
						'value'   => isset( $smart->data['field'] ) ? $smart->data['field'] : '',
						'options' => [
							''                      => esc_html__( 'Select a field', 'munipay' ),
							'id'                    => esc_html__( 'Payment ID', 'munipay' ),
							'payee'                 => esc_html__( 'Payee Name', 'munipay' ),
							'amount'                => esc_html__( 'Amount', 'munipay' ),
							'check_num'             => esc_html__( 'Check Number', 'munipay' ),
							'client_transaction_id' => esc_html__( 'Client Transaction ID', 'munipay' ),
							'notes'                 => esc_html__( 'Notes', 'munipay' ),
							'address'               => esc_html__( 'Address', 'munipay' ),
							'zip'                   => esc_html__( 'Zipcode', 'munipay' ),
							'reference'             => esc_html__( 'Reference', 'munipay' ),
						],
					],
					true
				);

				Form::text(
					[
						'name'  => 'field_value',
						'value' => isset( $smart->data['q'] ) ? $smart->data['q'] : '',
					],
					true
				);
				?>
			</div>

			<h5><?php esc_html_e( 'OR', 'munipay' ); ?></h5>

		</div>

		<div class="form-inline">

			<div class="input-group mb-2 mr-sm-2">
				<div class="input-group-prepend">
					<div class="input-group-text"><?php esc_html_e( 'Start Date', 'munipay' ); ?></div>
				</div>
				<input type="text" name="start_date" class="form-control report-datepicker" value="<?php echo $reports['from']; ?>">

				<div class="input-group-prepend">
					<div class="input-group-text"><?php esc_html_e( 'End Date', 'munipay' ); ?></div>
				</div>
				<input type="text" name="end_date" class="form-control report-datepicker" value="<?php echo $reports['to']; ?>">

			</div>

		</div>

		<button type="submit" class="btn btn-secondary btn-sm mb-2" name="download_report"><?php esc_html_e( 'Download as CSV', 'munipay' ); ?></button>
		<button type="submit" class="btn btn-primary btn-sm mb-2"><?php esc_html_e( 'Submit', 'munipay' ); ?></button>

	</form>

	<?php Form::display_errors( $smart->form->errors ); ?>

	<?php if ( false !== $reports['response'] ) : ?>

		<table class="table table-bordered table-striped mt-3">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Payment ID', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Payment Date', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Payee', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Status', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Amount', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Reference', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Address 1', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Address 2', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'City', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'State', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Zipcode', 'munipay' ); ?></th>
					<th><?php esc_html_e( 'Memo', 'munipay' ); ?></th>
				</tr>
			</thead>
			<tbody>
		<?php
		$payments = $reports['response']['payments']['payment'];
		if ( isset( $payments['payment_id'] ) ) {
			$payments   = [];
			$payments[] = $reports['response']['payments']['payment'];
		}
		$payments = array_reverse( $payments );
		foreach ( $payments as $payment ) :
			?>
			<tr>
				<td><?php echo $payment['payment_id']; ?></td>
				<td><?php echo date( 'm/d/Y', $payment['date_updated'] ); ?></td>
				<td><?php echo $payment['payee']; ?></td>
				<td><?php echo $payment['status']; ?></td>
				<td><?php echo $payment['amount']; ?></td>
				<td><?php echo $payment['reference']; ?></td>
				<td><?php echo $payment['address']; ?></td>
				<td><?php echo ! empty( $payment['address2'] ) ? $payment['address2'] : ''; ?></td>
				<td><?php echo $payment['city']; ?></td>
				<td><?php echo $payment['state']; ?></td>
				<td><?php echo $payment['zip']; ?></td>
				<td><?php echo $payment['memo']; ?></td>
			</tr>
			<?php
		endforeach;
		?>
			</tbody>
		</table>
	<?php endif; ?>

</div>
<?php
get_footer();
