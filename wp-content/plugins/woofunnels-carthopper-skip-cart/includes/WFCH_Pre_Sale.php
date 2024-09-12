<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/19/19
 * Time: 12:21 PM
 */

class WFCH_Pre_Sale {

	private static $_instance = null;
	private $post = null;
	private $cookie_name = '_wfch_open_pre_sale_page';
	private $expire = 86400;

	protected function __construct() {
		add_shortcode( 'wfch_product_page', [ $this, 'shortcode' ] );
		add_action( 'woocommerce_add_cart_item', [ $this, 'add_cart_item_meta' ], 99 );
		add_action( 'wp', [ $this, 'restrict_user_view_page' ] );
		add_filter( 'wfch_redirect_url', [ $this, 'change_redirect_url_pre_sale' ], 10, 2 );
		add_action( 'wfch_pre_sale_page', [ $this, 'pre_sale_page_actions' ] );
		add_action( 'wfch_show_popup_run', [ $this, 'remove_actions' ] );
	}

	/**
	 * @return null
	 */
	public static function get_instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	public function add_cart_item_meta( $cart_item_data ) {
		if ( isset( $_REQUEST['wfch_pre_sale'] ) && absint( $_REQUEST['wfch_pre_sale'] ) > 0 ) {
			$cart_item_data['_wfch_pre_data'] = [ 'pre_sale_id' => absint( $_REQUEST['wfch_pre_sale'] ) ];
		}

		return $cart_item_data;

	}


	/**
	 * @param $url
	 * @param $instance WFCH_Public
	 *
	 * @return mixed
	 */

	public function change_redirect_url_pre_sale( $url, $instance ) {
		if ( is_checkout() ) {
			return $url;
		}
		$rule = $instance->match_rule();

		if ( ! empty( $rule ) ) {
			$pre_sale = absint( $rule['pre_sale'] );
			if ( $pre_sale > 0 ) {
				$this->post = get_post( $pre_sale );
				if ( ! is_null( $this->post ) && 'publish' == $this->post->post_status ) {
					if ( ! WFCH_Common::is_pre_sale_page( $this->post->ID ) ) {
						return $url;
					}

					$sale_page   = $this->get_session_data();
					$pre_sale_id = $pre_sale;
					if ( ! empty( $sale_page ) && isset( $sale_page[ $pre_sale_id ] ) ) {
						return $url;
					}
					$sale_page[ $pre_sale_id ] = [ 'rule_id' => $rule['id'] ];
					$this->set_session_data( $sale_page );

					return get_the_permalink( $this->post->ID );
				}
			}
		}

		return $url;
	}


	private function get_session_data() {
		$sale_page = false;
		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			$sale_page = $_COOKIE[ $this->cookie_name ];
		} else {
			$sale_page = WC()->session->get( $this->cookie_name, false );
		}
		if ( false !== $sale_page ) {
			$sale_page = json_decode( $sale_page, true );
		}

		return $sale_page;
	}

	private function set_session_data( $sale_page = [] ) {
		if ( ! is_array( $sale_page ) ) {
			$sale_page = [];
		}
		setcookie( $this->cookie_name, json_encode( $sale_page ), $this->expire, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN, false, true );

		WC()->session->set( $this->cookie_name, json_encode( $sale_page ) );
	}


	public function restrict_user_view_page() {

		if ( is_admin() || is_checkout() || wp_doing_ajax() ) {
			return;
		}
		global $post;
		$redirect   = false;
		$this->post = $post;
		if ( ! is_null( $post ) ) {

			if ( false == WFCH_Common::is_pre_sale_page( $this->post->ID ) ) {
				$this->clear_session_data();

				return;
			}
			$sale_page = $this->get_session_data();
			if ( ! is_array( $sale_page ) || ! isset( $sale_page[ $this->post->ID ] ) ) {
				$redirect = true;
			}
			if ( $redirect ) {
				if ( WC()->cart->is_empty() ) {
					wp_redirect( site_url() );
				} else {
					wp_redirect( wc_get_checkout_url() );
				}
				$this->clear_session_data();

			}

			do_action( 'wfch_pre_sale_page', $this, $this->post );
		}

	}

	public function clear_session_data() {
		WC()->session->__unset( $this->cookie_name );
		setcookie( $this->cookie_name, '{}', - 1, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN, false, true );
	}

	public function pre_sale_page_actions() {

		add_filter( 'woocommerce_product_add_to_cart_url', function ( $url ) {

			$url = add_query_arg( [ 'wfch_pre_sale' => $this->post->ID ], $url );

			return $url;
		} );
	}

	public function shortcode() {
		include_once( WFCH_PRE_SALE_DIR . 'grid/layout_1/grid.php' );
	}

	public function remove_actions() {
		remove_action( 'woocommerce_after_single_product_summary', 'storefront_single_product_pagination', 30 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );


		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
		add_filter( 'woocommerce_product_tabs', function () {
			return [];
		}, 9999 );
	}


	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'can`t converted to string' );
	}

	/**
	 * To avoid cloning of current class
	 */
	protected function __clone() {
	}
}


if ( class_exists( 'WFCH_Core' ) ) {
	WFCH_Core::register( 'pre_sale', 'WFCH_Pre_Sale' );
}
