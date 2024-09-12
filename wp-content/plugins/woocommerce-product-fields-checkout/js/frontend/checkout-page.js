"use strict";
class WCPFC_ProductFieldsManager extends React.Component
{
	constructor(pros)
	{
		super(pros);
		this.state = 
		{
			field_values : {}
		}
		
		this.onFieldValueChange = this.onFieldValueChange.bind(this);
		this.onFieldStateValueChange = this.onFieldStateValueChange.bind(this);
		this.onFieldDateTimeValueChange = this.onFieldDateTimeValueChange.bind(this);
		this.onNativeFieldChange = this.onNativeFieldChange.bind(this);
	}
	initListenToNativeFieldsChange()
	{
		let checkout_native_fields_types = ['billing_fields', 'shipping_fields', 'payment_methods'];
		for(let type = 0; type < checkout_native_fields_types.length; type++)
			for(let field_id in wcpfc_conf_data[checkout_native_fields_types[type]])
				{
					if (!wcpfc_conf_data[checkout_native_fields_types[type]].hasOwnProperty(field_id)) continue;
					
					let current_elem = wcpfc_conf_data[checkout_native_fields_types[type]][field_id];
					
					jQuery(document).on("change", "#"+field_id, this.onNativeFieldChange); 
				}
				
		//update state
		//billing/shipping fields
		let fake_field_obj;
		for(let type = 0; type < checkout_native_fields_types.length; type++)
			for(let field_id in wcpfc_conf_data[checkout_native_fields_types[type]])
				{
					if(document.getElementById(field_id) == null)
						continue;
					fake_field_obj = {currentTarget: document.getElementById(field_id)};
					this.onNativeFieldChange(fake_field_obj)
				}
		//payment methods
		jQuery("input[name='payment_method']:checked").trigger("change"); //payment_method
	}
	//On value change
	onNativeFieldChange(event)
	{
		
		const value = event.currentTarget.type == 'radio' && !event.currentTarget.checked  ? "none" : event.currentTarget.value;
		let field_id = event.currentTarget.id;
		let field_type = "main";
		if(field_id == 'billing_state')
		{
			field_id = "billing_country";
			field_type =  "state";
		}
		else if(field_id == 'shipping_state')
		{
			field_id = "shipping_country";
			field_type =  "state";
		}
		field_id = field_id.startsWith("payment_method") ? "payment_method" : field_id;
		
		
		this.setState((prevState, props)=>
		{
			let old_state = prevState.field_values;
			old_state[field_id] = this.setStateFieldValueOnStateObject(old_state, field_id, [0,0,0], value, field_type);
			return {field_values: old_state};
			
		});
	}
	onFieldDateTimeValueChange(context, selector)
	{
		
		
		const field_html_id = selector.id;
		const selectors = document.getElementsByName(selector.id+"[value]");
		const value = selectors[1].value;  //0: select, 1:hidden input
		const ids_data = this.getFieldIdsFromHtmlId(field_html_id);
		const field_id = ids_data.field_ids[1];
		
		this.setState((prevState, props)=>
		{
			let old_state = prevState.field_values;
			old_state[field_id] = this.setStateFieldValueOnStateObject(old_state, field_id, ids_data.groupd_ids, value, "main");
			return {field_values: old_state};
			
		}  );
	}
	onFieldStateValueChange(event)
	{
		const field_html_id = event.currentTarget.id;
						//checkbox
		const value = event.currentTarget.value;
		const ids_data = this.getFieldIdsFromHtmlId(field_html_id);
		const field_id = ids_data.field_ids[1];
		
		this.setState((prevState, props) =>
		{
			let old_state = prevState.field_values;
			old_state[field_id] = this.setStateFieldValueOnStateObject(old_state, field_id, ids_data.groupd_ids, value, "state");
			return {field_values: old_state};
			
		});
	}
	onFieldValueChange(event)
	{
		const field_html_id = event.currentTarget.id;
						//checkbox
		const value = event.currentTarget.type == 'checkbox' ? event.currentTarget.checked : jQuery(event.currentTarget).val() /* event.currentTarget.value */;
		const ids_data = this.getFieldIdsFromHtmlId(field_html_id);
		let field_id = ids_data.field_ids[1];
		//
		this.setState((prevState, props) =>
		{
			let old_state = prevState.field_values;
			old_state[field_id] = this.setStateFieldValueOnStateObject(old_state, field_id, ids_data.groupd_ids, value, "main");

			return {field_values: old_state};
			
		});
		
		
	}
	getFieldIdsFromHtmlId(field_id)
	{
		const field_ids = field_id.split("_"); //order_6xvhv24a5l_1-0-0 ---> [0]: order, [1]: 6xvhv24a5l, [2]: 1-0-0;
		const groupd_ids = field_ids[2].split("-"); // 1-0-0 ---> [0]: group_id, [1]: field_id, [2]: sub_group_id;
		
		return {field_ids: field_ids , groupd_ids:groupd_ids}
	}
	//End value change
	//State value get and setter
	setStateFieldValueOnStateObject(state_obj, field_id, group_ids, value, value_type)
	{
		
		let field_data = !state_obj.hasOwnProperty(field_id) ?  {} : state_obj[field_id];
		
		if(!field_data.hasOwnProperty(group_ids[0]))
			field_data[group_ids[0]] = {};
		if(!field_data[group_ids[0]].hasOwnProperty(group_ids[2]))
		{
			field_data[group_ids[0]][group_ids[2]] = {};
			field_data[group_ids[0]][group_ids[2]][value_type] = "";
		}
		
		field_data[group_ids[0]][group_ids[2]][value_type] = value;
		
		return field_data;	
	}
	getFieldValueFromStateObject(field_id, group_id, sub_group_id)
	{
		//Special case: native fields
		
		if(field_id.startsWith("billing_") || field_id.startsWith("shipping_") || field_id.startsWith("payment_method"))
		{
			group_id = sub_group_id = 0;
			
		}
		if(field_id.startsWith("payment_method"))
			field_id = "payment_method";
		
		if(!this.state.field_values.hasOwnProperty(field_id) || 
			!this.state.field_values[field_id].hasOwnProperty(group_id) || 
			!this.state.field_values[field_id][group_id].hasOwnProperty(sub_group_id))
			return "no_data_for_field";
		
		let result = this.state.field_values[field_id][group_id][sub_group_id].main != null ? this.state.field_values[field_id][group_id][sub_group_id] : "no_data_for_field";
		return result;
	}
	removeFieldValueFromStateObject(field_data, ids)
	{
		// ids ---> [0]: group_id, [1]: field_id, [2]: sub_group_id;
		const group_id = ids[0];
		const sub_group_id = ids[2];
		const field_unique_id = field_data.id;
		
		
		if(this.state.field_values.hasOwnProperty(field_unique_id) && this.state.field_values[field_unique_id].hasOwnProperty(group_id))
				delete this.state.field_values[field_unique_id][group_id][sub_group_id];
	}
	//End value get and setter
	
	fieldCanBeRendered(logic_data, id_array)
	{
		const logic_conditions_array = logic_data.logic_condition;
		if(logic_conditions_array == undefined)
			return true;
		
		
		//let can_be_rendered = true;
		const filed_id_prefix = "order_";
		const groupd_id = id_array.join("-");
		
		let and_result = true;
		let or_result = false;
		for(var index in logic_conditions_array) //and
		{
			for(var index2 in logic_conditions_array[index]) //or
			{
				const current_logic_condition = logic_conditions_array[index][index2];
				const compare_type = current_logic_condition.type;
				const field_unique_id = current_logic_condition.field_unique_id;
				const parent_id = current_logic_condition.parent_id;
				//No need
				const field_data = wcpfc_conf_data.field_data[parent_id] //double check if field_data[id] == field_unique_id ??
				
				//3. get field value by id (from this.state.field_values)
				const field_value = this.getFieldValueFromStateObject(current_logic_condition.field_unique_id, id_array[0], id_array[2] );
				
				if(field_value != "no_data_for_field")
				{
					//4. compare and apply and/or concat logic
					or_result = or_result || wcpfc_compare_value_by_field_type(field_value, current_logic_condition);
				}
				else
					or_result = false || or_result;
			}
			and_result = and_result && or_result;	
			or_result = false;
		}		

		//If field cannot be rendered, remove its data from the state obj
		if(!and_result)
			this.removeFieldValueFromStateObject(logic_data, id_array );
		
		return and_result;
	}
	fieldsToDisplay()
	{
		const cart_item_keys = Object.keys(wcpfc_field_data);
		let product_fields = [];
		let one_time_fields = [];
		let	one_time_fields_temp = [];
		const mySelf = this;
		//one time filed
		
		const one_time_fields_id = Object.keys(wcpfc_field_data['one_time_field']);
		one_time_fields_id.forEach(function(field_id, index)
		{
			const current_one_time_field_data = wcpfc_field_data['one_time_field'][field_id];
			const indexes = ["onetimefield",index,0];
			
			if(current_one_time_field_data['options']['position'] == mySelf.props.position && mySelf.fieldCanBeRendered(current_one_time_field_data, indexes))
			{
				one_time_fields.push(<WCPFC_FieldRendered 
										key = {indexes.join("-")}
										cart_key = {"one_time_field"}
										form_type = {mySelf.props.form_type}
										indexes_array={indexes} 
										field_data={current_one_time_field_data}
										onFieldValueChange = {mySelf.onFieldValueChange}
										onFieldStateValueChange = {mySelf.onFieldStateValueChange}	
										onFieldDateTimeValueChange = {mySelf.onFieldDateTimeValueChange}
										/>);
			}
		});
		//after all fields have been created, check if they can be rendered
			
		//product field 
		cart_item_keys.forEach(function(cart_key, index)
		{
			if(cart_key == 'one_time_field')
				return;
		
			const current_cart_item = wcpfc_field_data[cart_key];
			
			let exists_at_least_one_field_to_show = current_cart_item.per_cart_quantity.field_data.length > 0;		
			let temp_product_fields = []; 
			let final_products_fields = [];
			let temp_final_products_fields = [];
			//let time_to_render_group = current_cart_item.time_to_render;
			let time_to_render_group = current_cart_item.cart_quantity;
			let create_container = false;
			let header_field_can_be_displayed = false;
					
			//Fields per item 
			current_cart_item.per_item.field_data.forEach(function(single_field_data, index2)
				{
					if(single_field_data['options']['position'] == mySelf.props.position)
					{
						const i = 1;
						const indexes = [index, index2, i];
						header_field_can_be_displayed = true;
						if(mySelf.fieldCanBeRendered(single_field_data, indexes))
							final_products_fields.push(<WCPFC_FieldRendered 
															key = {indexes.join("-")}
															cart_key = {cart_key}
															form_type = {mySelf.props.form_type}
															indexes_array={indexes} 
															field_data={single_field_data} 
															onFieldValueChange = {mySelf.onFieldValueChange}
															onFieldStateValueChange = {mySelf.onFieldStateValueChange}	
															onFieldDateTimeValueChange = {mySelf.onFieldDateTimeValueChange} />);
						
					}
				});
				
			//Fields per cart quantity
			while(time_to_render_group > 0) 
			{
				if(current_cart_item.per_cart_quantity.field_data.length > 1)
				{
					create_container = true;
				}
				current_cart_item.per_cart_quantity.field_data.forEach(function(single_field_data, index2)
				{
									
					if(single_field_data['options']['position'] == mySelf.props.position)
					{
						
						const i = time_to_render_group;
						const indexes = [index, index2, i];
						header_field_can_be_displayed = true;
						if(mySelf.fieldCanBeRendered(single_field_data, indexes))
							temp_final_products_fields.push(<WCPFC_FieldRendered 
															key = {indexes.join("-")}
															cart_key = {cart_key}
															form_type = {mySelf.props.form_type}
															indexes_array={indexes} 
															field_data={single_field_data} 
															onFieldValueChange = {mySelf.onFieldValueChange}
															onFieldStateValueChange = {mySelf.onFieldStateValueChange}	
															onFieldDateTimeValueChange = {mySelf.onFieldDateTimeValueChange} />);
						
					}
				});
				time_to_render_group--;
				if(create_container)
					final_products_fields.push(<div className={"wcpfc_group_container"}>{temp_final_products_fields}</div>);
				else
					final_products_fields.push(temp_final_products_fields);
				
				temp_final_products_fields = [];
			}
			//after all fields have been created, check if they can be rendered
			
			
			//if(final_products_fields.length > 0)
			if(header_field_can_be_displayed > 0)
				final_products_fields.unshift(<h3 key={current_cart_item.cart_key+"-title"} priority="110">{current_cart_item.cart_item_name}</h3>);
			
			product_fields = product_fields.concat(final_products_fields);
		});
					
		return one_time_fields.concat(product_fields);
	}
	componentDidUpdate()
	{
		
	}
	componentDidMount()
	{
		
		this.initListenToNativeFieldsChange();
	}
	componentWillMount()
	{
		
	}
	render()
	{
		const fields_to_show = this.fieldsToDisplay()
		return(<ErrorBoundary>{fields_to_show}</ErrorBoundary>);
	}
}


//Done to wait all the libraries are loaded	 
jQuery(document).ready(function () 
{
	setTimeout(function() 
	{
		jQuery("#place_order").fadeIn(500, function()
		{
			jQuery("#place_order").css('display','block'); 
			//For some reason on checkout_update, the button disappers if the hard coded css style is not removed
			jQuery('#wcpfc_place_order_css').remove();
		});
		
		if(document.getElementById('wcpfc_extra_product_fields_area_after_billing_form') !== null)
			ReactDOM.render(
			  <WCPFC_ProductFieldsManager position="after_billing_form" form_type="billing" />,
			  document.getElementById('wcpfc_extra_product_fields_area_after_billing_form')
			);
		
		if(document.getElementById('wcpfc_extra_product_fields_area_after_shipping_form') !== null)
			 ReactDOM.render(
			  <WCPFC_ProductFieldsManager position="after_shipping_form" form_type="shipping"/>,
			  document.getElementById('wcpfc_extra_product_fields_area_after_shipping_form')
			); 
	}, 2000);
});