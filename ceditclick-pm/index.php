<?php
/*
Plugin Name: CreditClick Price Module
Plugin URI:
Description: Extends WooCommerce with a CreditClick pricing information on products.
Version: 1.2.0
Author: CreditClick
Author URI: https://creditclick.eu/
Text Domain: creditclick-pm-
Domain Path: /lang
Copyright: © 2018 CreditClick
License:
License URI:
*/



add_action( 'plugins_loaded', 'credit_click_pm_init', 0 );

function credit_click_pm_init() {

	if ( class_exists( 'WooCommerce' ) ) {
		// code that requires WooCommerce

		include_once( 'credit_click_pm.php' );

		new credit_click_pm();

		if ( is_admin() ) {
			include_once( 'credit_click_pm_admin.php' );
			new credit_click_pm_admin();
		}
	}
}
