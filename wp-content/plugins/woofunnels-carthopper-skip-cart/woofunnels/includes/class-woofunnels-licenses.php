<?php

/**
 * Plugin licenses data class / we do not handle license activation and deactivation at this class
 *
 * @author woofunnels
 * @package WooFunnels
 */
class WooFunnels_Licenses {

	public $plugins_list;
	protected static $instance;

	public function __construct() {
		//calling appropriate hooks by identifying the request
		$this->maybe_submit();

		$this->maybe_deactivate();
		add_action( 'admin_notices', array( $this, 'maybe_show_invalid_license_error' ) );
	}

	public function get_plugins_list() {
		$this->plugins_list = apply_filters( 'woofunnels_plugins_license_needed', array() );
	}

	public function get_data() {
		if ( ! is_null( $this->plugins_list ) ) {
			return $this->plugins_list;
		}
		$this->get_plugins_list();

		return $this->plugins_list;
	}

	/**
	 * Pass to submission
	 */
	public function maybe_submit() {
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'woofunnels_activate-products' ) {
			do_action( 'woofunnels_licenses_submitted', $_POST );
		}
	}

	/**
	 * Pass to deactivate hook
	 */
	public function maybe_deactivate() {
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'woofunnels_deactivate-product' ) {
			do_action( 'woofunnels_deactivate_request', $_GET );
		}
	}

	/**
	 * Creates and instance of the class
	 * @return WooFunnels_licenses
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function maybe_show_invalid_license_error() {
		$get_plugins_data = $this->get_data();

		if ( ! is_array( $get_plugins_data ) ) {
			return;
		}


		if ( empty( $get_plugins_data ) ) {
			return;
		}
		$plugins_need_license = [];
		foreach ( $get_plugins_data as $plugin_data ) {

			if ( 'active' !== $plugin_data['product_status'] ) {
				array_push( $plugins_need_license, $plugin_data['plugin'] );
			}
		}

		if ( ! empty( $plugins_need_license ) ) {
			$this->show_invalid_license_notice( $plugins_need_license );
		}
	}

	public function show_invalid_license_notice( $plugins ) {
		?>
		<div class="error">
			<p>
				<?php
				echo sprintf(__( '<strong>Invalid License Key: </strong> You are <i>not receiving</i> Latest Updates, New Features, Security Updates &amp; Bug Fixes for <strong>%s</strong>. <a href="%s">Click Here To Fix This</a>.', 'buildwoofunnels' ),implode(', ',$plugins), admin_url('admin.php?page=woofunnels'));
				?>
			</p>
		</div>
		<?php
	}

	public function get_secret_license_key($key){
		$last_six = substr($key, -6);
		$initial_string = str_replace($last_six,'',$key);
		$initial_string_length = strlen($initial_string);
		$final_string = str_repeat('x', $initial_string_length).$last_six;
		return $final_string;
    }
}
