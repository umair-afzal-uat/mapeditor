<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Woocommerce Side Cart Premium
 * http://xootix.com/side-cart-woocommerce
 * Class WFACP_Compatibility_Xoo_Wsc
 */
class WFACP_Compatibility_Xoo_Wsc {
	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'remove_action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_css' ] );
	}

	public function remove_action() {
		WFACP_Common::remove_actions( 'woocommerce_update_order_review_fragments', 'Xoo_Wsc_Cart', 'set_ajax_fragments' );
		WFACP_Common::remove_actions( 'wp_footer', 'xoo_wsc_Cart_Data', 'get_cart_markup' );
		WFACP_Common::remove_actions( 'wp_footer', 'Xoo_Wsc_Frontend', 'cart_markup' );
	}

	public function add_css() {

		echo "<style>";
		echo "body.xoo-wsc-cart-active, html.xoo-wsc-cart-active{overflow: inherit !important;}";
		echo ".xoo-wsc-cart-trigger {display: none !important;}";
		echo "</style>";
	}
}


if ( ! class_exists( 'Xoo_Wsc_Cart' ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Xoo_Wsc(), 'Xoo_Wsc' );
