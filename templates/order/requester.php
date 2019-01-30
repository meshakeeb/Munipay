<?php
/**
 * Requester information.
 *
 * @package Aheto
 */

use Munipay\Form;

$current_user = wp_get_current_user();
?>
<form class="jumbotron p-4">

	<h5 class="pb-3">Check Requester</h5>

	<div class="row">

		<?php

		Form::text(
			[
				'id'       => 'request_date',
				'title'    => 'Date',
				'value'    => date( get_option( 'date_format' ) ),
				'disabled' => 'disabled',
			]
		);

		Form::text(
			[
				'id'    => 'requester_email',
				'title' => 'Email',
				'value' => $current_user->get( 'user_email' ),
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
				'value' => $current_user->get( 'user_login' ),
			]
		);

		Form::text(
			[
				'id'    => 'requester_phone',
				'title' => 'Phone',
				'value' => $current_user->get( 'phone' ),
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
				'value' => $current_user->get( 'signum' ),
			]
		);

		Form::text(
			[
				'id'    => 'requester_cost_center',
				'title' => 'Cost center',
				'value' => $current_user->get( 'cost_center' ),
			]
		);

		?>

	</div>

</form>
