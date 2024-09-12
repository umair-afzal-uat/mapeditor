<?php
/**
 * Override field methods
 *
 * @package     WFACPKirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       2.3.2
 */

/**
 * Field overrides.
 */
class WFACPKirki_Field_Sortable extends WFACPKirki_Field {

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'wfacpkirki-sortable';

	}

	/**
	 * Sets the $sanitize_callback.
	 *
	 * @access protected
	 */
	protected function set_sanitize_callback() {

		$this->sanitize_callback = array( $this, 'sanitize' );

	}

	/**
	 * Sanitizes sortable values.
	 *
	 * @access public
	 * @param array $value The checkbox value.
	 * @return array
	 */
	public function sanitize( $value = array() ) {

		if ( is_string( $value ) || is_numeric( $value ) ) {
			return array(
				esc_attr( $value ),
			);
		}
		$sanitized_value = array();
		foreach ( $value as $sub_value ) {
			if ( isset( $this->choices[ $sub_value ] ) ) {
				$sanitized_value[] = esc_attr( $sub_value );
			}
		}
		return $sanitized_value;

	}
}
