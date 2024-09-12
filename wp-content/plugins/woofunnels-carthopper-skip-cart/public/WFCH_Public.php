<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/4/19
 * Time: 5:10 PM
 */

class WFCH_Public {
	protected static $_instance = null;
	protected $skip_cart = false;
	protected $current_product_id = 0;
	protected $rules = [];
	protected $rules_products = [];
	protected $cart_products = [];
	protected $category = [];
	protected $excludes = [];
	protected $product_exclude = false;
	protected $add_cart_button_text = '';
	protected $last_item_added_in_ajax_data = [];
	protected $skip_cart_redirect_checkout = false;
	protected $match_rule = [];
	protected $data = [];

	protected function __construct() {

		add_action( 'wp_loaded', [ $this, 'prepare_data' ], - 1 );
		add_action( 'wp', [ $this, 'wp_action_2' ], 2 );
		add_action( 'wp', [ $this, 'wp_action_10' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ], 100 );
		add_action( 'woocommerce_add_to_cart', [ $this, 'woocommerce_add_to_cart' ], 99, 4 );
		add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'change_button_text' ], 99, 2 );
		add_action( 'woocommerce_cart_is_empty', [ $this, 'reset_session' ] );
		add_action( 'woocommerce_cart_item_removed', [ $this, 'reset_session' ] );
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

	public function enqueue_script() {
		wp_enqueue_script( 'wfch-global', WFCH_Common::get_include_url() . '/assets/js/global.js', array( 'jquery' ), WFCH_VERSION_DEV, true );
		wp_enqueue_script( 'wfch_frontend', plugin_dir_url( WFCH_PLUGIN_FILE ) . 'public/assets/js/wfch.min.js', [ 'jquery', 'wfch-global' ], WFCH_VERSION_DEV, true );
	}


	public function prepare_data() {
		if ( ( isset( $_GET['page'] ) || isset( $_GET['post_type'] ) ) && is_admin() ) {
			return;
		}

		$data        = WFCH_Common::save_publish_checkout_pages_in_transient();
		$this->data  = $data;
		$this->rules = $data['rules'];

		$this->skip_cart = wc_string_to_bool( $data['skip_cart'] );
		$this->category  = $data['category'];
		$this->excludes  = $data['excludes'];

		if ( is_array( $data['excludes'] ) ) {
			$this->excludes = array_map( function ( $ex ) {
				return $ex['id'];
			}, $data['excludes'] );
		}
		if ( is_array( $data['category'] ) ) {
			$this->category = array_map( function ( $ex ) {
				return $ex['id'];
			}, $data['category'] );
		}

		if ( is_array( $this->rules ) ) {
			foreach ( $this->rules as $rin => $rule ) {
				if ( $rule['published'] == 'draft' ) {
					unset( $this->rules[ $rin ] );
				} else {
					$rproduct             = array_keys( $rule['products'] );
					$this->rules_products = array_merge( $this->rules_products, $rproduct );
				}
			}
			if ( count( $this->rules ) > 0 ) {
				$this->rules = array_values( $this->rules );
			}
		}

	}

	public function wp_action_2() {
		if ( is_admin() ) {
			return;
		}
		if ( class_exists( 'WFACP_Core' ) ) {
			//if version is greater than 1.8.0
			if ( version_compare( WFACP_VERSION, '1.8.0', '>=' ) ) {
				add_filter( 'wfacp_global_checkout_page_id', [ $this, 'assign_aero_checkout_page_id' ] );
			} else {
				add_filter( 'option__wfacp_global_settings', [ $this, 'assign_aero_checkout_page_id_below_1_8' ] );
			}
		}
	}

	public function wp_action_10() {
		if ( is_admin() ) {
			return;
		}

		global $post;
		if ( ! is_null( $post ) && 'product' == $post->post_type ) {
			$this->current_product_id = $post->ID;

		}
		if ( is_checkout() ) {
			WC()->session->set( 'wfch_last_added_element', null );
		}
	}

	public function skip_cart_enable() {
		return $this->skip_cart;
	}

	public function woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id ) {
		if ( ( empty( $this->rules_products ) || ! in_array( $product_id, $this->rules_products ) ) && true !== $this->skip_cart ) {
			$this->reset_session();

			return;
		}

		$this->last_item_added_in_ajax_data = [
			'key'          => $cart_item_key,
			'product_id'   => $product_id,
			'quantity'     => $quantity,
			'variation_id' => $variation_id,
		];

		WC()->session->set( 'wfch_last_added_element', $this->last_item_added_in_ajax_data );
		$this->check_rules( $cart_item_key, $product_id, $quantity, $variation_id );
	}

	/**
	 * Reset our session variable when item removed or cart empty
	 */
	public function reset_session() {
		WC()->session->set( 'wfch_last_added_element', null );
		WC()->session->set( 'wfch_aero_checkout_id', null );
	}

	public function check_rules( $cart_item_key, $product_id, $quantity, $variation_id ) {


		if ( did_action( 'wfacp_after_checkout_page_found' ) ) {
			return;
		}
		$checkout_pages = $this->get_matched_checkout();


		if ( $this->skip_cart ) {
			if ( in_array( $product_id, $this->excludes ) ) {
				$this->product_exclude = true;
			}
			if ( in_array( $variation_id, $this->excludes ) ) {
				$this->product_exclude = true;
			}
			$item = wc()->cart->get_cart_item( $cart_item_key );
			/**
			 * @var $temp_pro_obj WC_Product;
			 */
			if ( ! is_null( $item['data'] ) && $item['data'] instanceof WC_Product ) {
				$temp_pro_obj = $item['data'];
				$cats         = $temp_pro_obj->get_category_ids();
				if ( count( $this->category ) > 0 && ! empty( array_intersect( $cats, $this->category ) ) ) {
					$this->product_exclude = true;
				}
			}
		}
		if ( $this->product_exclude ) {
			return;
		}


		$redirect = false;
		if ( count( $checkout_pages ) == 1 ) {
			//if we found only one checkout page then we store the checkout page id in session
			//we use this session data at checkout page for determine which aero checkout page template is assign to checkout page
			WC()->session->set( 'wfch_aero_checkout_id', array_keys( $checkout_pages )[0] );
			$redirect = true;
		} else {

			WC()->session->set( 'wfch_aero_checkout_id', null );
			// if more than one chekcout page is found then we forcelly redirect to global checkout
			if ( count( $checkout_pages ) > 1 ) {
				$this->skip_cart = true;
			}
			// if global skip cart enable them we redirect to global checkout
			if ( $this->skip_cart ) {
				$redirect = true;
			}
		}


		if ( $redirect ) {
			$this->skip_cart_redirect_checkout = true;
			if ( ! wp_doing_ajax() ) {
				//we always redirect at native woocommerce checkout page if aero checkout present or not
				wp_redirect( $this->redirect_url() );
				exit;
			}
		}
	}

	private function get_matched_checkout() {
		$cart_contents = WC()->cart->get_cart();
		foreach ( $cart_contents as $cart_key => $item ) {

			$this->cart_products[] = $item['product_id'];
		}

		$this->cart_products = array_unique( $this->cart_products );
		$rules               = $this->get_rules();
		$checkout_pages      = [];
		$is_checkout         = is_checkout();


		foreach ( $rules as $rule ) {
			$rproduct = array_keys( $rule['products'] );
			if ( empty( $rproduct ) ) {
				continue;
			}
			$match       = false;
			$checkout_id = isset( $rule['checkout']['id'] ) ? absint( $rule['checkout']['id'] ) : 0;
			$skip_cart   = wc_string_to_bool( $rule['skip_cart'] );
			if ( '0' === $rule['match'] ) {
				if ( $this->all_match( $rproduct ) && count( $cart_contents ) == count( $rproduct ) ) {
					if ( true == $is_checkout ) {
						$checkout_pages[ $checkout_id ] = 1;
					}
					if ( false == $is_checkout && true == $skip_cart ) {
						$checkout_pages[ $checkout_id ] = 1;
					}
					$this->match_rule = $rule;

					return $checkout_pages;
				} else {
					if ( $this->any_match( $rproduct ) ) {
						$checkout_id = 0;
						$match       = true;
					}
				}

			} elseif ( '1' == $rule['match'] ) {
				if ( $this->any_match( $rproduct ) ) {
					$match = true;
				}
			}
			if ( $match ) {
				if ( ! isset( $checkout_pages[ $checkout_id ] ) ) {
					if ( true == $is_checkout ) {
						$checkout_pages[ $checkout_id ] = 1;
						continue;
					}
					if ( false == $is_checkout && true == $skip_cart ) {
						$checkout_pages[ $checkout_id ] = 1;
					}
				} else {
					$checkout_pages[ $checkout_id ] ++;
				}
				$this->match_rule = $rule;
			}
		}


		return $checkout_pages;
	}

	private function get_rules() {
		return apply_filters( 'wfch_setting_rules', $this->rules );
	}

	private function all_match( $search_this = [] ) {
		if ( empty( $search_this ) ) {
			return false;
		}


		return count( array_intersect( $search_this, $this->cart_products ) ) == count( $search_this );

	}

	private function any_match( $search_this ) {
		return ! empty( array_intersect( $search_this, $this->cart_products ) );
	}

	private function redirect_url() {

		return apply_filters( 'wfch_redirect_url', wc_get_checkout_url(), $this );
	}

	public function assign_aero_checkout_page_id( $page_id ) {
		$aero_checkout_id = WC()->session->get( 'wfch_aero_checkout_id', null );


		if ( is_null( $aero_checkout_id ) ) {
			$checkout_pages = $this->get_matched_checkout();
			if ( count( $checkout_pages ) == 1 ) {
				$checkout_pages = array_keys( $checkout_pages );
				$my_page_id     = $checkout_pages[0];
				if ( $my_page_id == 0 && $page_id > 0 ) {
					return $page_id;
				} else {
					$page_id = $my_page_id;
				}
			}
		} else {
			if ( $aero_checkout_id > 0 ) {
				$page_id = $aero_checkout_id;
			}

		}

		return $page_id;
	}

	public function assign_aero_checkout_page_id_below_1_8( $filter ) {
		if ( is_admin() ) {
			return;
		}
		$aero_checkout_id = WC()->session->get( 'wfch_aero_checkout_id', null );
		if ( is_null( $aero_checkout_id ) ) {
			$checkout_pages = $this->get_matched_checkout();
			if ( count( $checkout_pages ) == 1 ) {
				$checkout_pages = array_keys( $checkout_pages );
				$my_page_id     = $checkout_pages[0];
				if ( $my_page_id == 0 && $filter['override_checkout_page_id'] > 0 ) {
					return $filter;
				} else {
					$filter['override_checkout_page_id'] = $my_page_id;
				}
			}
		} else {
			if ( $aero_checkout_id > 0 ) {
				$filter['override_checkout_page_id'] = $aero_checkout_id;
			}
		}

		return $filter;
	}

	/**
	 * @param $btn_text string
	 * @param $product WC_Product
	 */
	public function change_button_text( $btn_text, $product ) {

		if ( $product->is_purchasable() && $product->get_id() == $this->current_product_id ) {
			$btn = $this->get_add_cart_button_text();
			if ( '' !== $btn ) {
				return $btn;
			}

		}

		return $btn_text;
	}


	private function get_add_cart_button_text() {

		if ( '' !== $this->add_cart_button_text ) {
			return $this->add_cart_button_text;
		}
		$rules = $this->get_rules();

		foreach ( $rules as $rule ) {
			$rproduct         = array_keys( $rule['products'] );
			$add_to_cart_text = trim( $rule['add_to_cart_text'] );
			if ( empty( $rproduct ) ) {
				continue;
			}

			if ( '' !== $add_to_cart_text && in_array( $this->current_product_id, $rproduct ) ) {
				$this->add_cart_button_text = $add_to_cart_text;
				break;
			}

		}
		if ( '' == $this->add_cart_button_text ) {
			if ( $this->skip_cart ) {
				$this->add_cart_button_text = $this->data['add_cart_button_text'];
			}
		}

		return $this->add_cart_button_text;
	}

	public function add_redirect_url( $fragment ) {
		if ( $this->skip_cart_redirect_checkout ) {
			$fragment['wfch_redirect'] = $this->redirect_url();

			return $fragment;
		}
		if ( ! is_null( WC()->session->get( 'wfch_last_added_element', null ) ) ) {
			$this->match_rule_by_session_last_item();
			$reload = 'no';
			if ( $this->skip_cart_redirect_checkout ) {
				$reload = 'yes';
			}
			$redirect_url             = $this->redirect_url();
			$fragment['.wfch_reload'] = '<div class="wfch_reload" style="display: none!important;" data-action="' . $reload . '" data-url="' . $redirect_url . '"></div>';
		}

		return $fragment;
	}

	private function match_rule_by_session_last_item() {
		if ( apply_filters( 'wfch_enable_ajax_check_rules', false ) ) {
			$last_added_element = WC()->session->get( 'wfch_last_added_element', null );
			if ( ! is_null( $last_added_element ) ) {
				WC()->session->set( 'wfch_last_added_element', null );
				$this->check_rules( $last_added_element['key'], $last_added_element['product_id'], $last_added_element['quantity'], $last_added_element['variation_id'] );
			}
		}
	}

	public function add_snippet_html() {
		echo '<div class="wfch_reload"  style="display: none!important;"  data-action="no"></div>';
	}

	public function match_rule() {
		return $this->match_rule;
	}

}

if ( class_exists( 'WFCH_Core' ) ) {
	WFCH_Core::register( 'public', 'WFCH_Public' );
}
