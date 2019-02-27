<?php
/**
 * Order item for archive page.
 *
 * @package Munipay
 */

use Munipay\Order;

$order = new Order( get_the_ID() );
?>

<div class="mt-5">

	<ul class="list-group flex-row flex-wrap">

		<li class="list-group-item col-12 bg-light">
			<div class="text-success">
				<h5 class="my-0"><?php esc_html_e( 'Order number # ', 'munipay' ); ?><?php echo $order->get_id(); ?></h5>
			</div>
		</li>

		<li class="list-group-item col-3">
			<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Date', 'munipay' ); ?></strong>
			<?php echo $order->get_order_date(); ?>
		</li>

		<li class="list-group-item col-3">
			<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Name', 'munipay' ); ?></strong>
			<?php echo $order->get_requester_name(); ?>
		</li>

		<li class="list-group-item col-3">
			<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Delivery Charges', 'munipay' ); ?></strong>
			<?php echo $order->get_delivery_charges(); ?>
		</li>

		<li class="list-group-item col-3">
			<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Transaction Charges', 'munipay' ); ?></strong>
			<?php echo $order->get_transaction_charges(); ?>
		</li>

		<?php foreach ( $order->checks as $check ) : ?>
		<li class="list-group-item col-12 d-flex justify-content-between lh-condensed">
			<div>
				<span class="badge badge-secondary font-weight-normal">Request # <?php echo $check->get_id(); ?></span>
				<?php $check->get_tracking_number(); ?>
				<span class="badge badge-warning font-weight-normal"><?php echo $check->get_status(); ?></span>
				<h6 class="mt-1 mb-0"><?php echo $check->get_meta( 'payee_name' ); ?></h6>
				<small class="text-muted"><em><?php echo esc_html_x( 'for', 'checkout form', 'munipay' ); ?></em> <?php echo $check->get_meta( 'request_reason' ); ?></small>
			</div>
			<strong><?php echo $check->get_amount(); ?></strong>
		</li>
		<?php endforeach; ?>

		<li class="list-group-item col-12 d-flex justify-content-between bg-light">
			<div class="text-success">
				<h6 class="my-0"><?php esc_html_e( 'Total (USD)', 'munipay' ); ?></h6>
			</div>
			<span class="text-success"><?php echo $order->get_total(); ?></span>
		</li>

	</ul>

</div>
