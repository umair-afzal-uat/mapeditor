<?php 
class WCPFC_FieldConfiguratorPage
{
	var $page = "woocommerce-product-fields-checkout";
	var $jsx_handlers = array();
	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_filter( 'script_loader_tag', array($this, 'add_jsx_script_loading'), 10, 3 );
	}
	public function add_jsx_script_loading( $tag, $handle, $src)
	{
		if ( is_admin() && in_array( $handle, $this->jsx_handlers) ) 
		{
			$tag = strpos($tag, "type='text/javascript'") !== false ? str_replace( "<script type='text/javascript'", "<script type='text/babel'", $tag ) : str_replace( "<script", "<script type='text/babel' ", $tag );
		}

	  return $tag;
	}
	public function add_page($cap )
	{
		$this->page = add_submenu_page( 'woocommerce-product-fields-checkout', esc_html__('Fields', 'woocommerce-product-fields-checkout'),esc_html__('Fields', 'woocommerce-product-fields-checkout'), $cap, 'woocommerce-product-fields-checkout', array($this, 'render_page'));
		
		add_action('load-'.$this->page,  array($this,'page_actions'),9);
		add_action('admin_footer-'.$this->page,array($this,'footer_scripts'));
	}
	function footer_scripts(){
		?>
		<script> postboxes.add_postbox_toggles(pagenow);</script>
		<?php
	}
	
	function page_actions()
	{
		do_action('add_meta_boxes_'.$this->page, null);
		do_action('add_meta_boxes', $this->page, null);
	}
	public function render_page()
	{
		global $pagenow, $wcpfc_field_model, $wcpfc_wpml_model;
		
		$wcpfc_wpml_model->switch_to_default_language();
		
		//Save data 
		if(isset($_POST) && isset($_POST['wcpfc_nonce_configuration_data']) && wp_verify_nonce($_POST['wcpfc_nonce_configuration_data'], 'wcpfc_save_data') && isset($_POST['wcpfc_data']))
			$wcpfc_field_model->save_field_data($_POST['wcpfc_data']);
		elseif(isset($_POST) && isset($_POST['wcpfc_nonce_configuration_data']) && wp_verify_nonce($_POST['wcpfc_nonce_configuration_data'], 'wcpfc_save_data') && !isset($_POST['wcpfc_data']))
			$wcpfc_field_model->delete_field_data();
			
		if(isset($_GET['force_delete']))
			$wcpfc_field_model->delete_field_data();
		
		
		
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
		
		
		add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
		
		wp_enqueue_script('postbox'); 
		
		?>
		<div class="wrap">
			 <h2><?php esc_html_e('WooCommerce Checkout Fields Configurator','woocommerce-product-fields-checkout'); ?></h2>
	
			<form id="post"  method="post">
				<?php wp_nonce_field( 'wcpfc_save_data', 'wcpfc_nonce_configuration_data' ); ?>
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
						<div id="post-body-content">
						</div>
						
						<div id="postbox-container-1" class="postbox-container">
							<?php do_meta_boxes('woocommerce-product-fields-checkout','side',null); ?>
						</div>
						
						<div id="postbox-container-2" class="postbox-container">
							  <?php do_meta_boxes('woocommerce-product-fields-checkout','normal',null); ?>
							  <?php do_meta_boxes('woocommerce-product-fields-checkout','advanced',null); ?>
							  
						</div> 
					</div> <!-- #post-body -->
				</div> <!-- #poststuff -->
				
			</form>
		</div> <!-- .wrap -->
		<?php 
	}
	
	function add_meta_boxes()
	{
		$screen = get_current_screen();if(!$screen || $screen->base != "toplevel_page_woocommerce-product-fields-checkout")
			return;
		
		 add_meta_box( 'wcpfc_product_fields', 
					esc_html__('Fields','woocommerce-product-fields-checkout'), 
					array($this, 'render_product_fields_meta_box'), 
					'woocommerce-product-fields-checkout', 
					'normal' //side
			); 
			
			 add_meta_box( 'wcpfc_save_data', 
					esc_html__('Save','woocommerce-product-fields-checkout'), 
					array($this, 'render_save_button_meta_box'), 
					'woocommerce-product-fields-checkout', 
					'side' //side
			); 
		
	
	}
	function render_product_fields_meta_box()
	{
		global $wcpfc_wpml_model, $wcpfc_country_model, $wcpfc_field_model, $wcpfc_payment_method_model;
		
		$countries = $wcpfc_country_model->get_countries();
		$field_data = $wcpfc_field_model->get_field_data();
		$langs =  $wcpfc_wpml_model->get_langauges_list();
		$payment_methods = array();
							
		$js_data  = array(	'lang_data' => $langs,
							'default_lang' => get_locale(),
							'add_new_text' => esc_html__( 'Add new', 'woocommerce-product-fields-checkout' ),
							'type_tex' => esc_html__( 'Select type', 'woocommerce-product-fields-checkout' ),
							'field_position_text' => esc_html__( 'Position', 'woocommerce-product-fields-checkout' ),
							'after_billing_form_text' => esc_html__( 'After billing form', 'woocommerce-product-fields-checkout' ),
							'after_shipping_form_text' => esc_html__( 'After shipping form', 'woocommerce-product-fields-checkout' ),
							'field_label_text' => esc_html__( 'Field label', 'woocommerce-product-fields-checkout' ),
							'add_new_logic_rule_text' => esc_html__( 'Add new logic rule', 'woocommerce-product-fields-checkout' ),
							'remove_text' => esc_html__( 'Remove', 'woocommerce-product-fields-checkout' ),
							'placeholder_text' => esc_html__( 'Placeholder', 'woocommerce-product-fields-checkout' ),
							'html_field_label' => esc_html__( 'Type the HTML code', 'woocommerce-product-fields-checkout' ),
							'html_description_text' => esc_html__( 'This field will be showed ONLY on the checkout page and it is used to created heading, description or what you need to better organize other fields.', 'woocommerce-product-fields-checkout' ),
							'label_text' => esc_html__( 'Label', 'woocommerce-product-fields-checkout' ),
							'is_checked' => esc_html__( 'Is checked', 'woocommerce-product-fields-checkout' ),
							'value_text' => esc_html__( 'Value', 'woocommerce-product-fields-checkout' ),
							'description_text' => esc_html__( 'Description', 'woocommerce-product-fields-checkout' ), 
							'css_classes_text' => esc_html__( 'CSS row classes', 'woocommerce-product-fields-checkout' ),
							'css_input_text' => esc_html__( 'CSS input classes', 'woocommerce-product-fields-checkout' ),
							'mandatory_text' => esc_html__( 'Required', 'woocommerce-product-fields-checkout' ),
							'show_in_emails_text' => esc_html__( 'Show in emails', 'woocommerce-product-fields-checkout' ),
							'show_in_order_details_text' => esc_html__( 'Show in the order details page', 'woocommerce-product-fields-checkout' ),
							'yes_text' => esc_html__( 'Yes', 'woocommerce-product-fields-checkout' ),
							'no_text' => esc_html__( 'No', 'woocommerce-product-fields-checkout' ),
							'row_width_text' => esc_html__( 'Row width', 'woocommerce-product-fields-checkout' ),
							'row_width_full' => esc_html__( 'Full width', 'woocommerce-product-fields-checkout' ),
							'row_width_first' => esc_html__( 'Half left', 'woocommerce-product-fields-checkout' ),
							'row_width_last' => esc_html__( 'Half right', 'woocommerce-product-fields-checkout' ),
							'min_value_text' => esc_html__( 'Min value', 'woocommerce-product-fields-checkout' ),
							'max_value_text' => esc_html__( 'Max value', 'woocommerce-product-fields-checkout' ),
							'option_area_title_text' => esc_html__( 'Genaral options', 'woocommerce-product-fields-checkout' ),
							'once_text' => esc_html__( 'One time', 'woocommerce-product-fields-checkout' ),
							'each_product_text' => esc_html__( 'Per product', 'woocommerce-product-fields-checkout' ),
							'each_product_quantity_text' => esc_html__( 'Per cart quantity', 'woocommerce-product-fields-checkout' ),
							'display_policy_description_text' => esc_html__( 'Per product: The field will be showed for each matching product. Per cart quantity: the field will be showed for each mathcing product multiplied its cart quantity. One time: The field will be showed just one time if any of the products matches. ', 'woocommerce-product-fields-checkout' ),
							'display_policy_text' => esc_html__( 'Display policy', 'woocommerce-product-fields-checkout' ),
							'display_category_policy_text' => esc_html__( 'Children categories', 'woocommerce-product-fields-checkout' ),
							'categories_only_text' => esc_html__( 'Consider only categories', 'woocommerce-product-fields-checkout' ),
							'categories_and_children_text' => esc_html__( 'Consider categories and all children', 'woocommerce-product-fields-checkout' ),
							'products_selection_area_title_text' => esc_html__( 'Visibility', 'woocommerce-product-fields-checkout' ),
							'products_selection_area_description_text' => esc_html__( 'Select for which products/categories the fild will be associated. Note that if no products/categories the field will be associated to all the products currently in cart.', 'woocommerce-product-fields-checkout' ),
							'select_product_text' => esc_html__( 'Select products', 'woocommerce-product-fields-checkout' ),
							'select_categories_text' => esc_html__( 'Select categories', 'woocommerce-product-fields-checkout' ),
							'country_to_show_text' => esc_html__( 'Countries show', 'woocommerce-product-fields-checkout' ),
							'country_to_show_description_text' => esc_html__( 'NOTE: if the All option is selected, state/provinces dropdown selector will be properly showed only for the allowed selling location configured in the WooCommerce -> Settings -> General menu', 'woocommerce-product-fields-checkout' ),
							'coultry_all_selection' => esc_html__( 'All', 'woocommerce-product-fields-checkout' ),
							'coultry_selling_selection' => esc_html__( 'Selling location', 'woocommerce-product-fields-checkout' ),
							'coultry_shipping_selection' => esc_html__( 'Shipping location', 'woocommerce-product-fields-checkout' ),
							'hide_state_text' => esc_html__( 'Hide states/province/county selection', 'woocommerce-product-fields-checkout' ),
							'state_selector_width_text' => esc_html__( 'States/province/county selector width', 'woocommerce-product-fields-checkout' ),
							'select_multiple_value_text' => esc_html__( 'Multiple value selection', 'woocommerce-product-fields-checkout' ),
							'label_and_value_text' => esc_html__( 'Values and labels', 'woocommerce-product-fields-checkout' ),
							'label_text' => esc_html__( 'Label', 'woocommerce-product-fields-checkout' ),
							'add_new_text' => esc_html__( 'Add new', 'woocommerce-product-fields-checkout' ),
							'min_time_text' => esc_html__( 'Min time', 'woocommerce-product-fields-checkout' ),
							'max_time_text' => esc_html__( 'Max time', 'woocommerce-product-fields-checkout' ),
							'min_date_text' => esc_html__( 'Min date', 'woocommerce-product-fields-checkout' ),
							'max_date_text' => esc_html__( 'Max date', 'woocommerce-product-fields-checkout' ),
							'min_time_type_text' => esc_html__( 'Min time type', 'woocommerce-product-fields-checkout' ),
							'max_time_type_text' => esc_html__( 'Max time type', 'woocommerce-product-fields-checkout' ),
							'min_date_type_text' => esc_html__( 'Min time type', 'woocommerce-product-fields-checkout' ),
							'max_date_type_text' => esc_html__( 'Max time type', 'woocommerce-product-fields-checkout' ),
							'days_of_the_week_to_disable' => esc_html__( 'Days of the week to disable', 'woocommerce-product-fields-checkout' ),
							'monday_text' => esc_html__( 'Monday', 'woocommerce-product-fields-checkout' ),
							'tuesday_text' => esc_html__( 'Tuesday', 'woocommerce-product-fields-checkout' ),
							'wednesday_text' => esc_html__( 'Wednesday', 'woocommerce-product-fields-checkout' ),
							'thursday_text' => esc_html__( 'Thursday', 'woocommerce-product-fields-checkout' ),
							'friday_text' => esc_html__( 'Friday', 'woocommerce-product-fields-checkout' ),
							'saturday_text' => esc_html__( 'Saturday', 'woocommerce-product-fields-checkout' ),
							'sunday_text' => esc_html__( 'Sunday', 'woocommerce-product-fields-checkout' ),
							'dateformat_text' => esc_html__( 'Date format', 'woocommerce-product-fields-checkout' ),
							'dateformat_description_text' => esc_html__( 'Format used for frontend.', 'woocommerce-product-fields-checkout' ),
							'date_num_of_years_text' => esc_html__( 'Number of years to show', 'woocommerce-product-fields-checkout' ),
							'date_num_of_years_description_text' => esc_html__( 'You can specify the number of years to show in the year dropdown selector.', 'woocommerce-product-fields-checkout' ),
							'absolute_text' => esc_html__( 'Absolute', 'woocommerce-product-fields-checkout' ),
							'relative_text' => esc_html__( 'Relative', 'woocommerce-product-fields-checkout' ),
							'min_time_can_be_before_now_text' => esc_html__( 'Min time can be earlier than "now"?', 'woocommerce-product-fields-checkout' ),
							'min_date_can_be_before_now_text' => esc_html__( 'Min date can be earlier than "now"?', 'woocommerce-product-fields-checkout' ),
							'minute_interval_timepicker_text' => esc_html__( 'Minute interval between each time showed in the frontend timepicker', 'woocommerce-product-fields-checkout' ),
							'min_relative_time_from_now' => esc_html__( 'Min relative time from now', 'woocommerce-product-fields-checkout' ),
							'max_relative_time_from_now' => esc_html__( 'Max relative time from now', 'woocommerce-product-fields-checkout' ),
							'min_relative_date_from_now' => esc_html__( 'Min relative date from now', 'woocommerce-product-fields-checkout' ),
							'max_relative_date_from_now' => esc_html__( 'Max relative date from now', 'woocommerce-product-fields-checkout' ),
							'seconds_text' => esc_html__( 'Seconds', 'woocommerce-product-fields-checkout' ),
							'minutes_text' => esc_html__( 'Minutes', 'woocommerce-product-fields-checkout' ),
							'hours_text' => esc_html__( 'Hours', 'woocommerce-product-fields-checkout' ),
							'days_text' => esc_html__( 'Days', 'woocommerce-product-fields-checkout' ),
							'months_text' => esc_html__( 'Months', 'woocommerce-product-fields-checkout' ),
							'years_text' => esc_html__( 'Years', 'woocommerce-product-fields-checkout' ),
							'conditional_logic_area_title_text' => esc_html__( 'Conditional logic', 'woocommerce-product-fields-checkout' ),
							'conditional_logic_area_description_text' => esc_html__( 'The field will be showed if the following logic rules will be true. Rules are related with the "AND" logic operator. The field can be related only to other fields that has the same DISPLAY POLICY and POSITION values.', 'woocommerce-product-fields-checkout' ),
							'condition_title_text' => esc_html__( 'Rule', 'woocommerce-product-fields-checkout' ),
							'or_text' => esc_html__( 'Or', 'woocommerce-product-fields-checkout' ),
							'any_text' => esc_html__( 'Any', 'woocommerce-product-fields-checkout' ),
							//Logic 
							'is_text' => esc_html__( 'Is', 'woocommerce-product-fields-checkout' ),
							'not_text' => esc_html__( 'Not', 'woocommerce-product-fields-checkout' ),
							'condition_text' => esc_html__( 'Condition', 'woocommerce-product-fields-checkout' ),
							'contains_text' => esc_html__( 'Containing', 'woocommerce-product-fields-checkout' ),
							'equal_to_text' => esc_html__( 'Equal to', 'woocommerce-product-fields-checkout' ),
							'starts_with_text' => esc_html__( 'Starting with', 'woocommerce-product-fields-checkout' ),
							'ends_with_text' => esc_html__( 'Ends with', 'woocommerce-product-fields-checkout' ),
							'lesser_equal_than_text' => esc_html__( 'Lesser or equal than', 'woocommerce-product-fields-checkout' ),
							'lesser_than_text' => esc_html__( 'Lesser than', 'woocommerce-product-fields-checkout' ),
							'greater_than_text' => esc_html__( 'Greater than', 'woocommerce-product-fields-checkout' ),
							'greater_equal_than_text' => esc_html__( 'Greater or equal than', 'woocommerce-product-fields-checkout' ),
							'multiple_value_policy_text' => esc_html__( 'Multiple values policy', 'woocommerce-product-fields-checkout' ),
							'multiple_value_policy_description_text' => esc_html__( 'The condition will be considered true if', 'woocommerce-product-fields-checkout' ),
							'at_least_one_text' => esc_html__( 'Condition applies at least one', 'woocommerce-product-fields-checkout' ),
							'all_text' => esc_html__( 'Condition applies to all', 'woocommerce-product-fields-checkout' ),
							'is_checked' => esc_html__( 'Is checked', 'woocommerce-product-fields-checkout' ),
							'is_not_checked' => esc_html__( 'is not checked', 'woocommerce-product-fields-checkout' ),
							'confirm_field_delete_text' => esc_html__( 'Are you sure you want to delete the field?', 'woocommerce-product-fields-checkout' ),
							//
							'button_type_texts' => json_encode(
															array( //to add, extend the type array (this.field_types) in fieds.js
																'text' =>  esc_html__( 'Text', 'woocommerce-product-fields-checkout' ),
																'textarea'=>  esc_html__( 'Textarea', 'woocommerce-product-fields-checkout' ), 
																'email'=>  esc_html__( 'Email', 'woocommerce-product-fields-checkout' ), 
																'number' =>  esc_html__( 'Number', 'woocommerce-product-fields-checkout' ), 
																'checkbox'=>  esc_html__( 'Checkbox', 'woocommerce-product-fields-checkout' ), 
																'radio'=>  esc_html__( 'Radio', 'woocommerce-product-fields-checkout' ), 
																'select' =>  esc_html__( 'Select', 'woocommerce-product-fields-checkout' ),
																'date' =>  esc_html__( 'Date', 'woocommerce-product-fields-checkout' ), 
																'time' =>  esc_html__( 'Time', 'woocommerce-product-fields-checkout' ), 
																'country_state'=>  esc_html__( 'Country & state', 'woocommerce-product-fields-checkout' ),
																'html'=>  esc_html__( 'HTML', 'woocommerce-product-fields-checkout' )
															)
													),
							'field_data' => $field_data,
							'billing_fields' => $wcpfc_field_model->get_checkout_fields(),
							'shipping_fields' => $wcpfc_field_model->get_checkout_fields('shipping'),
							'payment_methods' => $wcpfc_payment_method_model->get_payment_methods(),
						);
		$js_country_data = array(
			'country_list' => json_encode($countries)
		);
		
		//1
		wp_register_script( 'admin-template-field', WCPFC_PLUGIN_PATH.'/js/admin/template/field.js', array('jquery'));	
		wp_register_script( 'admin-template-condition', WCPFC_PLUGIN_PATH.'/js/admin/template/logic-options.js', array('jquery'));	
		wp_register_script( 'admin-fields-configurator-page', WCPFC_PLUGIN_PATH.'/js/admin/product-fields-configurator.js', array('jquery'));
		wp_register_script( 'admin-template-common', WCPFC_PLUGIN_PATH.'/js/admin/template/type/options/common.js', array('jquery'));
		wp_register_script( 'admin-template-html-options', WCPFC_PLUGIN_PATH.'/js/admin/template/type/options/html.js', array('jquery'));
		wp_register_script( 'admin-template-text', WCPFC_PLUGIN_PATH.'/js/admin/template/type/text.js', array('jquery'));
		wp_register_script( 'admin-template-checkbox', WCPFC_PLUGIN_PATH.'/js/admin/template/type/checkbox.js', array('jquery'));
		wp_register_script( 'admin-template-textarea', WCPFC_PLUGIN_PATH.'/js/admin/template/type/textarea.js', array('jquery'));
		wp_register_script( 'admin-template-number', WCPFC_PLUGIN_PATH.'/js/admin/template/type/number.js', array('jquery'));
		wp_register_script( 'admin-template-country', WCPFC_PLUGIN_PATH.'/js/admin/template/type/country_state.js', array('jquery'));
		wp_register_script( 'admin-template-radio', WCPFC_PLUGIN_PATH.'/js/admin/template/type/radio.js', array('jquery'));
		wp_register_script( 'admin-template-select', WCPFC_PLUGIN_PATH.'/js/admin/template/type/select.js', array('jquery'));
		wp_register_script( 'admin-template-date', WCPFC_PLUGIN_PATH.'/js/admin/template/type/date.js', array('jquery'));
		wp_register_script( 'admin-template-time', WCPFC_PLUGIN_PATH.'/js/admin/template/type/time.js', array('jquery'));
		wp_register_script( 'admin-template-html', WCPFC_PLUGIN_PATH.'/js/admin/template/type/html.js', array('jquery'));
		wp_register_script( 'admin-template-logic-option-cumulative', WCPFC_PLUGIN_PATH.'/js/admin/template/logic-option/cumulative.js', array('jquery'));
		wp_register_script( 'admin-template-logic-option-country-state', WCPFC_PLUGIN_PATH.'/js/admin/template/logic-option/country_state.js', array('jquery'));
		wp_register_script( 'admin-error-manager', WCPFC_PLUGIN_PATH.'/js/com/error-manager.js', array('jquery'));	
		
		//2
		wp_localize_script( 'admin-fields-configurator-page', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-field', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-condition', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-text', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-common', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-html-options', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-checkbox', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-textarea', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-number', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-country', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-radio', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-select', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-date', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-html', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-logic-option-text', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-logic-option-country-state', 'wcpfc_settings', $js_data );
		wp_localize_script( 'admin-template-logic-option-country-state', 'wcpfc_country_data', $js_country_data );
		
		//3
		wp_enqueue_script( 'babel', WCPFC_PLUGIN_PATH.'/js/vendor/babel/babel.min.js', array('jquery'));
		wp_deregister_script('react');
		wp_deregister_script('react-dom');
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
		wp_enqueue_script( 'picker', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/picker.js', array('jquery'));
		wp_enqueue_script( 'picker-date', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/picker.date.js', array('jquery'));
		wp_enqueue_script( 'picker-time', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/picker.time.js', array('jquery'));
		wp_enqueue_script( 'picker-legacy', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/legacy.js', array('jquery'));
		//Load picker localization
		if(wcpfc_url_exists(WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/translations/'.$wcpfc_wpml_model->get_current_language().'.js'))
			wp_enqueue_script('picker-localization', WCPFC_PLUGIN_PATH.'/js/vendor/pickdate/translations/'.$wcpfc_wpml_model->get_current_language().'.js');
		wp_enqueue_script( 'admin-fields-configurator-page-misc', WCPFC_PLUGIN_PATH.'/js/admin/product-fields-configurator-misc.js', array('jquery'));
		wp_deregister_script('select2');
		wp_enqueue_script( 'admin-select2', WCPFC_PLUGIN_PATH.'/js/vendor/select2/select2.full.min.js', array('jquery'));
		wp_enqueue_script( 'selectWoo');
		wp_enqueue_script( 'admin-fields-configurator-page' );
		wp_enqueue_script( 'admin-template-field' );
		wp_enqueue_script( 'admin-template-text' );
		wp_enqueue_script( 'admin-template-condition' );
		wp_enqueue_script( 'admin-template-common' );
		wp_enqueue_script( 'admin-template-html-options' );
		wp_enqueue_script( 'admin-template-textarea' );
		wp_enqueue_script( 'admin-template-checkbox' );
		wp_enqueue_script( 'admin-template-number' );
		wp_enqueue_script( 'admin-template-country' );
		wp_enqueue_script( 'admin-template-radio' );
		wp_enqueue_script( 'admin-template-select' );
		wp_enqueue_script( 'admin-template-date' );
		wp_enqueue_script( 'admin-template-time' );
		wp_enqueue_script( 'admin-template-html' );
		wp_enqueue_script( 'admin-template-logic-option-cumulative' );
		wp_enqueue_script( 'admin-template-logic-option-country-state' );
		wp_enqueue_script( 'admin-error-manager' );
		
		//4
		$this->jsx_handlers[] = 'admin-fields-configurator-page';
		$this->jsx_handlers[] = 'admin-template-field';
		$this->jsx_handlers[] = 'admin-template-condition';
		$this->jsx_handlers[] = 'admin-template-text';
		$this->jsx_handlers[] = 'admin-template-common';
		$this->jsx_handlers[] = 'admin-template-html-options';
		$this->jsx_handlers[] = 'admin-template-textarea';
		$this->jsx_handlers[] = 'admin-template-checkbox';
		$this->jsx_handlers[] = 'admin-template-number';
		$this->jsx_handlers[] = 'admin-template-country';
		$this->jsx_handlers[] = 'admin-template-radio';
		$this->jsx_handlers[] = 'admin-template-select';
		$this->jsx_handlers[] = 'admin-template-date';
		$this->jsx_handlers[] = 'admin-template-time';
		$this->jsx_handlers[] = 'admin-template-html'; 
		$this->jsx_handlers[] = 'admin-template-logic-option-cumulative';
		$this->jsx_handlers[] = 'admin-template-logic-option-country-state';
		$this->jsx_handlers[] = 'admin-error-manager';
		
		
		wp_enqueue_style( 'admin-datetime-picker', WCPFC_PLUGIN_PATH.'/css/vendor/pickdate/themes/classic.css');
		wp_enqueue_style( 'admin-date-picker', WCPFC_PLUGIN_PATH.'/css/vendor/pickdate/themes/classic.date.css');
		wp_enqueue_style( 'admin-time-picker', WCPFC_PLUGIN_PATH.'/css/vendor/pickdate/themes/classic.time.css'); 
		wp_enqueue_style( 'admin-select2', WCPFC_PLUGIN_PATH.'/css/vendor/select2/select2.min.css'); 
		wp_enqueue_style( 'admin-fields-configurator-page', WCPFC_PLUGIN_PATH.'/css/admin-fields-configurator-page.css');
		
		?>
			<div id="product_fields_container">
				<span class="loading_text"><?php esc_html_e( 'Loading, please wait...', 'woocommerce-product-fields-checkout' )  ?></span>
			</div>
		<?php
	}
	
	function render_save_button_meta_box()
	{
		$screen = get_current_screen();
		if(!$screen || $screen->base != "toplevel_page_woocommerce-product-fields-checkout")
			return;
		submit_button( esc_html__( 'Save', 'woocommerce-product-fields-checkout' ),
						'primary',
						'submit'
					);
	}
}
?>