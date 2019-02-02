<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since   1.0.0
 * @package Munipay
 * @author  BoltMedia <info@boltmedia.ca>
 */

$is_fullwidth = is_page_template( 'page-fullwidth.php' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $is_fullwidth ? '' : 'container py-5' ); ?>>

	<?php if ( ! $is_fullwidth ) : ?>
	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header>
	<?php endif; ?>

	<div class="entry-content <?php echo $is_fullwidth ? '' : ' pt-4'; ?>">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'munipay' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
