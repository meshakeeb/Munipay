<?php
/**
 * Single Check edit template.
 *
 * @package Aheto
 */

use Munipay\Form;

$check = $this->current_check;
$class = 'check-' . $check->get_id();
?>
<li class="list-group-item list-group-item-secondary <?php echo $class; ?>">
	<h5 class="mb-0 d-flex justify-content-between">
		<div><?php echo $check->get_meta( 'payee_name' ); ?></div>
		<div>
			<span class="small text-muted"><?php echo 'check #' . $check->get_id(); ?> | </span>
			<a href="<?php echo home_url( 'enter-checks/#order-check-' . $check->get_id() ); ?>"><span class="dashicons dashicons-edit mt-1 ml-2"></span></a>
			<a href="#" class="text-danger order-check-remove" data-check-id="<?php echo $check->get_id(); ?>"><span class="dashicons dashicons-trash mt-1 ml-1"></span></a>
		</div>
	</h5>
</li>

<li class="list-group-item d-flex justify-content-between lh-condensed border-bottom-0 pt-3 <?php echo $class; ?>">
	<div>
		<h6 class="my-0">Check amount</h6>
		<small class="text-muted"><em>for</em> <?php echo $check->get_meta( 'request_reason' ); ?></small>
	</div>
	<span class="text-success"><?php echo $check->get_amount(); ?></span>
</li>

<li class="list-group-item d-flex justify-content-between lh-condensed border-bottom-0 border-top-0 py-0 <?php echo $class; ?>">
	<div>
		<h6 class="my-0">Delivery fee</h6>
		<small class="text-muted"><?php echo $check->get_delivery_method(); ?></small>
	</div>
	<span class="text-success"><?php echo $check->get_delivery_fee(); ?></span>
</li>

<li class="list-group-item d-flex justify-content-between lh-condensed border-bottom-0 border-top-0 <?php echo $class; ?>">
	<div>
		<h6 class="my-0">Transaction fee</h6>
		<small class="text-muted">6% of total amount</small>
	</div>
	<span class="text-success"><?php echo $check->get_transaction_fee(); ?></span>
</li>

<li class="list-group-item d-flex justify-content-between border-top-0 <?php echo $class; ?>">
	<div>
		<h6 class="my-0">Sub Total</h6>
	</div>
	<span><?php echo $check->get_total(); ?></span>
</li>
