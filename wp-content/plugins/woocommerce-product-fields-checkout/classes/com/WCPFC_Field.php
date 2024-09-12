<?php 
class WCPFC_Field
{
	var $field_data = array();
	var $posted_field_data_cache;
	
	function __construct()
	{
		
	}
	public function delete_field_data()
	{
		delete_option('wcpfc_field_configuration_data');
		$this->field_data = null;
	}
	public function save_field_data($data_to_save)
	{
		update_option('wcpfc_field_configuration_data', $this->stripslashes_deep($data_to_save));
		$this->field_data = null;
	}
	public function get_field_data($use_id_as_array_index = false)
	{
		if(isset($this->field_data[$use_id_as_array_index]))
			return $this->field_data[$use_id_as_array_index];
		
		global $wcpfc_product_model;
		
		$data_from_db = get_option('wcpfc_field_configuration_data');
		$data_from_db = !is_array($data_from_db) ? array() : $data_from_db;
		$this->field_data[$use_id_as_array_index] = $data_from_db;
		foreach( $data_from_db as $data_index => $db_data)
		{
			if(is_array(wcpfc_get_value_if_set($db_data, array('options', 'product_id'))))
			{
				$products_ids = array();			
				foreach($db_data['options']['product_id'] as $index => $product_id)
					$products_ids[] = ["id" => $product_id , "name" =>  $wcpfc_product_model->get_product_name($product_id)];
					
				$data_from_db[$data_index]['options']['product_id'] = $products_ids;
			}
		
			if(is_array(wcpfc_get_value_if_set($db_data, array('options', 'category_id'))))
			{
				$category_ids = array();
				foreach($db_data['options']['category_id'] as $category_id) 
					$category_ids[] = ["id" => $category_id , "name" =>  $wcpfc_product_model->get_product_category_name($category_id)];
				
				$data_from_db[$data_index]['options']['category_id'] = $category_ids;
			}
		}
		if($use_id_as_array_index)
		{
			$new_result = [];
			foreach($data_from_db as $single_field)
			{
				$new_result[$single_field["id"]] = $single_field;
			}
			
			return $new_result;
		}
		
		
		return $data_from_db;
	}
	public function get_js_field_data_per_cart_product()
	{
		global $wcpfc_product_model, $wcpfc_wpml_model, $wcpfc_cart_model;
		$field_data = $this->get_field_data();
		$cart_items = WC()->cart->get_cart();
		$js_data = array('one_time_field' => array());
		
		foreach($cart_items as $cart_item)
		{
			$not_translated_product_id = $cart_item['data']->get_id();
			$product_id = $wcpfc_wpml_model->get_main_language_id($cart_item['data']->get_id());
			$product =  wc_get_product( $product_id );
			foreach($field_data as $single_field)
			{
				if($wcpfc_product_model->field_applies_to_product($single_field, $product))
				{
					if($single_field['options']['display_policy'] == 'once')
					{
						$js_data['one_time_field'][$single_field['id']] = $single_field;
					}
					else
					{
						
						if(!isset($js_data[$cart_item['key']]))
							$js_data[$cart_item['key']] = array('cart_key'=>  $cart_item['key'],
															'cart_item_name' => $wcpfc_product_model->get_product_name($not_translated_product_id, false).$wcpfc_cart_model->get_wcuf_identifier($cart_item),
															'cart_quantity' => $cart_item['quantity'],
															'product_id' => $cart_item['product_id'],
															'variation_id' => $cart_item['variation_id'],
															 'per_cart_quantity' => array('field_data' => array()),
															 'per_item' => array('field_data' => array()));
						
							if( $single_field['options']['display_policy'] == 'each_product_quantity')
								$js_data[$cart_item['key']]['per_cart_quantity']['field_data'][] = $single_field;
							else
								$js_data[$cart_item['key']]['per_item']['field_data'][] = $single_field;
							
					}
				}	
			}				
		}
		
		return $js_data;
	}
	
	private function stripslashes_deep($value)
	{
		$value = is_array($value) ?
					array_map('stripslashes_deep', $value) :
					stripslashes($value);

		return $value;
	}
	function retrieve_fields_data_from_posted_data()
	{
		if(isset($this->posted_field_data_cache))
			return $this->posted_field_data_cache;
		
		global $wcpfc_field_model;
		
		$field_data =  $this->get_field_data(true);
		
		$detected_fields = [];
		
		foreach($_POST as $index => $posted_field)
		{
			$indexes = explode("_",$index); //order_6xvhv24a5l_1-0-0 ---> [0]: order, [1]: 6xvhv24a5l, [2]: 1-0-0, [3](options): example "checkbox"
			if(count($indexes) < 2)
				continue; 
			
			//in case of checkbox, all data are sent via input[hidden] field. the [name]_checkbox field will be lately tested (if present or not) to know if it has been checked or not
			if(isset($indexes[3]) && $indexes[3] == "checkbox") 
				continue;
			
			if(!isset($field_data[$indexes[1]])) //field_unique_id
				continue;
			
			
			$current_field_metadata = $field_data[$indexes[1]];
			
			if(isset($indexes[1]) && $indexes[0] == 'order')
			{
				if(!isset($detected_fields[$indexes[1]]))
					$detected_fields[$indexes[1]] = [];
																			  //order_mnzo76qx7qh_1-4-0_checkbox
				$detected_fields[$indexes[1]][] = ["data" => $posted_field, 
													"metadata" => $current_field_metadata ,
													"checkbox_has_been_checked" => $current_field_metadata["options"]["type"] == 'checkbox' && isset($_POST[$index."_checkbox"])];
			}
		}
		
		$this->posted_field_data_cache = $detected_fields;
		return $detected_fields;
	}
	//used for select field
	function get_label_from_values($values, $current_field_metadata, $lang)
	{
		
		$labels = array();
		if(!isset($values) || !is_array($values))
			return $labels;
		
		foreach($values as $selected_value => $value)
		 foreach($current_field_metadata['options']["value_label"] as $value_label)
			{
				if($value_label["value"] == $value)
					$labels[$value] = $value_label["label"][$lang];
			}
			
		return $labels;
	}
	public function get_field_readable_value($data)
	{
		global $wcpfc_datetime_model, $wcpfc_country_model;
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
		$result = wcpfc_get_value_if_set($data, array('value','value'), "");
		$type = $data['value']['type'];
		
		switch($type)
		{
			case 'date': 
			case 'time':
				
					$result = $result !=  "" ? $wcpfc_datetime_model->get_formatted_datetime($result, $data['value']["format"]) : esc_html__('-','woocommerce-product-fields-checkout');
			break;
			case 'select': 
					
					$result = !empty($result ) ? implode(", " , $result ) : esc_html__('-','woocommerce-product-fields-checkout');
			break;
			case 'checkbox': 
					$result = $data['value']['is_checked'] == 'yes' ? esc_html__('Yes','woocommerce-product-fields-checkout') : esc_html__('No','woocommerce-product-fields-checkout') ;
					$result = $data['value']['label']." ".$result;
				
			break;
			case 'country_state': 
					$country_code = $result;
					$state = wcpfc_get_value_if_set($data, array('value','state'), "");
					$result = 	WC()->countries->countries[$country_code ];
					$state = $wcpfc_country_model->state_code_to_name($state, $country_code);
					$result = $state != "" ? $result.", ".$state : $result;
			break;
		}
		
		return $result;
	}
	public function get_checkout_fields($type = 'billing')
	{	
		global $woocommerce;
		$result = array();
		$fields = $woocommerce->countries->get_address_fields( "", $type.'_' );
		$prefix = $type == 'billing' ? esc_html__('Billing - ','woocommerce-product-fields-checkout') : esc_html__('Shipping - ','woocommerce-product-fields-checkout');
		
		foreach($fields as $field_id => $field)
		{
			$field_type = wcpfc_get_value_if_set($field, 'type', "");
			$label = wcpfc_get_value_if_set($field, 'label', $field_id);
		
			
			if($field_id == 'billing_state' || $field_id == 'shipping_state')
				continue;
			
			$label = $field_id == 'billing_country' || $field_id == 'shipping_country' ?  esc_html__('Country & State/Province ','woocommerce-product-fields-checkout') : $label;
			
			
			$result[$field_id] = array('name' =>  $prefix.$label, 
										'type' => $field_type == 'country' ? 'country_state' : 'text',
										'id' => $field_id,  //used to identify the html id element
										'unique_id' => $field_id, 
										'is_multiple_value' => false, 
										'display_policy' => $type, 
										'position' => 'checkout_native_field' );
		}
		return $result;
	}
}
?>