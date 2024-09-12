<?php class WCPFC_Order 
{
	function __construct()
	{
		
	}
	public function add_order_item_meta($item_id, $item_label, $value, $unique = false)
	{
		wc_add_order_item_meta($item_id, $item_label, $value, $unique);
	}
	public function add_order_meta($order_id_or_obj, $label_values_array, $unique = false)
	{
		$order = is_object($order_id_or_obj) ? $order_id_or_obj : wc_get_order($order_id_or_obj);
		if($order == false)
			return;
		foreach($label_values_array as $pair)
		{
			$order->add_meta_data($pair["key"], $pair["value"], $unique);
		}
		$order->save();
	}
	public function is_meta_existing($order_id, $meta_name)
	{
		$check = wp_cache_get($order_id, 'post_meta');
		return !isset($check[$meta_name]) ? false : true;
	}
	public function save_meta($order_id, $meta_key, $meta_value)
	{
		$order = wc_get_order($order_id);
		if(is_bool($order))
			return;
		$order->update_meta_data($meta_key, $meta_value);
		$order->save();
	}
	public function delete_meta($order_id, $meta_key)
	{
		$order = wc_get_order($order_id);
		if(is_bool($order))
			return;
		$order->delete_meta_data($meta_key);
		$order->save();
	}
	public function ships_to_differt_address($order_id)
	{
		$order = new WC_Order($order_id);
		$result = $order->get_meta('_ship_to_different_address');
		
		return $result == 'yes' ? true : false;
	}
	public function set_ships_to_differt_address($order_id, $value)
	{
		$this->save_meta($order_id, '_ship_to_different_address', $value);
	}
	function get_field_meta_data_from_order_item($item, $field_type = 'customer') //admin || customer
	{
		$metedata = $item->get_meta_data();
		$result = $final_result = array();
		foreach($metedata as $meta)
		{
			$data = $meta->get_data();		
			$keys = explode("_",$data["key"]);
			$field_ids =  isset($keys[2]) ? explode("-", $keys[2]) : array($data["key"]);
			/* keys example (order item meta): 
				array(5) {
				  [0]=>
				  string(0) ""
				  [1]=>
				  string(5) "wcpfc"
				  [2]=>
				  string(10) "6xvhv24a5l-1" -> 6xvhv24a5l: unique_id ; 1: counter (there can be multiple fields with the same unique_id) -> see $field_ids
											  //On order meta, the id is just "6xvhv24a5l" without counters
				  [3]=>
				  string(8) "customer"
				  [4]=>
				  string(5) "label" 
				}
			*/
			
			
			
			if(!isset($keys[1]) || $keys[1] != "wcpfc") 
				continue;
			
			if(!isset($keys[3]) || $keys[3] != $field_type)
				continue;
			
			
			if(!isset($result[$field_ids[0]]))
				$result[$field_ids[0]] = array();
			
			if(!isset($result[$field_ids[0]][$field_ids[1]]))
					$result[$field_ids[0]][$field_ids[1]] = array();
				$result[$field_ids[0]][$field_ids[1]][$keys[4]] = $data["value"];
			
		}
		while(!empty($result))
			foreach($result as $key => $current_elem)
			{
				reset($current_elem);
				$first_key = key($current_elem); 
				$final_result[] =  $current_elem[$first_key];
				unset($result[$key][$first_key]);
				if(empty($result[$key]))
					unset($result[$key]);
			}
		
		return $final_result;
	}
	function get_field_meta_data_from_order($item, $field_type = 'customer') //admin || customer
	{
		$metedata = $item->get_meta_data();
		$result = $final_result = array();
		foreach($metedata as $meta)
		{
			$data = $meta->get_data();		
			$keys = explode("_",$data["key"]);
			/* keys example (order item meta): 
				array(5) {
				  [0]=>
				  string(0) ""
				  [1]=>
				  string(5) "wcpfc"
				  [2]=>
				  string(10) "6xvhv24a5l" -> 
				  [3]=>
				  string(8) "customer"
				  [4]=>
				  string(5) "label" 
				}
			*/
			
			
			
			if(!isset($keys[1]) || $keys[1] != "wcpfc") 
				continue;
			
			if(!isset($keys[3]) || $keys[3] != $field_type)
				continue;
			
			if(!isset($result[$keys[2]]))
				$result[$keys[2]] = array();
			
			$result[$keys[2]][$keys[4]] = $data["value"]; //checkbox, not ckeched -> "" else 1 
			
		}
		return $result;
	}
	function get_order_items_key_names_to_exclude($order)
	{
		$order_items =  $order->get_items(); 
		$result = array();
		foreach($order_items as $order_item)
		{
			$data = $order_item->get_meta_data();		
			foreach($data as $current_metadata)
			{
				$data = $current_metadata->get_data();	
				if(wcpfc_start_with($data["key"], "_wcpfc"))
					$result[] = $data["key"];
			}
		}
		
		return $result;
	}
} 
?>