<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/4/19
 * Time: 5:07 PM
 */

class WFCH_Admin {
	protected static $_instance = null;
	protected $page_loaded = false;

	protected function __construct() {
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 90 );

		if ( isset( $_GET['page'] ) && $_GET['page'] == WFCH_SLUG ) {
//			add_filter( 'woocommerce_settings_tabs_array', [ $this, 'register_settings' ], 99 );
//			add_action( 'woocommerce_sections_wfch_cart', [ $this, 'show_cart_settings' ] );
			add_action( 'admin_head', [ $this, 'admin_enqueue_assets' ], 99 );
			add_action( 'admin_menu', [ $this, 'admin_enqueue_style' ], 99 );
			add_action( 'admin_footer', [ $this, 'footer' ] );
			add_action( 'in_admin_header', array( $this, 'maybe_remove_all_notices_on_page' ) );
		}

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

	public function register_admin_menu() {
		add_submenu_page( 'woofunnels', __( 'CartHopper', 'woofunnels-carthopper-skip-cart' ), __( 'CartHopper', 'woofunnels-carthopper-skip-cart' ), 'manage_woocommerce', WFCH_SLUG, array(
			$this,
			'show_cart_settings',
		) );
	}

	public function register_settings( $tabs ) {

		$tabs['wfch_cart'] = 'Skip Cart';

		return $tabs;
	}


	public function show_cart_settings() {

		$this->page_loaded = true;
		include __DIR__ . "/templates/settings.php";

	}

	public function admin_enqueue_style() {
		wp_enqueue_style( 'wfch-admin', $this->get_admin_url() . '/assets/css/admin.css', array(), WFCH_VERSION_DEV );
	}

	public function get_admin_url() {
		return plugin_dir_url( WFCH_PLUGIN_FILE ) . 'admin';
	}

	public function admin_enqueue_assets() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_script( 'jquery-tiptip' );
		wp_enqueue_script( 'jquery-ui-sortable' );


		wp_enqueue_style( 'wfch-izimodal', $this->get_admin_url() . '/assets/iziModal/iziModal.css', array(), WFCH_VERSION_DEV );
		wp_enqueue_script( 'wfch-izimodal', $this->get_admin_url() . '/assets/iziModal/iziModal.js', array(), WFCH_VERSION_DEV );
		wp_enqueue_style( 'wfch-vue-multiselect', $this->get_admin_url() . '/assets/css/vue-multiselect.min.css', array(), WFCH_VERSION_DEV );
		wp_enqueue_script( 'wfch-vuejs', $this->get_admin_url() . '/assets/js/vue.min.js', array(), '2.5.13' );
		wp_enqueue_script( 'wfch-vue-multiselected', $this->get_admin_url() . '/assets/js/vue-multiselect.min.js', array(), '2.1.0' );

		wp_enqueue_script( 'wfch-global', WFCH_Common::get_include_url() . '/assets/js/global.js', array( 'jquery' ), WFCH_VERSION_DEV, true );

		wp_enqueue_script( 'wfch-admin', $this->get_admin_url() . '/assets/js/skip-cart.min.js', array( 'jquery' ), WFCH_VERSION_DEV, true );
		wp_localize_script( 'wfch-admin', 'wfch_data', $this->get_settings() );
		wp_localize_script( 'wfch-admin', 'wfch_localization', $this->get_localization() );
		wp_localize_script( 'wfch-admin', 'wfch_secure', [ 'nonce' => wp_create_nonce( 'wfch_secure_key' ) ] );

	}

	public function get_settings() {

		$data                        = WFCH_Common::save_publish_checkout_pages_in_transient();
		$data['aero_checkout_pages'] = new stdClass();
		$cat_args                    = array(
			'orderby'    => 'term_id',
			'order'      => 'ASC',
			'hide_empty' => false,
		);
		$categories                  = [];
		$terms                       = get_terms( 'product_cat', $cat_args );
		if ( count( $terms ) > 0 ) {
			foreach ( $terms as $index => $term ) {
				$categories[] = [ 'id' => $term->term_id, 'title' => $term->name ];
			}
		}

		if ( count( $categories ) > 0 ) {
			$data['category_list'] = $categories;
		}


		$woofunnel_transient_obj = WooFunnels_Transient::get_instance();
		if ( class_exists( 'WFACP_Core' ) ) {
			$data['aero_checkout_pages'] = $woofunnel_transient_obj->get_transient( 'wfacp_publish_posts', WFACP_SLUG );
		}


		return $data;
	}

	public function get_localization() {
		return [
			'data_saving'    => __( 'Data Saving...', 'woofunnels-carthopper-skip-cart' ),
			'delete_message' => __( ' Are you sure you want to delete this Product Rule?', 'woofunnels-carthopper-skip-cart' ),
		];
	}

	/**
	 * Remove all the notices in our dashboard pages as they might break the design.
	 */
	public function maybe_remove_all_notices_on_page() {

		remove_all_actions( 'admin_notices' );

	}

	public function footer() {
		include __DIR__ . '/templates/model.php';
	}
}


if ( class_exists( 'WFCH_Core' ) ) {
	WFCH_Core::register( 'admin', 'WFCH_Admin' );
}
