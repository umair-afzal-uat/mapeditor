<?php
$product_field  = WFACP_Common::get_product_field();
$advanced_field = WFACP_Common::get_advanced_fields();
$settings       = [
	'show_on_next_step' => [
		'single_step' => [
			'billing_email'      => 'true',
			'billing_first_name' => 'false',
			'billing_last_name'  => 'false',
			'shipping-address'   => 'true',
			'billing'            => 'true',
			'billing_phone'      => 'false'
		],
	],
];


$steps = [
	'single_step' => [
		'name'          => __( 'Step 1', 'woofunnels-aero-checkout' ),
		'slug'          => 'single_step',
		'friendly_name' => __( 'Single Step Checkout', 'woofunnels-aero-checkout' ),
		'active'        => 'yes',
	],
	'two_step'    => [
		'name'          => __( 'Step 2', 'woofunnels-aero-checkout' ),
		'slug'          => 'two_step',
		'friendly_name' => __( 'Two Step Checkout', 'woofunnels-aero-checkout' ),
		'active'        => 'yes',
	],
	'third_step'  => [
		'name'          => __( 'Step 3', 'woofunnels-aero-checkout' ),
		'slug'          => 'third_step',
		'friendly_name' => __( 'Three Step Checkout', 'woofunnels-aero-checkout' ),
		'active'        => 'no',
	],
];

if ( isset( $advanced_field['shipping_calculator']['label'] ) ) {
	$advanced_field['shipping_calculator']['label'] = __( 'Shipping Method', 'woofunnels-aero-checkout' );
}
$pageLayout = [
	'steps'                       => $steps,
	'fieldsets'                   => [
		'single_step' => array(
			array(
				'name'        => __( 'Your Information', 'woofunnels-aero-checkout' ),
				'class'       => '',
				'is_default'  => 'yes',
				'sub_heading' => '',
				'fields'      => array(
					[
						'label'        => __( 'Email', 'woocommerce' ),
						'required'     => 'true',
						'type'         => 'email',
						'class'        => [ 'form-row-wide' ],
						'validate'     => [ 'email' ],
						'autocomplete' => 'email username',
						'priority'     => '110',
						'id'           => 'billing_email',
						'field_type'   => 'billing',
						'placeholder'  => __( 'john.doe@example.com ', 'woofunnels-aero-checkout' ),
					],
					array(
						'label'        => __( 'First name', 'woocommerce' ),
						'required'     => 'true',
						'class'        => array(
							0 => 'form-row-first',
						),
						'autocomplete' => 'given-name',
						'priority'     => '10',
						'type'         => 'text',
						'id'           => 'billing_first_name',
						'field_type'   => 'billing',
						'placeholder'  => __( 'John', 'woofunnels-aero-checkout' ),

					),
					array(
						'label'        => __( 'Last name', 'woocommerce' ),
						'required'     => 'true',
						'class'        => array(
							0 => 'form-row-last',
						),
						'autocomplete' => 'family-name',
						'priority'     => '20',
						'type'         => 'text',
						'id'           => 'billing_last_name',
						'field_type'   => 'billing',
						'placeholder'  => __( 'Doe', 'woofunnels-aero-checkout' ),
					),

				),
			),
			array(
				'name'        => __( 'Your Shipping Address', 'woocommerce' ),
				'class'       => '',
				'sub_heading' => '',
				'fields'      => array(
					WFACP_Common::get_single_address_fields( 'shipping' ),
					WFACP_Common::get_single_address_fields(),
				),

			),


		),
		'two_step'    => [
			array(
				'name'        => __( 'Your Shipping Methods', 'woocommerce' ),
				'class'       => '',
				'sub_heading' => '',
				'fields'      => array(
					isset( $advanced_field['shipping_calculator'] ) ? $advanced_field['shipping_calculator'] : []
				),

			),
			[
				'name'        => __( 'Your Products', 'woofunnels-aero-checkout' ),
				'class'       => '',
				'sub_heading' => '',
				'html_fields' => [
					'product_switching' => 'true',
				],
				'fields'      => [
					$product_field['product_switching'],

				],
			],
			[
				'name'        => __( 'Your Order Summary', 'woofunnels-aero-checkout' ),
				'class'       => '',
				'sub_heading' => '',
				'html_fields' => [
					'order_summary' => 'true',
				],
				'fields'      => [
					$advanced_field['order_summary'],
				],
			],
		]
	],
	'product_settings'            => [
		'coupons'                             => '',
		'enable_coupon'                       => 'false',
		'disable_coupon'                      => 'false',
		'hide_quantity_switcher'              => 'false',
		'enable_delete_item'                  => 'false',
		'hide_product_image'                  => 'false',
		'is_hide_additional_information'      => 'true',
		'additional_information_title'        => WFACP_Common::get_default_additional_information_title(),
		'hide_quick_view'                     => 'false',
		'hide_you_save'                       => 'true',
		'hide_best_value'                     => 'false',
		'best_value_product'                  => '',
		'best_value_text'                     => 'Best Value',
		'best_value_position'                 => 'above',
		'enable_custom_name_in_order_summary' => 'false',
		'autocomplete_enable'                 => 'false',
		'autocomplete_google_key'             => '',
		'preferred_countries_enable'          => 'false',
		'preferred_countries'                 => '',
		'product_switcher_template'           => 'default',
	],
	'have_coupon_field'           => 'false',
	'have_billing_address'        => 'true',
	'have_shipping_address'       => 'true',
	'have_billing_address_index'  => '5',
	'have_shipping_address_index' => '4',
	'enabled_product_switching'   => 'yes',
	'have_shipping_method'        => 'true',
	'current_step'                => 'two_step',
];

return [ 'page_layout' => $pageLayout, 'page_settings' => $settings ];
