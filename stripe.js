var $ = jQuery;

$(document).ready(function(){
	// If the keys are not the 'live' ones
	if(!isLiveKeys) {
		var dom = '<div class="stripe-payment-form-warning">';
		dom += '<h3>Demo Mode</h3>';
		dom += '<p>Use the the credit card number <strong>4242-4242-4242</strong> and ';
		dom += 'a CVC of <strong>any 3 or 4 digit number</strong> for testing.</p>';
		dom += '</div>';
		$('#stripe-payment-wrap').prepend(dom);
		$('#cardNumber').val('424242424242');
		$('#cardCvc').val('123');
		$('#cardName').val('John Doe');
		$('#email').val('john.doe@example.com');
	}

	// Set the public key for use by Stripe.com
	Stripe.setPublishableKey(stripePublishable);
	
	// Auto-populate month and year drop downs
	showMonths();
	showYears();
	
	// Automatically add the autocomplete='off' attribute to all the input fields
	$("input").attr("autocomplete", "off");
	
	// Sanitize and validate all input elements
	$("input").blur(function(){
		var input = $(this);
		sanitize(input);
		validate(input);
	});
	
	// Bind to the submit for the form
    $("#stripe-payment-form").submit(function(event) {
    
    	// Check for configuration errors
    	if($('.stripe-payment-config-errors').length>0) {
    		alert('Fix the configuration errors before continuing.');
    		return false;
    	}
    
		// Lock the form so no change or double submission occurs
		lock_form();
    	// Trigger validation
    	if(!validateForm()) {
    		// The form is not valid…exit early
    		unlock_form();
    		return false;
    	}
    	
    	// Get the form values
    	var params = {};
    	params['name'] 		= $('#cardName').val();
    	params['number'] 	= $('#cardNumber').val();
    	params['cvc']		= $('#cardCvc').val();
    	params['exp_month'] = $('#cardExpiryMonth').val();
    	params['exp_year']	= $('#cardExpiryYear').val();
    	
        // Get the charge amount and convert to cents
        var amount = 100;
 
        // Validate card information using Stripe.com.
        //	Note: createToken returns immediately. The card
        //	is not charged at this time (only validated).
        //	The card holder info is HTTPS posted to Stripe.com
        //	for validation. The response contains a 'token'
        //	that we can use on our server.
        progress('Validating card data…');
        Stripe.createToken(params, amount, function(status, response){
                	        	
		    if (response.error) {
		    	// Show the error and unlock the form.
		    	progress(response.error.message);
		    	unlock_form();
		    	return false;
		    }
		    
		    // Collect additional info to post to our server.
		    //	Note: We are not posting any card holder info.
		    //	We only include the 'token' provided by Stripe.com.
		    var charge = {};
		    charge['token']		= response['id'];
		    charge['amount']	= amount;
		    charge['paymentId'] = $('#paymentId').val();
		    charge['email']		= $('#email').val();
		    charge['desc']	= $('#desc').val();
		    charge['action']	= 'stripe_plugin_process_card';
		    progress('Submitting charge…');
		    $.post('/wp-admin/admin-ajax.php', charge, function(response){
		    	// Try to parse the response (expecting JSON).
		    	try {
		    		response = JSON.parse(response);
		    	} catch (err) {
		    		// Invalid JSON.
		    		if(!$.trim(response).length) {
		    			response = { error: 'Server returned empty response during charge attempt'};
		    		} else {
		    			response = {error: 'Server returned invalid response:<br /><br />' + response};
		    		}
		    	}
		    	
		    	if(response['success']){
		    		// Card was successfully charged. Replace the form with a
		    		//	dynamically generated receipt.
                    $('#stripe-payment-wrap').html("<h4>Thank You</h4><b>$" + response['amount'] + " is making its way to our bank account.</b><br />Transaction ID: " + response['id'] + "<br /><br />").css('background-color', '#fff');
                    $("<a href='javascript:void(0);' class='red'>Make another charge</a>").click(function(){ location.href = location.href; }).appendTo('#stripe-payment-wrap');
   		    		progress('success');
		    	} else {
		    		// Show the error.
		    		progress(response['error']);
		    	}
		    	// Unlock the form.
		    	unlock_form();
		    });
        });
        
        // Do not submit the form.
        return false;
    });
});

// Lock and unlock the form. This prevents changes or 
//	double submissions during payment processing.
function lock_form() {
	$("#stripe-payment-form input").not('.amount').attr("disabled", "disabled");
	$("#stripe-payment-form select").attr("disabled", "disabled");
	$("#stripe-payment-form button").attr("disabled", "disabled");
}
function unlock_form() {
	$("#stripe-payment-form input").not('.amount').removeAttr("disabled");
	$("#stripe-payment-form select").removeAttr("disabled");
	$("#stripe-payment-form button").removeAttr("disabled");
}

// Helper function to display progress messages.
function progress(msg){
	$('.stripe-payment-form-row-progress span.message').html(msg);
}

// Validation helpers.
function validateForm() {
	var isValid = true;
	$("input").each(function(){
		sanitize($(this));
		isValid = validate($(this)) && isValid;
	});
	return isValid;
}
function sanitize(elem) {
	var value = $.trim(elem.val());
	if(elem.hasClass("number")){
		value = value.replace(/[^\d]+/g, '');
	}
	if(elem.hasClass("amount")){
        value = value.replace(/[^\d\.]+/g, '');
        if(value.length) value = parseFloat(value).toFixed(2);
	}
	elem.val(value);
}
function validate(elem) {
	var row = elem.closest('.stripe-payment-form-row');
	var error = $('.error', row);
	var value = $.trim(elem.val());
	if(elem.hasClass("required") && !value.length){
		error.html('Required.');
		return false;
	}
	if(elem.hasClass("amount") && value<0.50){
		error.html('Minimum charge is $0.50');
		return false;
	}
	error.html('');
	return true;
}

// Automatically populate the month and year selections.
function showMonths() {
	var months = $(".card-expiry-month"),
		month = new Date().getMonth() + 1;
	for(var i=1;i<=12;i++){
		months.append($("<option value='"+(i<10?"0":"")+i+"' "+(month===i ? "selected" : "")+">"+(i<10?"0":"")+i+"</option>"));
	}
}
function showYears() {
	var years = $(".card-expiry-year"),
	    year = new Date().getFullYear();
	
	for (var i = 0; i < 12; i++) {
	    years.append($("<option value='"+(i + year)+"' "+(i === 0 ? "selected" : "")+">"+(i + year)+"</option>"))
	}
}