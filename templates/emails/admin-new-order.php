<?php
/**
 * Admin new order email
 *
 * @package Munipay
 */

defined( 'ABSPATH' ) || exit;

?>
<p>
	Hi <?php echo $user->get( 'display_name' ); ?>,
</p>

<p>
	Here are the checks requested by Ericsson today for processing by Smart Payables tomorrow.
</p>

<table>
	<thead>
		<tr>
			<th>Request #</th>
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
	<strong>Thanks!</strong>
</p>
