<?php

/**
 * Order WooCommerce Sendinblue Newsletter Subscription
 * Author: Sendinblue
 *  Author URI: https://www.sendinblue.com/?r=wporg
 * Class WFACP_Compatibility_WC_SendinBlue
 */
class WFACP_Compatibility_WC_SendinBlue {

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

	}

	private function is_enable() {
		return class_exists( 'WC_Sendinblue_Integration' );
	}

	public function add_field( $fields ) {

		if ( $this->is_enable() ) {
			$fields['ws_opt_in'] = [
				'label'          => __( 'Sendinblue', 'woofunnels-aero-checkout' ),
				'data_label'     => __( 'Sendinblue', 'woofunnels-aero-checkout' ),
				'type'           => 'checkbox',
				'is_wfacp_field' => true,
				'id'             => 'ws_opt_in',
				'field_type'     => 'advanced',
				'cssready'       => [ 'wfacp-col-full' ],
				'class'          => [ 'wfacp-form-control-wrapper', 'wfacp-col-full', 'wfacp_checkbox_field', 'sendinblue' ],
				'input_class'    => [ 'wfacp-form-control' ],
			];

		}

		return $fields;
	}


	public function add_default_wfacp_styling( $args, $key ) {

		if ( $this->is_enable() && ! empty( $key ) && $key == 'ws_opt_in' ) {
			$this->customizations = get_option( 'wc_sendinblue_settings', array() );

			if ( isset( $this->customizations['ws_opt_field_label'] ) && ! empty( $this->customizations['ws_opt_field_label'] ) ) {
				$args['label'] = esc_attr( $this->customizations['ws_opt_field_label'] );
			}

		}

		return $args;
	}


}


if ( ! class_exists( 'WC_Sendinblue_Integration' ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_SendinBlue(), 'wc-sendin-blue' );

