<?php 
class WCPFC_Cart
{
	function __construct()
	{
		
	}
	public function get_wcuf_identifier($product_data)
	{
		$additional_text = "";
		if(class_exists('WCUF_Cart') && isset($product_data[WCUF_Cart::$sold_as_individual_item_cart_key_name]))
		{
			global $wcuf_text_model;
			$identfier_prefix_text = $wcuf_text_model->get_cart_identifier_prefix();		
			$additional_text = " ".$identfier_prefix_text.$product_data[WCUF_Cart::$sold_as_individual_item_cart_key_name];
		}
		
		return $additional_text;
	}
}
?>