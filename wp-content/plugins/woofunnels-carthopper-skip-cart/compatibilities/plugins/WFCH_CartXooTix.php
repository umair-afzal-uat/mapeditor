<?php
defined( 'ABSPATH' ) || exit;

class WFCH_CartXooTix extends WFCH_CMP_Singleton {
	private static $instance = null;

	protected function __construct() {
		parent::__construct();

	}

	public static function getInstance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();
		}

		return self::$instance;
	}

	public function setup_theme() {

		if ( true == $this->is_active() ) {
			$instance = WFCH_Core()->public;
			add_filter( 'woocommerce_add_to_cart_fragments', [ $instance, 'add_redirect_url' ] );
			add_action( 'wp_footer', [ $instance, 'add_snippet_html' ] );
			add_filter( 'wfch_enable_ajax_check_rules', function () {
				return true;
			} );
		}
	}

	protected function is_active() {

		if ( class_exists( 'xoo_wsc' ) ) {
			$gl_options = get_option( 'xoo-wsc-gl-options' );
			$ajax_atc   = isset( $gl_options['sc-ajax-atc'] ) ? $gl_options['sc-ajax-atc'] : 0;

			return wc_string_to_bool( $ajax_atc );
		}

		return parent::is_active();
	}

	public function footer() {
		if ( true !== $this->is_active() ) {
			return;
		}
		if ( is_singular( 'product' ) ) {
			?>
            <script>
                window.addEventListener('load', function () {
                    wfch.hooks.addFilter('wfch_reload_page_added_to_cart', function () {
                        return true;
                    });
                });
            </script>
			<?php
		}
	}

	protected function action() {
		remove_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
		add_action( 'after_setup_theme', [ $this, 'setup_theme' ], 20 );
	}
}

WFCH_Compatibilities::register( WFCH_CartXooTix::getInstance(), 'cart_xootix' );
