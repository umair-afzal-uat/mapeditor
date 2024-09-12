<?php


class WFCH_Smart_Coupon_SkyVerge extends WFCH_CMP_Singleton {

	private static $instance = null;
	private $added_product = [];

	public static function getInstance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function attach_hooks( $status ) {
		$public = WFCH_Core()->public;

		if ( ! is_null( $public ) ) {
			add_action( 'woocommerce_add_to_cart', [ $this, 'woocommerce_add_to_cart' ], 98, 4 );
			add_action( 'template_redirect', [ $this, 'check_rules' ], 1 );
			remove_action( 'woocommerce_add_to_cart', [ $public, 'woocommerce_add_to_cart' ], 99 );
		}

		return $status;
	}

	public function woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id ) {
		$this->added_product[] = [ $cart_item_key, $product_id, $quantity, $variation_id ];
	}

	public function check_rules() {
		if ( count( $this->added_product ) > 0 ) {
			foreach ( $this->added_product as $item ) {
				WFCH_Core()->public->check_rules( $item[0], $item[1], $item[2], $item[3] );
			}
		}

	}

	protected function action() {

		add_filter( 'wc_url_coupons_url_matches_coupon', [ $this, 'attach_hooks' ] );
	}
}

WFCH_Compatibilities::register( WFCH_Smart_Coupon_SkyVerge::getInstance(), 'SkyVerge_url_coupons' );
