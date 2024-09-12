<?php 
class WCPFC_PaymentMethod
{
	function __construct()
	{
		
	}
	
	public function get_payment_methods()
	{
		$gateways = new WC_Payment_Gateways();
		$result = array();
		$prefix = esc_html__('Payment - ','woocommerce-product-fields-checkout');
		foreach($gateways->payment_gateways( ) as $gateway_code => $gateway)
		{
			$payment_methods[$gateway_code] = $gateway->title;
			$result["payment_method_".$gateway_code] = array('name' =>  $prefix.$gateway->title, 
										'type' => 'payment_method',
										'id' => "payment_method_".$gateway_code, //used to identify the html id element
										'unique_id' => "payment_method_".$gateway_code, 
										'is_multiple_value' => false, 
										'display_policy' => 'payment_method', 
										'position' => 'checkout_native_field' );
		}
		return $result;
	}
}
?>