<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Functions used by plugins
 */
if ( ! class_exists( 'WFCH_WC_Dependencies' ) ) {
	require_once __DIR__ . '/WFCH_WC_Dependencies.php';
}

/**
 * WC Detection
 */
if ( ! function_exists( 'wfch_is_woocommerce_active' ) ) {
	function wfch_is_woocommerce_active() {
		return WFCH_WC_Dependencies::woocommerce_active_check();
	}

}
