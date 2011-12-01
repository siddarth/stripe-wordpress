<?php
/*
Plugin Name: Stripe Payments
Plugin URI: http://diglabs.com
Description: This plugin allows the Stripe payment system to be easily integrated into Wordpress.
Author: Bob Cravens
Version: 1.5.19.1
Author URI: http://diglabs.com/
*/

// Settings
$isLiveKeys 		= get_option('stripe_payment_is_live_keys');
$isLive = strlen($isLiveKeys)==0?false:true;
$publicKey 			= get_option('stripe_payment_test_public_key');
$secretKey 			= get_option('stripe_payment_test_secret_key');
if($isLive) {
	$publicKey 		= get_option('stripe_payment_live_public_key');
	$secretKey 		= get_option('stripe_payment_live_secret_key');
}
$currencySymbol 	= get_option('stripe_payment_currency_symbol');
$transPrefix 		= get_option('stripe_payment_trans_prefix');

// Define variables
define( 'STRIPE_PAYMENTS_VERSION', '3.0.1' );

if ( ! defined( 'STRIPE_PAYMENTS_PLUGIN_BASENAME' ) )
	define( 'STRIPE_PAYMENTS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'STRIPE_PAYMENTS_PLUGIN_NAME' ) )
	define( 'STRIPE_PAYMENTS_PLUGIN_NAME', trim( dirname( STRIPE_PAYMENTS_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'STRIPE_PAYMENTS_PLUGIN_DIR' ) )
	define( 'STRIPE_PAYMENTS_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . STRIPE_PAYMENTS_PLUGIN_NAME );

if ( ! defined( 'STRIPE_PAYMENTS_PLUGIN_URL' ) )
	define( 'STRIPE_PAYMENTS_PLUGIN_URL', WP_PLUGIN_URL . '/' . STRIPE_PAYMENTS_PLUGIN_NAME );

if ( ! defined( 'STRIPE_PAYMENTS_PAYMENT_URL' ) )
	define( 'STRIPE_PAYMENTS_PAYMENT_URL', WP_PLUGIN_URL . '/payment' );

// Bootstrap this plugin
require_once STRIPE_PAYMENTS_PLUGIN_DIR . '/initialize.php';

?>