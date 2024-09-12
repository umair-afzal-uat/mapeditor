<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/15/19
 * Time: 3:27 PM
 */

abstract class WFCH_Tools {


	public static function pr( $arr ) {
		echo '<br /><pre>';
		print_r( $arr );
		echo '</pre><br />';
	}

	public static function dump( $arr ) {
		echo '<pre>';
		var_dump( $arr );
		echo '</pre>';
	}

	public static function export( $arr ) {
		echo '<pre>';
		var_export( $arr );
		echo '</pre>';
	}

	public static function get_class_path( $class = 'WFCH_Core' ) {
		$reflector = new ReflectionClass( $class );
		$fn        = $reflector->getFileName();

		return dirname( $fn );
	}

	public static function remove_actions( $hook, $cls, $function = '' ) {

		global $wp_filter;

		if ( class_exists( $cls ) && isset( $wp_filter[ $hook ] ) && ( $wp_filter[ $hook ] instanceof WP_Hook ) ) {

			$hooks = $wp_filter[ $hook ]->callbacks;
			foreach ( $hooks as $priority => $refrence ) {
				if ( is_array( $refrence ) && count( $refrence ) > 0 ) {
					foreach ( $refrence as $index => $calls ) {
						if ( isset( $calls['function'] ) && is_array( $calls['function'] ) && count( $calls['function'] ) > 0 ) {
							if ( is_object( $calls['function'][0] ) ) {
								$cls_name = get_class( $calls['function'][0] );
								if ( $cls_name == $cls && $calls['function'][1] == $function ) {
									unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $index ] );
								}
							}
						}
					}
				}
			}
		}

	}
}