<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class wfch_AJAX_Controller
 * Handles All the request came from front end or the backend
 */
abstract class WFCH_AJAX_Controller {
	public static function init() {
		/**
		 * Backend AJAX actions
		 */
		if ( is_admin() ) {
			self::handle_admin_ajax();
		}
	}

	public static function handle_admin_ajax() {
		add_action( 'wp_ajax_wfch_create_skip_rules', array( __CLASS__, 'create_skip_rules' ) );
		add_action( 'wp_ajax_wfch_delete_skip_rules', array( __CLASS__, 'delete_skip_rules' ) );
		add_action( 'wp_ajax_wfch_product_search', array( __CLASS__, 'product_search' ) );
		add_action( 'wp_ajax_wfch_update_page_status', array( __CLASS__, 'update_page_status' ) );
		add_action( 'wp_ajax_wfch_save_menu_order', array( __CLASS__, 'save_menu_order' ) );
		add_action( 'wp_ajax_wfch_save_skip_cart_setting', array( __CLASS__, 'save_skip_cart_setting' ) );
	}

	public static function create_skip_rules() {
		self::check_nonce();
		$resp = array(
			'msg'    => 'Checkout Page not found',
			'status' => false,
		);
		if ( isset( $_POST['products'] ) && is_array( $_POST['products'] ) && ! empty( $_POST['products'] ) ) {
			$post                = array();
			$post['post_title']  = 'wfch_' . time();
			$post['post_type']   = WFCH_Common::get_post_type_slug();
			$post['post_status'] = 'publish';

			$saving_data                     = [];
			$saving_data['products']         = $_POST['products'];
			$saving_data['match']            = $_POST['match'];
			$saving_data['skip_cart']        = $_POST['skip_cart'];
			$saving_data['checkout']         = $_POST['checkout'];
			$saving_data['add_to_cart_text'] = $_POST['add_to_cart_text'];
			if ( isset( $_POST['wfch_id'] ) && $_POST['wfch_id'] > 0 ) {
				$wfch_id   = absint( $_POST['wfch_id'] );
				$post_data = get_post( $wfch_id );
				if ( ! is_null( $post_data ) ) {
					$saving_data['id'] = $wfch_id;
					update_post_meta( $wfch_id, '_wfch_data', $saving_data );
					$saving_data['published'] = $post_data->post_status;
					$resp['status']           = true;
					$resp['update']           = 'yes';
					$resp['wfch_id']          = $wfch_id;
					$resp['data']             = WFCH_Common::get_map_data( $saving_data );
					$resp['msg']              = __( 'Rules Successfully Updated', 'woofunnels-carthopper-skip-cart' );
					WFCH_Common::save_publish_checkout_pages_in_transient();
				}
				self::send_resp( $resp );
			}
			$menu_order = WFCH_Common::get_highest_menu_order();

			$post['menu_order'] = $menu_order + 1;
			$wfch_id            = wp_insert_post( $post );
			if ( $wfch_id !== 0 && ! is_wp_error( $wfch_id ) ) {
				$saving_data['id'] = $wfch_id;
				update_post_meta( $wfch_id, '_wfch_data', $saving_data );
				$resp['status'] = true;

				$saving_data['published'] = 'publish';
				$resp['wfch_id']          = $wfch_id;
				$resp['data']             = WFCH_Common::get_map_data( $saving_data );
				$resp['msg']              = 'Rules Successfully Added';
				WFCH_Common::save_publish_checkout_pages_in_transient();
			} else {
				$resp['msg'] = 'Rules creation error';
			}

		}

		self::send_resp( $resp );
	}

	public static function check_nonce() {
		$rsp = [
			'status' => 'false',
			'msg'    => 'Invalid Call',
		];
		if ( ! isset( $_REQUEST['wfch_nonce'] ) || ! wp_verify_nonce( $_REQUEST['wfch_nonce'], 'wfch_secure_key' ) ) {
			wp_send_json( $rsp );
		}
	}

	public static function send_resp( $data = array() ) {
		if ( ! is_array( $data ) ) {
			$data = [];
		}
		$data['nonce'] = wp_create_nonce( 'wfch_secure_key' );
		wp_send_json( $data );
	}


	public static function product_search( $term = false, $return = false ) {
		self::check_nonce();
		$term = wc_clean( empty( $term ) ? stripslashes( $_POST['term'] ) : $term );
		if ( empty( $term ) ) {
			wp_die();
		}
		$variations = true;
		$ids        = WFCH_Common::search_products( $term, $variations );
		/**
		 * Products types that are allowed in the offers
		 */
		$allowed_types   = apply_filters( 'wfch_offer_product_types', array(
			'simple',
			'variable',
			'course',
			'subscription',
			'variable-subscription',
			'virtual_subscription',
			'bundle',
			'yith_bundle',
			'woosb',
			'braintree-subscription',
			'braintree-variable-subscription',
		) );
		$product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_editable' );
		$product_objects = array_filter( $product_objects, function ( $arr ) use ( $allowed_types ) {
			return $arr && is_a( $arr, 'WC_Product' ) && in_array( $arr->get_type(), $allowed_types );
		} );
		$products        = array();
		/**
		 * @var $product_object WC_Product;
		 */
		foreach ( $product_objects as $product_object ) {
			$products[] = array(
				'id'    => $product_object->get_id(),
				'title' => rawurldecode( WFCH_Common::get_formatted_product_name( $product_object ) ),
			);
		}
		wp_send_json( apply_filters( 'wfch_woocommerce_json_search_found_products', $products ) );
	}


	public static function update_page_status() {
		self::check_nonce();
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['wfch_id'] ) && $_POST['wfch_id'] > 0 && isset( $_POST['post_status'] ) ) {
			$args    = [
				'ID'          => $_POST['wfch_id'],
				'post_status' => 'publish' == $_POST['post_status'] ? 'publish' : 'draft',
			];
			$post_id = wp_update_post( $args );

			$resp = array(
				'msg'     => __( 'Product rules status updated', 'woofunnels-carthopper-skip-cart' ),
				'status'  => true,
				'wfch_id' => $post_id,
			);
			WFCH_Common::save_publish_checkout_pages_in_transient();
		}
		self::send_resp( $resp );
	}


	public static function delete_skip_rules() {
		self::check_nonce();
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['wfch_id'] ) && $_POST['wfch_id'] > 0 ) {

			wp_delete_post( $_POST['wfch_id'] );

			$resp = array(
				'msg'     => __( 'Rules deleted', 'woofunnels-carthopper-skip-cart' ),
				'status'  => true,
				'wfch_id' => $_POST['wfch_id'],
			);
			WFCH_Common::save_publish_checkout_pages_in_transient();
		}
		self::send_resp( $resp );
	}

	public static function save_skip_cart_setting() {
		self::check_nonce();


		$data                         = [];
		$data['skip_cart']            = isset( $_POST['skip_cart'] ) ? $_POST['skip_cart'] : false;
		$data['category']             = isset( $_POST['category'] ) ? $_POST['category'] : [];
		$data['excludes']             = isset( $_POST['excludes'] ) ? $_POST['excludes'] : [];
		$data['add_cart_button_text'] = isset( $_POST['add_cart_button_text'] ) ? $_POST['add_cart_button_text'] : '';
		update_option( 'wfch_setting_rules', $data );

		$resp = array(
			'msg'    => __( 'Settings updated', 'woofunnels-carthopper-skip-cart' ),
			'status' => true,
		);
		WFCH_Common::save_publish_checkout_pages_in_transient();
		self::send_resp( $resp );
	}

	public static function save_menu_order() {
		self::check_nonce();

		if ( isset( $_POST['menu_order'] ) ) {
			$menu_order = $_POST['menu_order'];
			$length     = count( $menu_order );
			foreach ( $menu_order as $rule_id ) {
				$args = [ 'menu_order' => $length, 'ID' => absint( $rule_id ) ];
				wp_update_post( $args );
				$length --;
			}
		}
		$resp = array(
			'msg'    => __( '', 'woofunnels-carthopper-skip-cart' ),
			'status' => true,
		);
		WFCH_Common::save_publish_checkout_pages_in_transient();
		self::send_resp( $resp );
	}

}

WFCH_AJAX_Controller::init();
