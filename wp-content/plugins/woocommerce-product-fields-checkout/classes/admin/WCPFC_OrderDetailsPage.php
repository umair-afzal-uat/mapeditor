<?php 
class WCPFC_OrderDetailsPage
{
	function __construct()
	{
		if (defined('DOING_AJAX') && DOING_AJAX)
			return;
		
		add_action( 'woocommerce_after_order_itemmeta', array( &$this, 'display_order_item_meta' ), 10, 3 );
		add_filter( 'woocommerce_hidden_order_itemmeta', array( &$this, 'hide_private_metakeys' )); //hidden wcmca keys
		
		//Onetime fields 
		add_action( 'woocommerce_admin_order_data_after_billing_address',  array( &$this,'display_billing_order_one_time_fields' ));
		add_action( 'woocommerce_admin_order_data_after_shipping_address',  array( &$this,'display_shipping_order_one_time_fields' ));
	}
	function display_shipping_order_one_time_fields($order)
	{
		global $wcpfc_order_model;
		if(!$wcpfc_order_model->ships_to_differt_address($order->get_id()))
			return; 
		$this->render_one_time_fields_by_type($order, 'shipping');
	}
	function display_billing_order_one_time_fields($order)
	{
		$this->render_one_time_fields_by_type($order);
	}
	private function render_one_time_fields_by_type($order, $type = 'billing')
	{
		global $wcpfc_order_model, $wcpfc_field_model;
		$order_meta = $wcpfc_order_model->get_field_meta_data_from_order($order, 'admin');
		$text_align = is_rtl() ? 'right' : 'left';
		$i = 0;
		
		$fields_to_show = array('billing' => array(), 'shipping' => array());
		foreach($order_meta as $field_data)
		{
			$fields_to_show[$field_data["value"]['form_type']][] = $field_data;
		}
		/* example: $order_meta
		array(1) {
			  ["qiew7s7vwjl"]=>
				  array(2) {
					["label"]=>
					string(7) "Onetime"
					["value"]=>
					array(5) {
					  ["value"]=>
					  string(7) "billing"
					  ["type"]=>
					  string(4) "text"
					  ["form_type"]=>
					  string(7) "billing"
					  ["show_in_emails"]=>
					  string(2) "no"
					  ["show_in_order_details_page"]=>
					  string(2) "no"
					}
				  }
			*/
			
		wp_enqueue_style('WCPFC-order-details-page', WCPFC_PLUGIN_PATH.'/css/admin-order-details-page.css'); 		
		foreach($fields_to_show[$type] as $field_data)
			{
				$meta_value = $wcpfc_field_model->get_field_readable_value($field_data);
				
				echo '<p>';
				echo '<strong>'.$field_data['label'].':</strong>';
				echo '<span class="wcpfc-order-field-content">'.$meta_value.'</span>';
				echo '</p>';
			}
	}
	function display_order_item_meta($item_id, $item, $_product )
	{
		global $wcpfc_field_model, $wcpfc_order_model;		
		$is_email = did_action('woocommerce_email_order_details') > 0;
		
		$result = $wcpfc_order_model->get_field_meta_data_from_order_item($item, 'admin');
		$fields_to_show = array();
		foreach($result as $field_data)
		{
			//Can be displayed? 
			//Note: ready display option from field settings and if not existing read from field metadata?
			if(!$is_email && $field_data["value"]["show_in_order_details_page"] == 'yes')
				$fields_to_show[] = $field_data;
			elseif($is_email && $field_data["value"]["show_in_emails"] == 'yes')
				$fields_to_show[] = $field_data;
		}
	
		
		if(count($fields_to_show) > 0 )
			?> <div class="view"><table class="display_meta" cellspacing="0">
							<tbody><?php 
		
			foreach($fields_to_show as $field_data): ?>
					<tr>
						<th><?php echo $field_data["label"] ?>:</th>
						<td><p><?php echo $wcpfc_field_model->get_field_readable_value($field_data) ?></p></td>
					</tr>
				<?php 
		endforeach;
		
		if(count($fields_to_show) > 0 )
		?> </tbody></table></div><?php 
	}
	
	public function hide_private_metakeys($keys)
	{
		global $wcpfc_order_model, $post;

		$order = wc_get_order($post->ID);
		$keys_to_exclude = $wcpfc_order_model->get_order_items_key_names_to_exclude($order);
		if(!empty($keys_to_exclude))
			$keys = array_merge($keys, $keys_to_exclude);
		
		return $keys;
	}
}
?>