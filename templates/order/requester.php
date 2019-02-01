<?php
/**
 * Requester information.
 *
 * @package Aheto
 */

use Munipay\Form;

$order = $this->order;
?>
<form class="jumbotron p-4" id="order-requester">

	<h5 class="pb-3">Check Requester</h5>

	<div class="row">

		<?php

		Form::text(
			[
				'id'       => 'request_date',
				'title'    => 'Date',
				'value'    => $order->get_order_date(),
				'readonly' => 'readonly',
			]
		);

		Form::text(
			[
				'id'    => 'requester_email',
				'title' => 'Email',
				'value' => $order->get_requester_email(),
			]
		);

		?>

	</div>

	<div class="row pt-4">

		<?php

		Form::text(
			[
				'id'    => 'requester_name',
				'title' => 'Requester Name',
				'value' => $order->get_requester_name(),
			]
		);

		Form::text(
			[
				'id'    => 'requester_phone',
				'title' => 'Phone',
				'value' => $order->get_requester_phone(),
			]
		);

		?>

	</div>

	<div class="row pt-4">

		<?php

		Form::text(
			[
				'id'    => 'requester_signum',
				'title' => 'Signum',
				'value' => $order->get_requester_signum(),
			]
		);

		Form::text(
			[
				'id'    => 'requester_cost_center',
				'title' => 'Cost center',
				'value' => $order->get_requester_cost_center(),
			]
		);

		?>

	</div>

</form>
