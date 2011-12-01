<?php

// Create the html that defines the payment form.
//	Note: Do not add any 'name' attributes to input elements
//	that contain sensitive data. This prevents this data from
//	being posted to your site. All sensitive card holder info
//	is only sent to Stripe.com using HTTPS.
function create_payment_form($amount = 100, $paymentId = null, $paymentUrl=STRIPE_PAYMENTS_PAYMENT_URL) {
		
	return <<<EOT
<div id="stripe-payment-wrap">
	<form action="$paymentUrl" method="post" id="stripe-payment-form">
		<input id="paymentId" type="hidden" name="paymentId" value="$paymentId" />
		<div class="stripe-payment-form-row">
	        <label>Amount (USD $)</label>
			<input type="text" id="cardAmount" size="20" name="cardAmount" disabled="disabled" class="amount required" value="$amount" />
			<span class="error"></span>
	    </div>
	    <div class="stripe-payment-form-row">
			<label>Name on Card</label>
			<input type="text" id="cardName" size="20" name="cardName" class="required" />
			<span class="error"></span>
	    </div>
		<div class="stripe-payment-form-row">
			<label>Email Address</label>
			<input type="text" id="email" size="20" name="email" class="email required" />
			<span class="error"></span>
	    </div>
	    <div class="stripe-payment-form-row">
			<label>Card Number</label>
			<input type="text" size="20" id="cardNumber" class="number required stripe-sensitive" />
			<span class="error"></span>
	    </div>
	    <div class="stripe-payment-form-row">
			<label>CVC</label>
			<input type="text" size="4" id="cardCvc" class="number required stripe-sensitive" />
			<span class="error"></span>
	    </div>
	    <div class="stripe-payment-form-row">
			<label>Expiration</label>
			<select id="cardExpiryMonth" class="required card-expiry-month stripe-sensitive"></select>
			&nbsp;/&nbsp;
			<select id="cardExpiryYear" class="required card-expiry-year stripe-sensitive"></select>
	    </div>
	    <div class="stripe-payment-form-row-submit">
			<button id="stripe-payment-form-submit" type="submit" class="button">Submit Payment</button>
		</div>
		<div class="stripe-payment-form-row-progress">
			<span class="message"></span>
		</div>
	</form>
</div>
EOT;
}
?>