<?php
/**
 * Requester information.
 *
 * @package Aheto
 */

use Munipay\Form;

$order        = $this->order;
$current_user = wp_get_current_user();
?>
<form class="jumbotron p-4" id="order-requester">

	<h5 class="pb-3">Check Requester</h5>

	<div class="row">

		<?php

		Form::text(
			[
				'id'       => 'request_date',
				'title'    => 'Date',
				'value'    => date( get_option( 'date_format' ) ),
				'readonly' => 'readonly',
			]
		);

		Form::text(
			[
				'id'    => 'requester_email',
				'title' => 'Email',
				'value' => $order ? $order->get_meta( 'requester_email' ) : $current_user->get( 'user_email' ),
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
				'value' => $order ? $order->get_meta( 'requester_name' ) : $current_user->get( 'display_name' ),
			]
		);

		Form::text(
			[
				'id'    => 'requester_phone',
				'title' => 'Phone',
				'value' => $order ? $order->get_meta( 'requester_phone' ) : $current_user->get( 'phone' ),
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
				'value' => $order ? $order->get_meta( 'requester_signum' ) : $current_user->get( 'signum' ),
			]
		);

		Form::text(
			[
				'id'    => 'requester_cost_center',
				'title' => 'Cost center',
				'value' => $order ? $order->get_meta( 'requester_cost_center' ) : $current_user->get( 'cost_center' ),
			]
		);

		?>

	</div>

</form>
