<?php
/**
 * Requester information.
 *
 * @package Munipay
 */

use Munipay\Form;

$check = $this->get_check_with_bundle_info();
?>
<div class="jumbotron p-4 d-none" id="order-bundle">

	<h5 class="pb-3"><?php esc_html_e( 'Bundle Package Info', 'munipay' ); ?></h5>

	<div class="row">

		<?php

		Form::select(
			[
				'id'      => 'bundle_contents',
				'title'   => esc_html__( 'Bundle Check Contents', 'munipay' ),
				'value'   => $check ? $check->get_meta( 'bundle_contents' ) : '',
				'options' => [
					''         => esc_html__( 'Select Type', 'munipay' ),
					'loose'    => esc_html__( 'Loose', 'munipay' ),
					'envelope' => esc_html__( 'Envelope', 'munipay' ),
				],
			]
		);

		Form::text(
			[
				'id'    => 'bundle_mailto',
				'value' => $check ? $check->get_meta( 'bundle_mailto' ) : '',
				'title' => esc_html__( 'Bundle Mail To', 'munipay' ),
			]
		);

		?>

	</div>

	<div class="row pt-4">

		<?php

		Form::text(
			[
				'id'    => 'bundle_address',
				'value' => $check ? $check->get_meta( 'bundle_address' ) : '',
				'title' => esc_html__( 'Bundle Address', 'munipay' ),
			]
		);

		Form::text(
			[
				'id'    => 'bundle_city',
				'value' => $check ? $check->get_meta( 'bundle_city' ) : '',
				'title' => esc_html__( 'Bundle City', 'munipay' ),
			]
		);

		?>

	</div>

	<div class="row pt-4">

		<?php

		Form::text(
			[
				'id'    => 'bundle_state',
				'value' => $check ? $check->get_meta( 'bundle_state' ) : '',
				'title' => esc_html__( 'Bundle State', 'munipay' ),
			]
		);

		Form::text(
			[
				'id'    => 'bundle_zip',
				'value' => $check ? $check->get_meta( 'bundle_zip' ) : '',
				'title' => esc_html__( 'Bundle Zipcode', 'munipay' ),
			]
		);

		?>

	</div>

</div>
