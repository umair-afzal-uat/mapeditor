<?php
class WCPFC_Checkout
{
	var $jsx_handlers = array();
	var $order_meta_already_saved = array();
	function __construct()
	{
		add_filter( 'woocommerce_after_checkout_billing_form' , array(&$this, 'add_product_billing_fields_meta') );
		add_filter( 'woocommerce_after_checkout_shipping_form' , array(&$this, 'add_product_shipping_fields_meta') );
		add_filter( 'script_loader_tag', array($this, 'add_jsx_script_loading'), 10, 3 );
		//Validation before checkout is completed
		add_action('woocommerce_checkout_process', array( &$this, 'validate_fields_before_checkout_is_completed' )); 
		//After checkout
		add_action('woocommerce_new_order_item', array( &$this, 'add_field_meta_to_order_item_meta' ),10,3);	
	}
	public function add_jsx_script_loading( $tag, $handle, $src)
	{
		if ( in_array( $handle, $this->jsx_handlers) ) 
		{
			$tag = strpos($tag, "type='text/javascript'") !== false ? str_replace( "<script type='text/javascript'", "<script type='text/babel'", $tag ) : str_replace( "<script", "<script type='text/babel' ", $tag );
	  }

	  return $tag;
	}
	function add_product_fields($fields )
	{
		wcpfc_var_dump($fields );
		return $fields ;
	}
	function add_product_billing_fields_meta()
	{
		global $wcpfc_field_model, $wcpfc_wpml_model,$wcpfc_country_model, $wcpfc_datetime_model, $wcpfc_payment_method_model;
		//1
		wp_register_script( 'wcpfc-checkout-page', WCPFC_PLUGIN_PATH.'/js/frontend/checkout-page.js', array('jquery'));	
		wp_register_script( 'wcpfc-country-state', WCPFC_PLUGIN_PATH.'/js/frontend/field-country-state.js', array('jquery'));	
		wp_register_script( 'wcpfc-field-renderer', WCPFC_PLUGIN_PATH.'/js/frontend/field-renderer.js', array('jquery'));	
		wp_register_script( 'wcpfc-field-value-comparator', WCPFC_PLUGIN_PATH.'/js/frontend/field-value-comparator.js', array('jquery'));	
		wp_register_script( 'wcpfc-error-manager', WCPFC_PLUGIN_PATH.'/js/com/error-manager.js', array('jquery'));	
		
		//2
		$js_field_data = $wcpfc_field_model->get_js_field_data_per_cart_product();
		$field_data = $wcpfc_field_model->get_field_data();
		$js_conf_data = array('curr_lang' => $wcpfc_wpml_model->get_current_locale(), 
							  'all_countries' => $wcpfc_country_model->get_countries('all'), 
							  'allowed_countries' => $wcpfc_country_model->get_countries('allowed'), 
							  'shipping_countries' => $wcpfc_country_model->get_countries('shipping_countries'),
							  'current_time' => $wcpfc_datetime_model->current_time(),
							  'current_date' => $wcpfc_datetime_model->current_date(),
							  'ajaxurl' => admin_url( 'admin-ajax.php' ),
							  'loader_path' => WCPFC_PLUGIN_PATH.'/img/horizontal-loader.gif',
							  'field_data' => $field_data,
							  'billing_fields' => $wcpfc_field_model->get_checkout_fields(),
							  'shipping_fields' => $wcpfc_field_model->get_checkout_fields('shipping'),
							  'payment_methods' => $wcpfc_payment_method_model->get_payment_methods(),
							  );
		wp_localize_script( 'wcpfc-checkout-page', 'wcpfc_field_data', $js_field_data );
		wp_localize_script( 'wcpfc-checkout-page', 'wcpfc_conf_data', $js_conf_data );
		wp_localize_script( 'wcpfc-country-state', 'wcpfc_field_data', $js_field_data );
		wp_localize_script( 'wcpfc-country-state', 'wcpfc_conf_data', $js_conf_data );
		
		//3
		wp_enqueue_script( 'babel', WCPFC_PLUGIN_PATH.'/js/vendor/babel/babel.min.js', array('jquery'));
		if (defined('WP_DEBUG') && true === WP_DEBUG)
		{
			wp_enqueue_script( 'react', WCPFC_PLUGIN_PATH.'/js/vendor/react/react.development.js', array('jquery'));
			wp_enqueue_script( 'react-dom', WCPFC_PLUGIN_PATH.'/js/vendor/react/react-dom.development.js', array('jquery'));
		}
		else 
		{
			wp_enqueue_script( 'react', WCPFC_PLUGIN_PATH.'/js/vendor/react/react.production.min.js', array('jquery'));
			wp_enqueue_script( 'react-dom', WCPFC_PLUGIN_PATH.'/js/vendor/react/react-dom.production.min.js', array('jquery'));
		}
		wp_enqueue_script( 'wcpfc-checkout-page' );
		wp_enqueue_script( 'wcpfc-error-manager' );
		wp_enqueue_script( 'wcpfc-country-state' );
		wp_enqueue_script( 'wcpfc-field-renderer' );
		wp_enqueue_script( 'wcpfc-field-value-comparator' );
		wp_enqueue_script( 'picker', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/picker.js', array('jquery'));
		wp_enqueue_script( 'picker-date', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/picker.date.js', array('jquery'));
		wp_enqueue_script( 'picker-time', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/picker.time.js', array('jquery'));
		wp_enqueue_script( 'picker-legacy', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/legacy.js', array('jquery'));
		//Load picker localization
		if(wcpfc_url_exists(WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/translations/'.$wcpfc_wpml_model->get_current_language().'.js'))
			wp_enqueue_script('picker-localization', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/translations//'.$wcpfc_wpml_model->get_current_language().'.js');
		wp_enqueue_script( 'moment', WCPFC_PLUGIN_PATH.'/js/vendor/moment/moment.min.js', array('jquery'));
		
		//4
		$this->jsx_handlers[] = 'wcpfc-checkout-page';
		$this->jsx_handlers[] = 'wcpfc-error-manager';
		$this->jsx_handlers[] = 'wcpfc-country-state';
		$this->jsx_handlers[] = 'wcpfc-field-renderer';
		
		wp_enqueue_style( 'wcpfc-checkout-page', WCPFC_PLUGIN_PATH.'/css/frontend-checkout-page.css');
		wp_enqueue_style( 'admin-datetime-picker', WCPFC_PLUGIN_PATH.'/css/vendor/pickdate/themes/classic.css');
		wp_enqueue_style( 'admin-date-picker', WCPFC_PLUGIN_PATH.'/css/vendor/pickdate/themes/classic.date.css');
		wp_enqueue_style( 'admin-time-picker', WCPFC_PLUGIN_PATH.'/css/vendor/pickdate/themes/classic.time.css'); 
		?>
		<div id="wcpfc_extra_product_fields_area_after_billing_form">
			<img class="wcpf-loader"  src="<?php echo WCPFC_PLUGIN_PATH.'/img/horizontal-loader.gif'; ?>"  style="display:block;"></img>
		</div>
		<?php 
	}
	function add_product_shipping_fields_meta()
	{
		?>
		<div id="wcpfc_extra_product_fields_area_after_shipping_form">
			<img class="wcpf-loader"  src="<?php echo WCPFC_PLUGIN_PATH.'/img/horizontal-loader.gif'; ?>" style="display:block;"></img>
		</div>
		<?php 
	}
	
	function validate_fields_before_checkout_is_completed()
	{
		global $wcpfc_field_model,$wcpfc_wpml_model, $wcpfc_product_model, $wcpfc_cart_model, $wcpfc_order_model;
		
		$ship_to_different_address = isset($_POST['ship_to_different_address']);
		$posted_field_all_data = $wcpfc_field_model->retrieve_fields_data_from_posted_data();
		$cart_items = WC()->cart->get_cart();
		
			foreach($posted_field_all_data as $field_unique_id => $posted_fields)
			foreach($posted_fields as $posted_field_data)
			{
				if(!isset($cart_items[$posted_field_data["data"]["cart_key"]]) && $posted_field_data["data"]["cart_key"] != 'one_time_field')
					continue;
				
				$current_field_metadata = $posted_field_data["metadata"];
			
				//It means that it is visible and eventually has to be checked if it is required or not
				$checkbox_has_been_checked = $posted_field_data["checkbox_has_been_checked"]; 
				$field_type = $current_field_metadata["options"]["type"];
				$form_type = $posted_field_data["data"]["form_type"];
				$is_checkbox_field = $current_field_metadata["options"]["type"] == 'checkbox';
				$field_name = $current_field_metadata["name"][$wcpfc_wpml_model->get_current_locale()];
				$is_required = $current_field_metadata["options"]["required"] == 'yes';
				
				
				
				//In case of select field multiple values 
				$value = wcpfc_get_value_if_set($posted_field_data, array('data','value'), "");
				if( $value != "" && is_array($value))
				{
					$value = !empty($posted_field_data["data"]["value"]) ? $posted_field_data["data"]["value"] : "";
				}
				else 
					$value = trim($value);
				
				if($form_type == 'shipping' && !$ship_to_different_address)
					continue;
				
				//Required check
				$product_name = "";
				if($posted_field_data["data"]["cart_key"] != "one_time_field")
				{
					$product_name = sprintf( esc_html__('for product %s', 'woocommerce-product-fields-checkout'), $wcpfc_product_model->get_product_name($cart_items[$posted_field_data["data"]["cart_key"]]['data']->get_id(), false).$wcpfc_cart_model->get_wcuf_identifier($cart_items[$posted_field_data["data"]["cart_key"]]));
				}
				
				if($is_required && (($is_checkbox_field && !$checkbox_has_been_checked) || $value=="" ))
					wc_add_notice( sprintf( esc_html__('%s field %s is required.', 'woocommerce-product-fields-checkout') , $field_name, $product_name), 'error');
				
				if($field_type == 'number' && $value != "")
				{
					$min = $current_field_metadata["options"]["min_value"] != "" ? floatval($current_field_metadata["options"]["min_value"]) : "none";
					$max = $current_field_metadata["options"]["max_value"] != "" ? floatval($current_field_metadata["options"]["max_value"]) : "none";
					
					
					if ($min !== "none"  && $value < $min) 
						wc_add_notice( sprintf( esc_html__('%s field %s value cannot be lesser than %d.', 'woocommerce-product-fields-checkout') , $field_name, $product_name, $min), 'error');
					else if($max !== "none" && $value > $max)
						wc_add_notice( sprintf( esc_html__('%s field %s value cannot be greater than %d.', 'woocommerce-product-fields-checkout') , $field_name, $product_name, $max), 'error');
				}
				if($field_type == 'email' && $value != "")
				{
					if (!WC_Validation::is_email($value))
						wc_add_notice( sprintf( esc_html__('%s field %s is not a valid email.', 'woocommerce-product-fields-checkout') , $field_name, $product_name), 'error');
				}					
				
			}
		
		
	}
	
	function add_field_meta_to_order_item_meta($item_id, $values, $order_id)
	{
		global $wcpfc_field_model, $wcpfc_wpml_model, $wcpfc_datetime_mode, $wcpfc_order_model;
		if ( is_a( $values, 'WC_Order_Item_Product' ) ) 
		{
			$values = $values->legacy_values;
			
		} 
		$cart_item_key = $values["key"];
		$ship_to_different_address = isset($_POST['ship_to_different_address']);
		$posted_field_data = $wcpfc_field_model->retrieve_fields_data_from_posted_data();
		$wcpfc_order_model->set_ships_to_differt_address($order_id, !$ship_to_different_address ? 'no' : 'yes');
		
		
		$counter = 0;
		foreach($posted_field_data as $field_unique_id => $posted_fields)
			foreach($posted_fields as $posted_field_data)
			{
				$form_type = $posted_field_data["data"]["form_type"];
				$current_field_metadata = $posted_field_data["metadata"];
				$field_type = $current_field_metadata["options"]["type"];				
				$field_name = $current_field_metadata["name"][$wcpfc_wpml_model->get_current_locale()];
				$field_name_default_lang = $current_field_metadata["name"][$wcpfc_wpml_model->get_default_locale()];
				
				if($form_type == 'shipping' && !$ship_to_different_address)
					continue; 
				
				$posted_value =  isset($posted_field_data["data"]["value"]) ? $posted_field_data["data"]["value"] : "";
				$value = $value_default_lang = array("value" => $posted_value);
				switch($field_type)
				{
					case 'checkbox': $value = $value_default_lang = array('is_checked' => $posted_field_data["checkbox_has_been_checked"] ? 'yes' : 'no',
																		  'label' => $posted_field_data["data"]["label"],
																		   
																			); 
					break;
					case 'select': 
								$value = array("value" => $wcpfc_field_model->get_label_from_values($posted_value, $current_field_metadata, $wcpfc_wpml_model->get_current_locale()),
												);
								$value_default_lang = array("value" => $wcpfc_field_model->get_label_from_values($posted_value, $current_field_metadata, $wcpfc_wpml_model->get_default_locale()),
															 );
					break;
					case 'time' : //Formatted at display time
								$value  = array( 'value' => $posted_value , 
												 'format' => $current_field_metadata["options"]["time_frontend_format"],
												  ); 
								$value_default_lang = array( 'value' => $value , 
															 'format' => $current_field_metadata["options"]["time_frontend_format"],
															  ); 
						break;
					case 'date': //Formatted at display time: for some reasons the strftime crashes 
								$value  = array( 'value' =>  $posted_value , 
												 'format' => $current_field_metadata["options"]["date_frontend_format"],
												  ); 
								$value_default_lang = array( 'value' => $posted_value , 
															 'format' => $current_field_metadata["options"]["date_frontend_format"],
															  );  
						break;
						
					case 'country_state': $value = $value_default_lang = array( 'value' => $posted_value, 
																			    'state' => isset($posted_field_data["data"]["state"]) ? $posted_field_data["data"]["state"] : "",
																			); 
						break;
				}
				
				//common
				$value['type'] = $posted_field_data["data"]["field_type"];
				$value['form_type'] = $posted_field_data["data"]["form_type"];
				$value['show_in_emails'] = $current_field_metadata["options"]["show_in_emails"]; 
				$value['show_in_order_details_page'] = $current_field_metadata["options"]["show_in_order_details_page"]; 
				
				$value_default_lang['type'] = $posted_field_data["data"]["field_type"]; 
				$value_default_lang['form_type'] = $posted_field_data["data"]["form_type"]; 
				$value_default_lang['show_in_emails'] = $current_field_metadata["options"]["show_in_emails"]; 
				$value_default_lang['show_in_order_details_page'] = $current_field_metadata["options"]["show_in_order_details_page"]; 
				
				
				if($posted_field_data["data"]["cart_key"] == "one_time_field" && !isset($this->order_meta_already_saved[$field_unique_id]))
				{
					$item_to_save = array();
					$item_to_save[] = ['key' => '_wcpfc_'.$field_unique_id.'_admin_label', 'value' => $field_name_default_lang];
					$item_to_save[] = ['key' => '_wcpfc_'.$field_unique_id.'_admin_value', 'value' => $value_default_lang];
					$item_to_save[] = ['key' => '_wcpfc_'.$field_unique_id.'_customer_label', 'value' => $field_name];
					$item_to_save[] = ['key' => '_wcpfc_'.$field_unique_id.'_customer_value', 'value' => $value];
					
					$wcpfc_order_model->add_order_meta($order_id, $item_to_save, true);
					$this->order_meta_already_saved[$field_unique_id] = true;
				} 
				else if($cart_item_key == $posted_field_data["data"]["cart_key"])
				{	
				
					$wcpfc_order_model->add_order_item_meta($item_id, '_wcpfc_'.$field_unique_id."-".$counter.'_admin_label', $field_name_default_lang, false);
					$wcpfc_order_model->add_order_item_meta($item_id, '_wcpfc_'.$field_unique_id."-".$counter.'_admin_value', $value_default_lang, false);
					$wcpfc_order_model->add_order_item_meta($item_id, '_wcpfc_'.$field_unique_id."-".$counter.'_customer_label', $field_name, false);
					$wcpfc_order_model->add_order_item_meta($item_id, '_wcpfc_'.$field_unique_id."-".$counter.'_customer_value', $value, false);
					$counter++;
				}
			}
	}
	function add_field_meta_to_order_meta($order_id, $posted_data, $order)
	{
		
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		  return $order_id;
		 
	}
}
?>