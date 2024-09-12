<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WFACP_Compatibility_With_Theme_Nitro {

	public function __construct() {
		/* checkout page */
		add_filter( 'wr_nitro_theme_options_definition', [ $this, 'remove_panels' ] );
	}

	public function remove_panels( $theme_options ) {
		if ( WFACP_Common::is_customizer() ) {
			return [];
		}

		return $theme_options;
	}

}

if ( ! class_exists( 'WR_Nitro' ) ) {
	return;
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Nitro(), 'nitro' );
