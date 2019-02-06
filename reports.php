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
$to   = date( 'm/d/Y' );
$from = date( 'm/d/Y', strtotime( '-30 day' ) );

// Set date.
$smart->data['from_date'] = isset( $_POST['start_date'] ) ? $_POST['start_date'] : $from;
$smart->data['to_date']   = isset( $_POST['end_date'] ) ? $_POST['end_date'] : $to;

$response = $smart->send( 'payments/byDate' );
?>
<div class="container mt-5 mb-5">

	<h3 class="mb-5"><?php esc_html_e( 'Reports', 'munipay' ); ?></h3>

	<form class="form-inline" method="post">
		<div class="input-group mb-2 mr-sm-2">
			<div class="input-group-prepend">
				<div class="input-group-text">Start Date</div>
			</div>
			<input type="text" name="start_date" class="form-control report-datepicker" value="<?php echo $from; ?>">
		</div>

		<div class="input-group mb-2 mr-sm-2">
			<div class="input-group-prepend">
				<div class="input-group-text">End Date</div>
			</div>
			<input type="text" name="end_date" class="form-control report-datepicker" value="<?php echo $to; ?>">
		</div>

		<button type="submit" class="btn btn-primary btn-sm mb-2">Submit</button>
	</form>

	<?php Form::display_errors( $form->errors ); ?>

	<?php if ( false !== $response ) : ?>

		<table class="table table-bordered table-striped mt-3">
			<thead>
				<tr>
					<th>Payment ID</th>
					<th>Payee</th>
					<th>Status</th>
					<th>Amount</th>
					<th>Payment Date</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$payments = array_reverse( $response['payments']['payment'] );
		foreach ( $payments as $payment ) :
			?>
			<tr>
				<td><?php echo $payment['payment_id']; ?></td>
				<td><?php echo $payment['payee']; ?></td>
				<td><?php echo $payment['status']; ?></td>
				<td><?php echo $payment['amount']; ?></td>
				<td><?php echo date( 'm/d/Y', $payment['date_updated'] ); ?></td>
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
