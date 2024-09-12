<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WFCH_Compatibilities
 * Loads all the compatibilities files we have to provide compatibility with each plugin
 */
class WFCH_Compatibilities {

	public static $plugin_compatibilities = array();

	public static function load_all_compatibilities() {
		// load all the WFCH_Compatibilities files automatically
		$compatibilities_folder = [ 'themes', 'gateways', 'plugins', 'others' ];
		foreach ( $compatibilities_folder as $folder ) {
			foreach ( glob( plugin_dir_path( WFCH_PLUGIN_FILE ) . 'compatibilities/' . $folder . '/*.php' ) as $_field_filename ) {
				require_once( $_field_filename );
			}
		}
	}

	public static function register( $object, $slug ) {
		self::$plugin_compatibilities[ $slug ] = $object;
	}

	public static function get_compatibility_class( $slug ) {
		return ( isset( self::$plugin_compatibilities[ $slug ] ) ) ? self::$plugin_compatibilities[ $slug ] : false;
	}

}

WFCH_Compatibilities::load_all_compatibilities();
