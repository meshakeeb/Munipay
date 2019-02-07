<?php
/**
 * Tracking number generated email
 *
 * @package Munipay
 */

defined( 'ABSPATH' ) || exit;

?>
<p>
	Hi <?php echo $user->get( 'display_name' ); ?>,
</p>

<p>
	Your checks have successfully shipped and a tracking number is now available.
</p>

Tracking #: <?php echo $tracking; ?>

<p>
	You can track your package here: https://www.usps.com/manage/welcome.htm
</p>

<p>
	<strong>Thanks!</strong>
</p>
