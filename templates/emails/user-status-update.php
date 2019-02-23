<?php
/**
 * Status update email
 *
 * @package Munipay
 */

defined( 'ABSPATH' ) || exit;

?>
<p>
	Hi <?php echo $user->get( 'display_name' ); ?>,
</p>

<p>
	The status of request # <?php echo $check->get_id(); ?> has changed from <?php echo $old_status; ?> to <?php echo $new_status; ?>.
</p>

<p>
	Please visit the following link to view a detailed summary: <a href="<?php echo get_permalink( $check->get_object()->post_parent ); ?>">Click Order Details</a>
</p>

<p>
	Thanks!
</p>
