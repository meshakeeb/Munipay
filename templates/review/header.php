<?php
/**
 * Review header.
 *
 * @package Munipay
 */

?>
<div class="jumbotron py-4 px-2 bg-warning">

	<div class="container">

		<p class="h4 font-weight-normal">
			<?php esc_html_e( 'Use this page to make sure the check requests you’ve submitted are correct, including payee information, check amount and related fees.', 'munipay' ); ?>
		</p>

		<p class="h5 font-weight-normal pt-3">
			<?php esc_html_e( 'To correct a request, click the pencil icon in the upper right corner of the request to return to the the Enter page. Make your changes and click UPDATE to save. To delete a request, click the red X.', 'munipay' ); ?>
		</p>

		<p class="h5 font-weight-normal pt-3">
			<?php esc_html_e( 'When everything’s correct, click the orange CHECK OUT button to go to the Order page.', 'munipay' ); ?>
		</p>

		<div class="alert alert-secondary mt-4" role="alert">

			<p class="h5 font-weight-normal">
				<?php esc_html_e( 'If you have questions or need customer support, please email mana.sourcing@ericsson.com or call 469-266-5389.', 'munipay' ); ?>
			</p>

		</div>

	</div>

</div>
