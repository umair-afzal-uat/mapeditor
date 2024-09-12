<?php 
class WCPFC_FieldDisplayManagment
{
	function __construct()
	{
		//*OneTime fields
		//**Emails
		add_action('woocommerce_email_after_order_table',array(&$this, 'render_one_time_fields_on_email'), 8 , 4); 
		//**Thank you page, Order details page
		add_action('woocommerce_order_details_after_order_table',array(&$this, 'render_one_time_fields_after_produt_table')); 
		
		//*Field per product
		//**This is fired both by emails and order details page while rendering item table
		add_action( 'woocommerce_order_item_meta_end', array( &$this, 'display_order_item_meta' ), 20, 3 ); 
		
	}
	function render_one_time_fields_on_email($order, $sent_to_admin, $plain_text, $email)
	{
		$this->render_one_time_fields_after_produt_table($order, true);
	}
	//Order fields
	function render_one_time_fields_after_produt_table($order, $is_email = false)
	{
		global $wcpfc_order_model, $wcpfc_field_model;
		$order_meta = $wcpfc_order_model->get_field_meta_data_from_order($order);
		$text_align = is_rtl() ? 'right' : 'left';
		$i = 0;
		
		
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
		
	
		
		$fields_to_show = array('billing' => array(), 'shipping' => array());
		foreach($order_meta as $field_data)
		{
			//Can be displayed? 
			//Note: ready display option from field settings and if not existing read from field metadata?
			if(!$is_email && $field_data["value"]["show_in_order_details_page"] == 'yes')
				$fields_to_show[$field_data["value"]['form_type']][] = $field_data;
			elseif($is_email && $field_data["value"]["show_in_emails"] == 'yes')
				$fields_to_show[$field_data["value"]['form_type']][] = $field_data; 
		}
			
		foreach($fields_to_show as $form_type => $current_fields)
		{
			$exists_at_least_one_field = false;
			$section_label_already_printed = false;
			$form_type_label = $form_type == 'billing' ? esc_html__('Billing', 'woocommerce-product-fields-checkout') : esc_html__('Shipping', 'woocommerce-product-fields-checkout');
			
			ob_start();
			if(count($current_fields) > 0)
			{
				if(!$is_email): ?>
					<table class="woocommerce-table woocommerce-table--order-details shop_table order_details wcpfc_fields_table" >
				<?php else: ?>
					<table class="td" cellspacing="0" cellpadding="6" style="margin-top:20px; margin-bottom:20px; width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
				<?php endif; ?>
				<tfoot>
				<?php 
			}
			foreach($current_fields as $field_data)
			{
				
				$exists_at_least_one_field = true;
				$meta_value = $wcpfc_field_model->get_field_readable_value($field_data);
				?>
					<?php if(!$is_email):?>
					<tr>
						<th scope="row" class="wcpfc_table_fields_th" ><?php echo $field_data['label'];  ?>:</th>
						<td class="wcpfc_table_fields_td" ><?php echo  $meta_value ?></td>
					</tr>
					<?php else:?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $field_data['label'] ?></th>
						<td class="td" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $meta_value; ?></td>
					</tr>
					<?php $i++; endif;?>
				<?php 
				$i++;
				$section_label_already_printed = true;
			}
			if(count($current_fields) > 0)
			{
				?>
				</tfoot>
				</table>
				<?php
			}
			$html = ob_get_contents();
			ob_end_clean();
			
			if($exists_at_least_one_field)
			{
				$css =  $is_email ? 'style="margin-top:20px;"' : "";
				echo $html;
			}
		}
	}
	function display_order_item_meta($item_id, $item, $order )
	{
		global $wcpfc_field_model, $wcpfc_order_model;		
		$is_email = did_action('woocommerce_email_order_details') > 0;
		
		$result = $wcpfc_order_model->get_field_meta_data_from_order_item($item);
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
			?> <ul class="wc-item-meta"><?php 
		
			foreach($fields_to_show as $field_data): 
					$meta_value = $wcpfc_field_model->get_field_readable_value($field_data);
					$meta_value = trim($meta_value) == "" ? "&nbsp;<br/>" : $meta_value ;
					?>
					<li>
						<strong class="wc-item-meta-label"><?php echo $field_data["label"] ?>:</strong>
						<p><?php echo $meta_value; ?></p>
					</li>
				<?php 
		endforeach;
		
		if(count($fields_to_show) > 0 )
		?> </ul><?php 
	}
}
?>