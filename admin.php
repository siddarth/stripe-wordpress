<?php 

add_action('admin_menu', 'stripe_admin_menu');
function stripe_admin_menu() {
	add_options_page(
		'Stripe Payments', 
		'Stripe Payments', 
		1, 
		__FILE__, 
		'stripe_admin_form'
		);
}
function stripe_admin_form() {
	include('admin_form.php');
}

add_action('admin_head', 'admin_register_head');
function admin_register_head() {
	$url = STRIPE_PAYMENTS_PLUGIN_URL.'/admin.css';
	echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}

?>