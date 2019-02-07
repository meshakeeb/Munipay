<?php
/**
 * User order summary email
 *
 * @package Munipay
 */

defined( 'ABSPATH' ) || exit;

?>
<p>
	Good afternoon,
</p>

<p>
	Here’s confirmation of charges made to your authorized credit card to fund the checks you requested today. We’ve sent complete details about your check requests in a separate email.
</p>

<p>
	The attached CSV file has complete details about each request, including any internal approval and accounting information you provided.
</p>

<table>
	<thead>
		<tr>
			<th>Check #</th>
			<th>Recipient</th>
			<th>Reason</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $order->checks as $check ) : ?>
			<tr>
				<td><?php echo $check->get_id(); ?></td>
				<td><?php echo $check->get_meta( 'payee_name' ); ?></td>
				<td><?php echo $check->get_meta( 'request_reason' ); ?></td>
				<td><?php echo $check->get_amount(); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<p>
	Thanks for using the Munipay portal. If you have questions or need assistance, please email help@munipay.io or call 469-955-3570.
</p>
