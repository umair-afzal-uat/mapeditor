<?php
defined( 'ABSPATH' ) || exit;

class WFCH_OcceanWP extends WFCH_CMP_Singleton {

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
		if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
			return true;
		}

		return parent::is_active();
	}

	public function footer() {
		if ( true !== $this->is_active() ) {
			return;
		}
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
					<?php
					if ( is_singular( 'product' ) ) {
					?>
                    wfch.hooks.addFilter('wfch_reload_wc_fragments_refreshed', function () {
                        return true;
                    });
					<?php
					}
					if ( apply_filters( 'wfch_enable_ajax_add_to_cart_on_archive', false ) ) {
					?>
                    wfch.hooks.addFilter('wfch_reload_page_added_to_cart', function () {
                        return true;
                    });
					<?php
					}
					?>
                })(jQuery);
            });
        </script>
		<?php
	}
}

WFCH_Compatibilities::register( WFCH_OcceanWP::getInstance(), 'occeanwp' );
