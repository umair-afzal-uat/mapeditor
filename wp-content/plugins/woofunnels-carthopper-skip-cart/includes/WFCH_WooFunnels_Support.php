<?php
defined( 'ABSPATH' ) || exit;

class WFCH_WooFunnels_Support {

	public static $_instance = null;
	/** Can't be change this further, as is used for license activation */
	public $full_name = '';
	public $is_license_needed = true;
	/**
	 * @var WooFunnels_License_check
	 */
	public $license_instance;
	protected $encoded_basename = '';

	public function __construct() {

		$this->encoded_basename = sha1( WFCH_PLUGIN_BASENAME );
		$this->full_name        = WFCH_FULL_NAME;
		$this->slug             = WFCH_FULL_NAME;

		add_action( 'admin_menu', array( $this, 'add_menus' ), 80.1 );
		add_filter( 'woofunnels_plugins_license_needed', array( $this, 'add_license_support' ), 10 );
		add_action( 'init', array( $this, 'init_licensing' ), 12 );
		add_action( 'woofunnels_licenses_submitted', array( $this, 'process_licensing_form' ) );
		add_action( 'woofunnels_deactivate_request', array( $this, 'maybe_process_deactivation' ) );

		if ( ! wp_next_scheduled( 'woofunnels_wfch_license_check' ) ) {
			wp_schedule_event( time(), 'daily', 'woofunnels_wfch_license_check' );
		}
		add_action( 'woofunnels_wfch_license_check', array( $this, 'license_check' ) );
		add_filter( 'woofunnels_default_reason_' . WFCH_PLUGIN_BASENAME, function () {
			return 1;
		} );
		add_filter( 'woofunnels_default_reason_default', function () {
			return 1;
		} );
	}

	/**
	 * @return null|WFCH_WooFunnels_Support
	 */
	public static function get_instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 * Adding WooCommerce sub-menu for global options
	 */
	public function add_menus() {
		if ( ! WooFunnels_dashboard::$is_core_menu ) {
			add_menu_page( __( 'WooFunnels', 'woofunnels' ), __( 'WooFunnels', 'woofunnels' ), 'manage_woocommerce', 'woofunnels', array( $this, 'woofunnels_page' ), '', 59 );
			add_submenu_page( 'woofunnels', __( 'Licenses', 'woofunnels' ), __( 'License', 'woofunnels' ), 'manage_woocommerce', 'woofunnels' );
			WooFunnels_dashboard::$is_core_menu = true;
		}
	}

	public function woofunnels_page() {
		if ( ! isset( $_GET['tab'] ) ) {
			WooFunnels_dashboard::$selected = 'licenses';
		}
		WooFunnels_dashboard::load_page();
	}

	/**
	 * License management helper function to create a slug that is friendly with edd
	 *
	 * @param type $name
	 *
	 * @return type
	 */
	public function slugify_module_name( $name ) {
		return preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $name ) ) );
	}

	public function add_license_support( $plugins ) {
		$status  = 'invalid';
		$renew   = 'Please Activate';
		$license = array(
			'key'     => '',
			'email'   => '',
			'expires' => '',
		);

		$plugins_in_database = WooFunnels_License_check::get_plugins();

		if ( is_array( $plugins_in_database ) && isset( $plugins_in_database[ $this->encoded_basename ] ) && count( $plugins_in_database[ $this->encoded_basename ] ) > 0 ) {
			$status  = 'active';
			$renew   = '';
			$license = array(
				'key'     => $plugins_in_database[ $this->encoded_basename ]['data_extra']['api_key'],
				'email'   => $plugins_in_database[ $this->encoded_basename ]['data_extra']['license_email'],
				'expires' => $plugins_in_database[ $this->encoded_basename ]['data_extra']['expires'],
			);
		}

		$plugins[ $this->encoded_basename ] = array(
			'plugin'            => $this->full_name,
			'product_version'   => WFCH_VERSION,
			'product_status'    => $status,
			'license_expiry'    => $renew,
			'product_file_path' => $this->encoded_basename,
			'existing_key'      => $license,
		);

		return $plugins;
	}

	public function woofunnels_slugify_module_name( $name ) {
		return preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $name ) ) );
	}

	public function init_licensing() {
		if ( class_exists( 'WooFunnels_License_check' ) && $this->is_license_needed ) {
			$this->license_instance = new WooFunnels_License_check( $this->encoded_basename );
			//          $this->license_check();

			$plugins = WooFunnels_License_check::get_plugins();
			if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
				$data = array(
					'plugin_slug' => WFCH_PLUGIN_BASENAME,
					'plugin_name' => WFCH_FULL_NAME,
					//	'email'       => $plugins[ $this->encoded_basename ]['data_extra']['license_email'],
					'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
					'product_id'  => $this->full_name,
					'version'     => WFCH_VERSION,
				);
				$this->license_instance->setup_data( $data );
				$this->license_instance->start_updater();
			}
		}

	}

	public function process_licensing_form( $posted_data ) {

		if ( isset( $posted_data['license_keys'][ $this->encoded_basename ] ) ) {
			$key = $posted_data['license_keys'][ $this->encoded_basename ]['key'];
			//	$email = $posted_data['license_keys'][ $this->encoded_basename ]['email'];
			$data = array(
				'plugin_slug' => WFCH_PLUGIN_BASENAME,
				'plugin_name' => WFCH_PLUGIN_BASENAME,
				//'email'       => $email,

				'license_key' => $key,
				'product_id'  => $this->full_name,
				'version'     => WFCH_VERSION,
			);
			$this->license_instance->setup_data( $data );
			$this->license_instance->activate_license();
		}
	}

	/**
	 * Validate is it is for email product deactivation
	 *
	 * @param type $posted_data
	 */
	public function maybe_process_deactivation( $posted_data ) {
		if ( isset( $posted_data['filepath'] ) && $posted_data['filepath'] == $this->encoded_basename ) {
			$plugins = WooFunnels_License_check::get_plugins();
			if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
				$data = array(
					'plugin_slug' => WFCH_PLUGIN_BASENAME,
					'plugin_name' => WFCH_PLUGIN_BASENAME,
					//	'email'       => $plugins[ $this->encoded_basename ]['data_extra']['license_email'],
					'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
					'product_id'  => $this->full_name,
					'version'     => WFCH_VERSION,
				);
				$this->license_instance->setup_data( $data );
				$this->license_instance->deactivate_license();
				wp_safe_redirect( 'admin.php?page=' . $posted_data['page'] . '&tab=' . $posted_data['tab'] );
			}
		}
	}

	public function license_check() {
		$plugins = WooFunnels_License_check::get_plugins();
		if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
			$data = array(
				'plugin_slug' => WFCH_PLUGIN_BASENAME,
				//	'email'       => $plugins[ $this->encoded_basename ]['data_extra']['license_email'],
				'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
				'product_id'  => $this->full_name,
				'version'     => WFCH_VERSION,
			);
			$this->license_instance->setup_data( $data );
			$this->license_instance->license_status();
		}
	}

	public function is_license_present() {
		$plugins = WooFunnels_License_check::get_plugins();

		if ( ! isset( $plugins[ $this->encoded_basename ] ) ) {
			return false;
		}

		return true;

	}

}

new WFCH_WooFunnels_Support();
