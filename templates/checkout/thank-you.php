<?php
/**
 * Checkout billing form.
 *
 * @package Munipay
 */

use Munipay\Form;
use Munipay\Order;

$order = new Order( $_GET['order'] );

$this->errors->add( 'success', __( 'Thank you. Your order has been received.', 'munipay' ) );
?>

<div class="container mt-5 mb-5">

	<?php Form::display_errors( $this->errors ); ?>

	<div class="row mt-5">

		<div class="col-md-4 offset-md-1 order-md-2 mb-4">

			<h4 class="d-flex justify-content-between align-items-center mb-3 px-2">
				<span class="text-muted"><?php esc_html_e( 'Total Checks', 'munipay' ); ?></span>
				<span class="badge badge-secondary badge-pill"><?php echo count( $order->checks ); ?></span>
			</h4>

			<ul class="list-group">
				<?php foreach ( $order->checks as $check ) : ?>
				<li class="list-group-item d-flex justify-content-between lh-condensed">
					<div>
						<h6 class="my-0"><?php echo $check->get_meta( 'payee_name' ); ?></h6>
						<small class="text-muted"><em><?php echo esc_html_x( 'for', 'checkout form', 'munipay' ); ?></em> <?php echo $check->get_meta( 'request_reason' ); ?></small>
					</div>
					<span class="text-muted"><?php echo $check->get_amount(); ?></span>
				</li>
				<?php endforeach; ?>

				<li class="list-group-item d-flex justify-content-between bg-light">
					<div class="text-success">
						<h6 class="my-0"><?php esc_html_e( 'Delivery Charges', 'munipay' ); ?></h6>
					</div>
					<span class="text-success"><?php echo $order->get_delivery_charges(); ?></span>
				</li>

				<li class="list-group-item d-flex justify-content-between bg-light">
					<div class="text-success">
						<h6 class="my-0"><?php esc_html_e( 'Transaction Charges', 'munipay' ); ?></h6>
					</div>
					<span class="text-success"><?php echo $order->get_transaction_charges(); ?></span>
				</li>

				<li class="list-group-item d-flex justify-content-between">
					<span><?php esc_html_e( 'Total (USD)', 'munipay' ); ?></span>
					<strong><?php echo $order->get_total(); ?></strong>
				</li>

			</ul>

		</div>

		<div class="col-md-7 order-md-1">

			<h4 class="d-flex justify-content-between align-items-center mb-3 px-2">
				<span class="text-muted"><?php esc_html_e( 'Details', 'munipay' ); ?></span>
			</h4>

			<ul class="list-group flex-row flex-wrap">

				<li class="list-group-item col-12 bg-light">
					<div class="text-success">
						<h6 class="my-0"><?php esc_html_e( 'Requester Details', 'munipay' ); ?></h6>
					</div>
				</li>

				<li class="list-group-item col-6">
					<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Date', 'munipay' ); ?></strong>
					<?php echo $order->get_order_date(); ?>
				</li>

				<li class="list-group-item col-6">
					<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Name', 'munipay' ); ?></strong>
					<?php echo $order->get_requester_name(); ?>
				</li>

				<li class="list-group-item col-6">
					<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Email', 'munipay' ); ?></strong>
					<?php echo $order->get_requester_email(); ?>
				</li>

				<li class="list-group-item col-6">
					<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Phone', 'munipay' ); ?></strong>
					<?php echo $order->get_requester_phone(); ?>
				</li>

				<li class="list-group-item col-6">
					<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Signum', 'munipay' ); ?></strong>
					<?php echo $order->get_requester_signum(); ?>
				</li>

				<li class="list-group-item col-6">
					<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Cost Center', 'munipay' ); ?></strong>
					<?php echo $order->get_requester_cost_center(); ?>
				</li>

				<li class="list-group-item col-12 bg-light">
					<div class="text-success">
						<h6 class="my-0"><?php esc_html_e( 'Order Details', 'munipay' ); ?></h6>
					</div>
				</li>

				<li class="list-group-item col-12 d-flex justify-content-between lh-condensed">
					<strong><?php _e( 'Munipay number:', 'munipay' ); ?></strong>
					<span># <?php echo $order->get_id(); ?></span>
				</li>

			</ul>

		</div>

	</div>

</div>
