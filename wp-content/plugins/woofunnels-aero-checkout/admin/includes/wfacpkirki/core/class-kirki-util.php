<?php
/**
 * A utility class for WFACPKirki.
 *
 * @package     WFACPKirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       3.0.9
 */

/**
 * Utility class.
 */
class WFACPKirki_Util {

	/**
	 * Constructor.
	 *
	 * @since 3.0.9
	 * @access public
	 */
	public function __construct() {

		add_filter( 'http_request_args', array( $this, 'http_request' ), 10, 2 );
	}

	/**
	 * Determine if WFACPKirki is installed as a plugin.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return bool
	 */
	public static function is_plugin() {

		$is_plugin = false;
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Get all plugins.
		$plugins = get_plugins();
		$_plugin = '';
		foreach ( $plugins as $plugin => $args ) {
			if ( ! $is_plugin && isset( $args['Name'] ) && ( 'WFACPKirki' === $args['Name'] || 'WFACPKirki Toolkit' === $args['Name'] ) ) {
				$is_plugin = true;
				$_plugin   = $plugin;
			}
		}

		// No need to proceed any further if WFACPKirki wasn't found in the list of plugins.
		if ( ! $is_plugin ) {
			return false;
		}

		// Make sure the is_plugins_loaded function is loaded.
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Extra logic in case the plugin is installed but not activated.
		if ( $_plugin && ! is_plugin_active( $_plugin ) ) {
			return false;
		}
		return $is_plugin;
	}

	/**
	 * Build the variables.
	 *
	 * @static
	 * @access public
	 * @since 3.0.9
	 * @return array Formatted as array( 'variable-name' => value ).
	 */
	public static function get_variables() {

		$variables = array();

		// Loop through all fields.
		foreach ( WFACPKirki::$fields as $field ) {

			// Check if we have variables for this field.
			if ( isset( $field['variables'] ) && $field['variables'] && ! empty( $field['variables'] ) ) {

				// Loop through the array of variables.
				foreach ( $field['variables'] as $field_variable ) {

					// Is the variable ['name'] defined? If yes, then we can proceed.
					if ( isset( $field_variable['name'] ) ) {

						// Sanitize the variable name.
						$variable_name = esc_attr( $field_variable['name'] );

						// Do we have a callback function defined? If not then set $variable_callback to false.
						$variable_callback = ( isset( $field_variable['callback'] ) && is_callable( $field_variable['callback'] ) ) ? $field_variable['callback'] : false;

						// If we have a variable_callback defined then get the value of the option
						// and run it through the callback function.
						// If no callback is defined (false) then just get the value.
						$variables[ $variable_name ] = WFACPKirki_Values::get_value( $field['settings'] );
						if ( $variable_callback ) {
							$variables[ $variable_name ] = call_user_func( $field_variable['callback'], WFACPKirki_Values::get_value( $field['settings'] ) );
						}
					}
				}
			}
		}

		// Pass the variables through a filter ('wfacpkirki_variable') and return the array of variables.
		return apply_filters( 'wfacpkirki_variable', $variables );

	}

	/**
	 * HTTP Request injection.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param array  $request The request params.
	 * @param string $url     The request URL.
	 * @return array
	 */
	public function http_request( $request = array(), $url = '' ) {
		// Early exit if installed as a plugin or not a request to wordpress.org,
		// or finally if we don't have everything we need.
		if (
			self::is_plugin() ||
			false === strpos( $url, 'wordpress.org' ) || (
				! isset( $request['body'] ) ||
				! isset( $request['body']['plugins'] ) ||
				! isset( $request['body']['translations'] ) ||
				! isset( $request['body']['locale'] ) ||
				! isset( $request['body']['all'] )
			)
		) {
			return $request;
		}

		$plugins = json_decode( $request['body']['plugins'], true );
		if ( ! isset( $plugins['plugins'] ) ) {
			return $request;
		}
		$exists = false;
		foreach ( $plugins['plugins'] as $plugin ) {
			if ( isset( $plugin['Name'] ) && 'WFACPKirki Toolkit' === $plugin['Name'] ) {
				$exists = true;
			}
		}
		// Inject data.
		if ( ! $exists && defined( 'WFACP_KIRKI_PLUGIN_FILE' ) ) {
			$plugins['plugins']['wfacpkirki/wfacpkirki.php'] = get_plugin_data( WFACP_KIRKI_PLUGIN_FILE );
		}
		$request['body']['plugins'] = wp_json_encode( $plugins );
		return $request;
	}

	/**
	 * Returns the $wp_version.
	 *
	 * @static
	 * @access public
	 * @since 3.0.12
	 * @param string  $context      Use 'minor' or 'major'.
	 * @param boolean $only_numeric Set to true if you want to skip the alpha/beta etc parts.
	 * @return int|float|string     Returns integer when getting the 'major' version.
	 *                              Returns float when getting the 'minor' version with $only_numeric set to true.
	 *                              Returns string when getting the 'minor' version with $only_numeric set to false.
	 */
	public static function get_wp_version( $context = 'minor', $only_numeric = true ) {
		global $wp_version;

		// We only need the major version.
		if ( 'major' === $context ) {
			$version_parts = explode( '.', $wp_version );
			return ( $only_numeric ) ? absint( $version_parts[0] ) : $version_parts[0];
		}

		// If we got this far, we want the full monty.
		if ( $only_numeric ) {
			// Get the numeric part of the version without any beta, alpha etc parts.
			if ( false !== strpos( $wp_version, '-' ) ) {
				// We're on a dev version.
				$version_parts = explode( '-', $wp_version );
				return floatval( $version_parts[0] );
			}
			return floatval( $wp_version );
		}
		return $wp_version;
	}
}
