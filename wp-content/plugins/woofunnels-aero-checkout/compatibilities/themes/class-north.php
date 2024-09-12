<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WFACP_Compatibility_With_North {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			remove_all_actions( 'woocommerce_checkout_before_customer_details', 5 );
			remove_all_actions( 'woocommerce_checkout_after_customer_details', 25 );
		}
	}
}

if ( ! function_exists( 'thb_body_classes' ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_North(), 'north' );
