<?php
/**
 * Plugin Name: CartHopper: WooCommerce Skip Cart
 * Plugin URI: https://buildwoofunnels.com
 * Description: Set up global skip cart and create cart based rules to send user to specific checkout page.
 * Version: 1.0.0
 * Author: WooFunnels
 * Author URI: https://buildwoofunnels.com
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: woofunnels-order-bump
 *
 * Requires at least: 4.9
 * Tested up to: 5.5
 * WC requires at least: 3.0
 * WC tested up to: 4.3.3
 * WooFunnels: true
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OrderBumps: WooCommerce Checkout Offers. If not, see <http://www.gnu.org/licenses/>.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WFCH_Core {
	protected static $_instance = null;

	private static $_registered_entity = array(
		'active'   => array(),
		'inactive' => array(),
	);
	public $is_dependency_exists = true;


	protected function __construct() {
		$this->define();
		$this->do_dependency_check();
		if ( true === $this->is_dependency_exists ) {
			$this->load_core_classes();
			$this->load_commons();
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

	private function define() {
		define( 'WFCH_VERSION', '1.0.0' );
		define( 'WFCH_MIN_WP_VERSION', '4.9' );
		define( 'WFCH_MIN_WC_VERSION', '3.3' );
		define( 'WFCH_SLUG', 'wfch' );
		define( 'WFCH_TEXTDOMAIN', 'woofunnels-carthopper-skip-cart' );
		define( 'WFCH_FULL_NAME', __( 'CartHopper: WooCommerce Skip Cart', 'woofunnels-carthopper-skip-cart' ) );
		define( 'WFCH_PLUGIN_FILE', __FILE__ );
		define( 'WFCH_PLUGIN_DIR', __DIR__ );
		define( 'WFCH_PLUGIN_URL', untrailingslashit( plugin_dir_url( WFCH_PLUGIN_FILE ) ) );
		define( 'WFCH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'WFCH_VERSION_DEV', time() );
	}

	private function do_dependency_check() {
		include_once WFCH_PLUGIN_DIR . '/woo-includes/woo-functions.php';
		if ( ! wfch_is_woocommerce_active() ) {
			add_action( 'admin_notices', array( $this, 'wc_not_installed_notice' ) );
			$this->is_dependency_exists = false;
		}
	}

	private function load_core_classes() {
		/** Setting Up WooFunnels Core */
		require_once( 'start.php' );
	}

	private function load_commons() {

		require WFCH_PLUGIN_DIR . '/includes/WFCH_Tools.php';
		require WFCH_PLUGIN_DIR . '/includes/WFCH_Common.php';
		require WFCH_PLUGIN_DIR . '/includes/WFCH_WooFunnels_Support.php';
		require WFCH_PLUGIN_DIR . '/includes/WFCH_AJAX_Controller.php';
		WFCH_Common::init();
		$this->load_hooks();
	}

	private function load_hooks() {
		/**
		 * Initialize Localization
		 */
		add_action( 'plugins_loaded', array( $this, 'load_classes' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'register_classes' ), 2 );

	}

	public static function register( $short_name, $class, $overrides = null ) {
		//Ignore classes that have been marked as inactive
		if ( in_array( $class, self::$_registered_entity['inactive'] ) ) {
			return;
		}
		//Mark classes as active. Override existing active classes if they are supposed to be overridden
		$index = array_search( $overrides, self::$_registered_entity['active'] );
		if ( false !== $index ) {
			self::$_registered_entity['active'][ $index ] = $class;
		} else {
			self::$_registered_entity['active'][ $short_name ] = $class;
		}

		//Mark overridden classes as inactive.
		if ( ! empty( $overrides ) ) {
			self::$_registered_entity['inactive'][] = $overrides;
		}
	}

	public function load_classes() {

		global $woocommerce;
		global $wp_version;
		if ( ! version_compare( $wp_version, WFCH_MIN_WP_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'wp_version_check_notice' ) );

			return false;
		}
		if ( ! version_compare( $woocommerce->version, WFCH_MIN_WC_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'wc_version_check_notice' ) );

			return false;
		}

		if ( is_admin() ) {
			include __DIR__ . '/admin/WFCH_Admin.php';
		}
		require WFCH_PLUGIN_DIR . '/public/WFCH_Public.php';

	}

	public function register_classes() {
		$load_classes = self::get_registered_class();
		if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
			foreach ( $load_classes as $access_key => $class ) {
				$this->$access_key = $class::get_instance();
			}

			do_action( 'wfch_loaded' );
		}
	}

	public static function get_registered_class() {
		return self::$_registered_entity['active'];
	}

	public function wc_version_check_notice() {
		?>
        <div class="error">
            <p>
				<?php
				/* translators: %1$s: Min required woocommerce version */
				printf( __( '<strong> Attention: </strong>CartHopper requires WooCommerce version %1$s or greater. Kindly update the WooCommerce plugin.', 'woofunnels-carthopper-skip-cart' ), WFCH_MIN_WC_VERSION );
				?>
            </p>
        </div>
		<?php
	}

	public function wp_version_check_notice() {
		?>
        <div class="error">
            <p>
				<?php
				/* translators: %1$s: Min required woocommerce version */
				printf( __( '<strong> Attention: </strong>CartHopper requires WordPress version %1$s or greater. Kindly update the WordPress.', 'woofunnels-carthopper-skip-cart' ), WFCH_MIN_WP_VERSION );
				?>
            </p>
        </div>
		<?php
	}


	public function wc_not_installed_notice() {
		?>
        <div class="error">
            <p>
				<?php
				echo __( '<strong> Attention: </strong>WooCommerce is not installed or activated. CartHopper is a WooCommerce Extension and would only work if WooCommerce is activated. Please install the WooCommerce Plugin first.', 'woofunnels-carthopper-skip-cart' );
				?>
            </p>
        </div>
		<?php
	}

	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * To avoid cloning of current class
	 */
	protected function __clone() {
	}
}

if ( ! function_exists( 'WFCH_Core' ) ) {

	/**
	 * Global Common function to load all the classes
	 * @return WFOB_Core
	 */
	function WFCH_Core() {  //@codingStandardsIgnoreLine
		return WFCH_Core::get_instance();
	}
}


$GLOBALS['WFCH_Core'] = WFCH_Core();