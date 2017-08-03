<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('woocommerce_email_header', $reason); ?>

<p><?php echo sprintf(__( "Your order with order number %s now has the following status: %s", 'nbdesigner' ),$order->get_order_number(),'<b>'.__( $reason, 'nbdesigner').'</b>'); ?></p>

<p><?php echo $message; ?></p>

<?php if ($reason == 'Your design rejected'): ?>
    <p><?php _e('Please log into your account and design again. Go to product detail page, redesign and save your design (haven\'t to add to cart!)', 'nbdesigner'); ?></p>
    <p><a href="<?php echo $my_order_url; ?>"><?php _e( 'Login to design again.', 'nbdesigner'); ?></a></p>
<?php endif; ?>

<?php do_action('woocommerce_email_footer'); ?>