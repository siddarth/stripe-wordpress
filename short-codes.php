<?php

// Short Codes
add_shortcode('stripe_payment', 'stripe_payment');	
function stripe_payment($atts, $content = null ) {
   	extract(shortcode_atts(array(
      "amount" 		=> 500.0,
      "payment_id"	=> null
   	), $atts));
      	
   	$errors = verify_configuration_settings();
	
	return $errors.create_payment_form($amount, $payment_id);
}

// Verify configuration settings
function verify_configuration_settings() {
	global $publicKey;
	global $secretKey;
	global $currencySymbol;
	
	$error = "";
	if( strlen($publicKey)==0) {
		$error .= "<li>Public key is not set.</li>";
	}
	if( strlen($secretKey)==0) {
		$error .= "<li>Secret key is not set.</li>";
	}
	if( strlen($currencySymbol)==0) {
		$error .= "<li>Secret key is not set.</li>";
	}
	
	if(strlen($error)>0) {
		$error = "<div class='stripe-payment-config-errors'><p>Fix the following configuration errors before using the form.</p><ul>".$error."</ul></div>";
	}
	
	return $error;
}

?>