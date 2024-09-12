<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WFACP_Compatibility_With_Ocean {
	public function __construct() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ], 99 );
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_actions' ], 99 );
		add_action( 'after_setup_theme', [ $this, 'actions' ], 9 );
	}


	public function actions() {

		if ( class_exists( 'WFACP_Common' ) && class_exists( 'OceanWP_Customizer' ) && WFACP_Common::is_customizer() ) {

			WFACP_Common::remove_actions( 'after_setup_theme', 'OceanWP_Customizer', 'register_options' );
		}
	}

	public function remove_woo_css() {

		$template = wfacp_template();
		if ( ! $template instanceof WFACP_Template_Common ) {
			return;
		}

		$tempType = $template->get_template_type();
		if ( $tempType != 'pre_built' && class_exists( 'OceanWP_WooCommerce_Config' ) ) {
			if ( class_exists( 'WC_Social_Login_Loader' ) ) {
				wp_dequeue_style( 'oceanwp-woocommerce' );
			}
		}

	}

	public function remove_actions() {
		if ( class_exists( 'OceanWP_WooCommerce_Config' ) ) {

			add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

			WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'OceanWP_WooCommerce_Config', 'checkout_timeline' );
			WFACP_Common::remove_actions( 'ocean_head_css', 'OceanWP_WooCommerce_Customizer', 'head_css' );
			WFACP_Common::remove_actions( 'ocean_head_css', 'OceanWP_General_Customizer', 'head_css' );
			$this->remove_woo_css();

		}
	}

}

if ( ! class_exists( 'OCEANWP_Theme_Class' ) ) {
	return;
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Ocean(), 'ocean' );
