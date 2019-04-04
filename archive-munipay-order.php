<?php
/**
 * The template for displaying all orders
 *
 * @since   1.0.0
 * @package Munipay
 * @author  BoltMedia <info@boltmedia.ca>
 */

get_header();

?>
<div class="container mt-5 mb-5">

	<h3 class="mb-5"><?php esc_html_e( 'Order History', 'munipay' ); ?></h3>

	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'templates/order/list', 'item' );
	}

	get_template_part( 'templates/pagination' );
	?>

</div>
<?php
get_footer();
