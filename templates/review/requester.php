<?php
/**
 * Requester information.
 *
 * @package Munipay
 */

use Munipay\Form;

$order = $this->order;
?>
<ul class="list-group">

	<li class="list-group-item active">
		<h5 class="mb-0"><?php esc_html_e( 'Request Details', 'munipay' ); ?></h5>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Date', 'munipay' ); ?></strong>
		<?php echo $order->get_order_date(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Name', 'munipay' ); ?></strong>
		<?php echo $order->get_requester_name(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Email', 'munipay' ); ?></strong>
		<?php echo $order->get_requester_email(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Phone', 'munipay' ); ?></strong>
		<?php echo $order->get_requester_phone(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Signum', 'munipay' ); ?></strong>
		<?php echo $order->get_requester_signum(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block"><?php esc_html_e( 'Cost Center', 'munipay' ); ?></strong>
		<?php echo $order->get_requester_cost_center(); ?>
	</li>

</ul>
