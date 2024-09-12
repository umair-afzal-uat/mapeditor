<?php 
class WCPFC_Country 
{
	function __construct()
	{
		add_action('wp_ajax_wcpfc_load_state', array(&$this, 'ajax_render_state_field'));
		add_action('wp_ajax_nopriv_wcpfc_load_state', array(&$this, 'ajax_render_state_field'));
		add_action('wp_ajax_wcpfc_load_states_by_country_id', array(&$this, 'ajax_render_states_selector_by_country_id'));
		//checkout
		add_action('wp_ajax_wcpfc_load_states_by_country_id_for_frontend', array(&$this, 'ajax_load_states_data'));
		add_action('wp_ajax_nopriv_wcpfc_load_states_by_country_id_for_frontend', array(&$this, 'ajax_load_states_data'));
	}
	public function render_states_selector_by_country_id($country_id = 'none' ,  $field_id = "", $parent_id = "", $logic_field_id ="", $pre_selected_state = "")
	{
		$states_data = $this->get_state_by_country($country_id, true);
		$states = $states_data['states'];
		$label_data = $states_data['label'];
		$selected = "";
		if($field_id === "" || $parent_id === "" )
		{
	
		}	
		else if ( is_array( $states ) && empty( $states ) ) //Like Germany, it doesn't have a states/provinces
		{
			?>
			<input type="hidden" name="wcpfc_data[<?php echo $parent_id; ?>][logic_condition][<?php echo $logic_field_id; ?>][<?php echo $field_id; ?>][state]" value="<?php echo $pre_selected_state;?>" />
			<?php 
		}
		elseif(is_array($states)) //Ex.: Italy, Brazil
		{
			?>
			<select name="wcpfc_data[<?php echo $parent_id; ?>][logic_condition][<?php echo $logic_field_id; ?>][<?php echo $field_id; ?>][state]" >
				<option value="any" <?php selected($pre_selected_state, 'any'); ?>><?php esc_html_e('Any','woocommerce-product-fields-checkout');?></option>
				<?php foreach($states as $state_code => $state_name): ?> 
					<option value="<?php echo $state_code;?>" <?php selected($pre_selected_state, $state_code); ?>><?php echo $state_name;?></option>
				<?php endforeach; ?>
			</select>
			<?php 
		}
		else //$states is false. Ex.: UK
		{
			//echo 'none';
			?>
			<input type="text" value="<?php echo $pre_selected_state;?>" name="wcpfc_data[<?php echo $parent_id; ?>][logic_condition][<?php echo $logic_field_id; ?>][<?php echo $field_id; ?>][state]" />
			<small className="wcpfc_state_description"><?php esc_html_e('(Leave empty to apply to all or type the name of the state/county/province)', 'woocommerce-product-fields-checkout'); ?></small>
			<?php 
		}
		
		
	}
	public function ajax_render_states_selector_by_country_id()
	{
		$country_id = isset($_POST['country_code']) ? $_POST['country_code'] : "";
		//$item_type = isset($_POST['item_type']) ? $_POST['item_type'] : "";
		$field_id = isset($_POST['field_id']) ? $_POST['field_id'] : "";
		$parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : "";
		$logic_field_id = isset($_POST['logic_field_id']) ? $_POST['logic_field_id'] : "";
		$pre_selected_state = isset($_POST['selected_state']) ? $_POST['selected_state'] : "";
		$this->render_states_selector_by_country_id($country_id, $field_id , $parent_id, $logic_field_id, $pre_selected_state);
		wp_die();
	}
	public function ajax_load_states_data()
	{
		$country_id = isset($_POST['country_code']) ? $_POST['country_code'] : "";
		
		$states_data = $this->get_state_by_country($country_id, true);
		$states = $states_data['states'];
		$label_data = $states_data['label'];
		$label_to_show = wcpfc_get_value_if_set($label_data, array($country_id, "state", "label"), $label_data['default']['state']['label']);
		$output = array();
		
		//wcpfc_var_dump($states);
		
		if ( is_array( $states ) && empty( $states ) ) //Like Germany, it doesn't have a states/provinces
		{
			$output = array (
				'type' => 'hidden',
				'label' => "",
				'states' => $states
			);
		}
		elseif(is_array($states)) //Ex.: Italy, Brazil
		{
			//echo json_encode($states);	
			$output = array (
				'type' => 'select',
				'label' => $label_to_show,
				'states' => $states
			);
		}
		else //$states is false. Ex.: UK
		{
			//echo 'none';
			$output = array (
				'type' => 'input',
				'label' => $label_to_show,
				'states' => array()
			);
		}
		echo json_encode($output);
		wp_die();	
	}
	public function ajax_render_state_field()
	{
		$country_id = isset($_POST['country_code']) ? $_POST['country_code'] : 'none';
		$form_type = isset($_POST['form_type']) ? $_POST['form_type'] : 'billing';
		$unique_id = isset($_POST['unique_id']) ? $_POST['unique_id'] : 'none';
		$is_checkout_page = isset($_POST['is_checkout_page']) ? $_POST['is_checkout_page'] == 'true' : true;
		$state_selector_width = isset($_POST['state_selector_width']) ? $_POST['state_selector_width'] : 'wide';
		$prev_state_value = isset($_POST['prev_state_value']) && $_POST['prev_state_value'] != 'none' ? $_POST['prev_state_value'] : null;
		if($country_id == 'none' || $unique_id  == 'none')
		{
			echo "none";
			wp_die();
		}
		$states_data = $this->get_state_by_country($country_id, true);
		$states = $states_data['states'];
		$label_data = $states_data['label'];
		
		if ( is_array( $states ) && empty( $states ) ) //Like Germany, it doesn't have a states/provinces
		{
			woocommerce_form_field($form_type.'_wcpfc_id_'.$unique_id."_state", array(
							'type'       => 'hidden',
							'class'      => array( 'form-row-'.$state_selector_width ),
							'value'    => $states,
							'required' => false,
							'label'      => !isset($label_data[$country_id]['state']['label']) ? "&nbsp;": $label_data[$country_id]['state']['label'],
							'custom_attributes'  => array('required' => 'required')
							), $prev_state_value
					);
		}
		elseif(is_array($states)) //Ex.: Italy, Brazil
		{
			$reordered_states = array();
			$reordered_states[""] = esc_html__('Select one','woocommerce-product-fields-checkout');
			foreach($states as $state_code => $state_name)
				$reordered_states[$state_code] = $state_name;
			
			$required = isset($label_data[$country_id]['state']['required']) ? $label_data[$country_id]['state']['required'] : false;
			$custom_attributes = $required ? array('required' => 'required') : array();
			woocommerce_form_field($form_type.'_wcpfc_id_'.$unique_id."_state", array(
							'type'       => 'select',
							'required'          => $required,
							'class'      => array( 'form-row-'.$state_selector_width ),
							'label'      => !isset($label_data[$country_id]['state']['label']) ? esc_html__('State / County','woocommerce-product-fields-checkout') : $label_data[$country_id]['state']['label'],
							'input_class' => array('wcpfc_select2','wcpfc_state_select', 'not_empty'),
							'options'    => $reordered_states,
							'custom_attributes'  => $custom_attributes
							), $prev_state_value 
					);
		}
		else //$states is false. Ex.: UK
		{
			$required = isset($label_data[$country_id]['state']['required']) ? $label_data[$country_id]['state']['required'] : false;
			$custom_attributes = $required ? array('required' => 'required') : array();
			woocommerce_form_field($form_type.'_wcpfc_id_'.$unique_id."_state", array(
						'type'       => 'text',
						'class'      => array( 'form-row-'.$state_selector_width ),
						'required'          => $required,
						'label'      => !isset($label_data[$country_id]['state']['label']) ? esc_html__('State / County','woocommerce-product-fields-checkout') : $label_data[$country_id]['state']['label'],//esc_html__('State / Province','woocommerce-product-fields-checkout'),
						'custom_attributes'  => $custom_attributes
						), $prev_state_value
					);
		}
		
		wp_die();
	}
	public function get_state_by_country($country_id, $return_label_data = false, $type = 'billing')
	{
		$countries_obj   = new WC_Countries();
		
		$states = $countries_obj->get_states( $country_id ); //paramenter -> GB, IT ... is the "value" selected in the $countries select box
		$label_data =  $countries_obj->get_country_locale();
		
		return !$return_label_data ? $states : array('label' =>$label_data, 'states' => $states);
	}
	
	function get_countries($type = 'all', $empty_selection = false)
	{
		$countries_obj  = new WC_Countries();
		
		switch($type)
		{
			default:
			case 'all': $countries = $countries_obj->get_countries();
			break;
			case 'allowed': $countries   = $countries_obj->get_allowed_countries();
			break;
			case 'shipping_countries': $countries  = $countries_obj->get_shipping_countries();
			break;
		}
		
		if(count($countries) > 1)
		{
			$reordered_states = array();
			if( $empty_selection)
				$reordered_states[""] = esc_html__('Select one','woocommerce-product-fields-checkout');
			foreach($countries as $country_code => $country_name)
				$reordered_states[$country_code] = $country_name;
		}
		else
			$reordered_states = $countries;
		return $reordered_states;
	}
	function get_countries_with_states()
	{
		$country = $this->get_countries();
		$result = array();
		
		foreach((array)$country as $country_code => $country_name)
		{
			$states = $this->get_state_by_country($country_code);
			if(is_array($states) && !empty($states))
				$result[$country_code] = $country_name;
		}
		return $result;
	}
	function country_code_to_name($code)
	{
		$countries_obj   = new WC_Countries();
		return  isset($countries_obj->countries[ $code ])  ? $countries_obj->countries[ $code ]  : $code;
	}
	function state_code_to_name($state_code, $country_code = null )
	{
		$countries_obj   = new WC_Countries();
		$result = $countries_obj->get_states($country_code );
	
		if($result)
		{
			if($country_code == null)
			{
				foreach($result as $country)
					if(isset($country[$state_code]))
						return $country[$state_code];
			}
			else if(isset($result[$state_code]))
				return $result[$state_code];
			else
				return isset($result[$country_code][$state_code]) ? $result[$country_code][$state_code] : $state_code;
		}
		return $state_code;
	}
}
?>