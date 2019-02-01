<?php
/**
 * Requester information.
 *
 * @package Aheto
 */

use Munipay\Form;

$order = $this->order;
?>
<ul class="list-group">

	<li class="list-group-item active">
		<h5 class="mb-0">Request Details</h5>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block">Date</strong>
		<?php echo $order->get_order_date(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block">Name</strong>
		<?php echo $order->get_requester_name(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block">Email</strong>
		<?php echo $order->get_requester_email(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block">Phone</strong>
		<?php echo $order->get_requester_phone(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block">Signum</strong>
		<?php echo $order->get_requester_signum(); ?>
	</li>

	<li class="list-group-item">
		<strong class="small font-weight-bold d-block">Cost Center</strong>
		<?php echo $order->get_requester_cost_center(); ?>
	</li>

</ul>
