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

$form         = new stdClass;
$form->errors = new WP_Error;

$smart = new Smart_Payables( $form );

// Default date.
$to   = isset( $_POST['end_date'] ) ? $_POST['end_date'] : date( 'm/d/Y' );
$from = isset( $_POST['start_date'] ) ? $_POST['start_date'] : date( 'm/d/Y', strtotime( '-30 day' ) );

// Serch by field.
if ( isset( $_POST['field_value'] ) && ! empty( $_POST['field_value'] ) ) {
	$smart->data['q']      = $_POST['field_value'];
	$smart->data['field '] = $_POST['field_name'];

	$response = $smart->send( 'payments/search' );
} else {
	// Search by date.
	$smart->data['from_date'] = $from;
	$smart->data['to_date']   = $to;

	$response = $smart->send( 'payments/byDate' );
}
?>
<div class="container mt-5 mb-5">

	<h3 class="mb-5"><?php esc_html_e( 'Reports', 'munipay' ); ?></h3>

	<form method="post">

		<div class="form-inline">

			<div class="input-group mb-2 mr-sm-2">
				<div class="input-group-prepend">
					<div class="input-group-text">Search By</div>
				</div>
				<select class="form-control" name="field_name">
					<option value="">Select a field</option>
					<option value="id">Payment ID</option>
					<option value="payee">Payee Name</option>
					<option value="amount">Amount</option>
					<option value="check_num">Check Number</option>
					<option value="client_transaction_id">Client Transaction ID</option>
					<option value="notes">Notes</option>
					<option value="address">Address</option>
					<option value="zip">Zipcode</option>
					<option value="reference">Reference</option>
				</select>
				<input type="text" name="field_value" class="form-control" value="">
			</div>

			<h5>OR</h5>

		</div>

		<div class="form-inline">

			<div class="input-group mb-2 mr-sm-2">
				<div class="input-group-prepend">
					<div class="input-group-text">Start Date</div>
				</div>
				<input type="text" name="start_date" class="form-control report-datepicker" value="<?php echo $from; ?>">

				<div class="input-group-prepend">
					<div class="input-group-text">End Date</div>
				</div>
				<input type="text" name="end_date" class="form-control report-datepicker" value="<?php echo $to; ?>">

			</div>

		</div>

		<button type="submit" class="btn btn-primary btn-sm mb-2">Submit</button>

	</form>

	<?php Form::display_errors( $form->errors ); ?>

	<?php if ( false !== $response ) : ?>

		<table class="table table-bordered table-striped mt-3">
			<thead>
				<tr>
					<th>Payment ID</th>
					<th>Payment Date</th>
					<th>Payee</th>
					<th>Status</th>
					<th>Amount</th>
					<th>Reference</th>
					<th>Address 1</th>
					<th>Address 2</th>
					<th>City</th>
					<th>State</th>
					<th>Zipcode</th>
					<th>Memo</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$payments = $response['payments']['payment'];
		if ( isset( $payments['payment_id'] ) ) {
			$payments   = [];
			$payments[] = $response['payments']['payment'];
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
