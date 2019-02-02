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

	<h5 class="pb-3"><?php esc_html_e( 'Check Requester', 'munipay' ); ?></h5>

	<div class="row">

		<?php

		Form::text(
			[
				'id'       => 'request_date',
				'title'    => esc_html__( 'Date', 'munipay' ),
				'value'    => $order->get_order_date(),
				'readonly' => 'readonly',
			]
		);

		Form::text(
			[
				'id'    => 'requester_email',
				'title' => esc_html__( 'Email', 'munipay' ),
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
				'title' => esc_html__( 'Requester Name', 'munipay' ),
				'value' => $order->get_requester_name(),
			]
		);

		Form::text(
			[
				'id'    => 'requester_phone',
				'title' => esc_html__( 'Phone', 'munipay' ),
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
				'title' => esc_html__( 'Signum', 'munipay' ),
				'value' => $order->get_requester_signum(),
			]
		);

		Form::text(
			[
				'id'    => 'requester_cost_center',
				'title' => esc_html__( 'Cost center', 'munipay' ),
				'value' => $order->get_requester_cost_center(),
			]
		);

		?>

	</div>

</form>
