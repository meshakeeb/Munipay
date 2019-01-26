<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @since   1.0.0
 * @package Munipay
 * @author  BoltMedia <info@boltmedia.ca>
 */
?>

	<footer id="colophon" class="site-footer">

		<div class="container">

			<div class="row align-items-center">

				<div class="col-4">
					<div>
						&COPY; <?php echo date( 'Y' ); ?> Munipay. All rights reserved.
					</div>
				</div>

				<div class="col-8">

					<?php
					wp_nav_menu(
						[
							'theme_location'  => 'footer_navigation',
							'container_class' => 'navbar navbar-expand-lg navbar-dark',
							'menu_class'      => 'navbar-nav ml-auto',
							'walker'          => new \Munipay\Bootstrap_Walker,
						]
					);
					?>

				</div>

			</div>

		</div>

	</footer><!-- #colophon -->

</div><!-- #page -->

<?php get_template_part( 'templates/tracking-code' ); ?>
<?php wp_footer(); ?>

</body>
</html>
