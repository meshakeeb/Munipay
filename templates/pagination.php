<?php
/**
 * Template part for displaying pagination
 *
 * @since   1.0.0
 * @package Munipay
 * @author  BoltMedia <info@boltmedia.ca>
 */

if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
	return;
}

// Set up paginated links.
$links = paginate_links(
	[
		'mid_size'  => 2,
		'type'      => 'array',
		'prev_text' => sprintf(
			'&laquo; <span class="nav-prev-text">%s</span>',
			__( 'Newer posts', 'munipay' )
		),
		'next_text' => sprintf(
			'<span class="nav-next-text">%s</span> &raquo;',
			__( 'Older posts', 'munipay' )
		),
	]
);
if ( empty( $links ) ) {
	return;
}

$links = str_replace( 'page-numbers', 'page-link', $links );
$links = str_replace( 'current\'', '\' style="z-index: 1;color: #fff;background-color: #007bff;border-color: #007bff;"', $links );

$r  = '';
$r .= "<ul class='pagination justify-content-center'>\n\t<li class=\"page-item\">";
$r .= join( "</li>\n\t<li class=\"page-item\">", $links );
$r .= "</li>\n</ul>\n";
?>
<nav class="navigation mt-5" role="navigation">
	<?php echo $r; ?>
</nav>
