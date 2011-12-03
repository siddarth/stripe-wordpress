<?php

// Required PHP files
require_once STRIPE_PAYMENTS_PLUGIN_DIR . '/admin.php';
require_once STRIPE_PAYMENTS_PLUGIN_DIR . '/form.php';
require_once STRIPE_PAYMENTS_PLUGIN_DIR . '/ajax-payment.php';
require_once STRIPE_PAYMENTS_PLUGIN_DIR . '/short-codes.php';


// WP Header Hook (add javascript and css to the page)
add_action('wp_head', 'addHeaderCode', 0);
function addHeaderCode() {
	// Use the global defined in the stripe.php file.
	global $publicKey;
	global $isLiveKeys;
	
    if (function_exists('wp_enqueue_script')) {
    	// mark the beginning of our area
    	echo "\n<!--Stripe Payment Plugin Begin-->\n";
    	
    	// include our CSS styles
        echo '<link type="text/css" rel="stylesheet" href="' . STRIPE_PAYMENTS_PLUGIN_URL . '/stripe.css" />' . "\n";
        
        // add our scripts and their dependencies
    	wp_enqueue_script('jquery');
        wp_enqueue_script('stripe_payment_plugin', STRIPE_PAYMENTS_PLUGIN_URL . '/stripe.js', array('jquery'), '3.2.2');
	wp_enqueue_script('stripe', 'https://js.stripe.com/v1/', array('jquery'), '1.5.19');
		
		// emit the javascript variable that holds our public strip key
		$isLive = strlen($isLiveKeys)==0?'false':'true';
        echo "<script type='text/javascript'>var stripePublishable='$publicKey';var isLiveKeys=$isLive;</script>\n";
        
        // mark the end of our area
    	echo "\n<!--Stripe Payment Plugin End-->\n";
    }
}

?>