"use strict";
class LogicCondition extends React.Component
{
	constructor(props)
	{
		super(props);
		this.last_children_key = 0;
		this.logic_rules_number = 0;
		this._ismounted = false;
		this.state = 
		{
			children: [] //child structure: see below on default stetting loading for structure
		};
		this.allFieldsData;
		this.onTypeSelection = this.onTypeSelection.bind(this);
		this.addNewLogicCondition = this.addNewLogicCondition.bind(this);
		this.removeLogicCondition = this.removeLogicCondition.bind(this);
		this.refreshUI = this.refreshUI.bind(this);
		this.defaultSettings = this.props.loadFromSettings ? wcpfc_settings.field_data[this.props.parent_id].logic_condition[this.props.keyToLoad] : [];
		this.display_policy = this.props.display_policy;
		this.position = this.props.parent_position; 
		this.parent_id = this.props.parent_id; 
		
		document.addEventListener('wcpfcRefreshLogicOptions', this.refreshUI);
		
		//init
		//settings loading
		if(this.props.loadFromSettings)
		{
			const mySelf = this;
			const settings_key =  Object.keys(this.defaultSettings);
			const type_selector_data =  this.props.getAllFieldsMeta(true);
			settings_key.forEach(function(key, index)
			{
				mySelf.state.children.push({type: mySelf.defaultSettings[key].type, 
											parent_id: mySelf.defaultSettings[key].parent_id, 
											parent_unique_id: mySelf.defaultSettings[key].field_unique_id, 
											defaultValue: mySelf.defaultSettings[key].parent_id,
											display_policy: type_selector_data[mySelf.defaultSettings[key].parent_id].display_policy,
											position: type_selector_data[mySelf.defaultSettings[key].parent_id].position,
											id: mySelf.last_children_key++ });
			});
		}
	}
	getDefaultValueByName(setting_name)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options
		return settings.hasOwnProperty(setting_name) ? settings[setting_name] : "";
	}
	itemIsNotARelative(mySelf, element_to_compare)
	{
		return fields_data[index_to_check].position != 'checkout_native_field' &&
					(mySelf.display_policy != fields_data[index_to_check].display_policy ||
				     mySelf.position != fields_data[index_to_check].position);
	}
	itemCanBeUsedForSelection(mySelf, element_to_compare)
	{
		return element_to_compare.position === 'checkout_native_field' ||
			    (element_to_compare.id != mySelf.parent_id  && 
				 element_to_compare.display_policy == mySelf.display_policy &&
				 element_to_compare.position == mySelf.position);	
	}
	getDefaultChildData(type_selector_data)
	{
		const keys = Object.keys(type_selector_data);
		let first_key = null;
		const mySelf = this;
		
		for(let key in type_selector_data)
		{
			//
			if(mySelf.itemCanBeUsedForSelection( mySelf ,type_selector_data[key]))
			{
				first_key = key;
				break;
			}
		}
		
		if(first_key == null)
			return {};
		
		return {type: type_selector_data[first_key].type, 
				parent_id: type_selector_data[first_key].id, 
				parent_unique_id: type_selector_data[first_key].unique_id, 
				display_policy: type_selector_data[first_key].display_policy,
				position: type_selector_data[first_key].position,				
				defaultValue:"", 
				id: this.last_children_key++ };
	}
	onTypeSelection(event, index_to_update)
	{
		event.preventDefault();
		const new_value =  event.currentTarget[event.currentTarget.selectedIndex].getAttribute('data-type');
		const parent_unique_id = event.currentTarget[event.currentTarget.selectedIndex].getAttribute('data-parent-unique-id');
		const display_policy = event.currentTarget[event.currentTarget.selectedIndex].getAttribute('data-display-policy');
		const position = event.currentTarget[event.currentTarget.selectedIndex].getAttribute('data-position');
		const parent_id = event.currentTarget.value;
		this.setState((prevState) =>
		{
			prevState.children.forEach(function(elem, index)
			{
				if(elem.id == index_to_update)
				{
					prevState.children[index].type = new_value;
					prevState.children[index].parent_id = parent_id;
					prevState.children[index].parent_unique_id = parent_unique_id;
					prevState.children[index].display_policy = display_policy;
					prevState.children[index].position = position;
				}
			});
			
			return{
				children : prevState.children
			}
		});
	}
	addNewLogicCondition(event)
	{
		event.preventDefault();
		
	    const type_selector_data =  this.props.getAllFieldsMeta(false);
		
		if(type_selector_data.length > 0 )
		{
			
		
			this.setState((prevState) => ({
				children: prevState.children.concat(this.getDefaultChildData(type_selector_data))
			}));
		}
	}
	removeLogicCondition(event, index_to_remove)
	{
		if(event != null)
			event.preventDefault();
		this.setState((prevState) => 
		{
			let new_children =  []; 
			prevState.children.forEach(function(elem, index)
			{
				if(elem.id != index_to_remove)
				{
					new_children.push(elem);
				}
			}); 
			 
			if(new_children.length == 0) 
				this.props.unmountMe(this.props.id);
		
			return{
				children: new_children
			}
		});
	}
	//invoked when a field changes its type
	refreshUI(event)
	{
		// IMPORTANT: event.field_id replaces this.props.parent_id
		const fields_data =  this.props.getAllFieldsMeta(true); 
		
		//method invoked by field.js -> "refreshChildrenUI()"
		if(typeof event != 'undefined')
		{
			if(typeof fields_data[event.field_id] == 'undefined')
				return
			
			//Is still needed?
			this.display_policy = fields_data[event.field_id].display_policy;
			this.position  = fields_data[event.field_id].position;
		}
		
		const mySelf = this;
		
		if(!this._ismounted)
			return;
		
		
		this.setState((prevState,props) =>
		{
			
			let index_to_remove = [];
			let new_children = [];
			prevState.children.forEach(function(elem, index)
			{
				const index_to_check = prevState.children[index].parent_id;
				
				
				//in case the element is no longer associable to the current (due to its position or display policy has been changed), the associated child is removed
				if( !fields_data.hasOwnProperty(index_to_check) || !mySelf.itemCanBeUsedForSelection(mySelf, fields_data[index_to_check]) ) 
					{
						index_to_remove.push(index);
					}
				else 
				{
					prevState.children[index].type = fields_data[index_to_check].type;
					prevState.children[index].display_policy = fields_data[index_to_check].display_policy;
					prevState.children[index].position = fields_data[index_to_check].position;
					
					new_children.push(prevState.children[index]);
				}
				
			});
			
			
			return{
				children : new_children
			}
		});
	}
	initDatePicker()
	{
		jQuery('.wcpfc_logic_date_selector').pickadate({
			format: 'yyyy-mm-dd',
			formatSubmit: 'yyyy-mm-dd',
			selectMonths: true,
			selectYears: true,
			hiddenSuffix: ''
			
		});
	}
	initTimePicker()
	{
		jQuery('.wcpfc_logic_time_selector').pickatime({
			format: 'HH:i',
			formatSubmit: 'HH:i',
			hiddenSuffix: ''
			
		});
	}
	renderLogicOptionsByType(type, field_id)
	{
		let output = [];
		this.allFieldsData = this.props.getAllFieldsMeta(true);
		
		switch(type)
		{
			case 'email' :
			case 'text' : output = <LogicOptionText logic_field_id={this.props.id} 
													id={field_id} 
													parent_id={this.props.parent_id} 
													loadFromSettings={this.props.loadFromSettings}
													keyToLoad={this.props.keyToLoad} />
				break;
			case 'textarea' : output = <LogicOptionTextarea logic_field_id={this.props.id} 
															id={field_id} 
															parent_id={this.props.parent_id} 
															loadFromSettings={this.props.loadFromSettings} 
															keyToLoad={this.props.keyToLoad} />
				break;
			case 'number' : output = <LogicOptionNumber logic_field_id={this.props.id} 
														id={field_id} 
														parent_id={this.props.parent_id} 
														loadFromSettings={this.props.loadFromSettings} 
														keyToLoad={this.props.keyToLoad}
														/>
				break;
			case 'select' : output = <LogicOptionSelect logic_field_id={this.props.id} 
														id={field_id}
														parent_id={this.props.parent_id} 
														is_multiple_select={this.allFieldsData[this.state.children[field_id].parent_id].is_multiple_value} 
														loadFromSettings={this.props.loadFromSettings}
														keyToLoad={this.props.keyToLoad}
														/>
				break;
			case 'checkbox' : output = <LogicOptionCheckbox logic_field_id={this.props.id} 
															id={field_id} 
															parent_id={this.props.parent_id} 
															loadFromSettings={this.props.loadFromSettings} 
															keyToLoad={this.props.keyToLoad}
															/>
				break;
			case 'date' : output = <LogicOptionDate logic_field_id={this.props.id} 
													id={field_id} 
													parent_id={this.props.parent_id} 
													loadFromSettings={this.props.loadFromSettings} 
													keyToLoad={this.props.keyToLoad}
													/>					  
				break;
			case 'time' : output = <LogicOptionTime logic_field_id={this.props.id} 
													id={field_id} 
													parent_id={this.props.parent_id} 
													loadFromSettings={this.props.loadFromSettings} 
													keyToLoad={this.props.keyToLoad}
													/>					  
				break;
			case 'country_state' : output = <LogicOptionCountryState logic_field_id={this.props.id} 
																	 id={field_id} 
																	 parent_id={this.props.parent_id} 
																	 loadFromSettings={this.props.loadFromSettings} 
																	 keyToLoad={this.props.keyToLoad}
																	 />			
			break;
			case 'payment_method' : output = <LogicOptionCheckbox logic_field_id={this.props.id} 
																	 id={field_id} 
																	 parent_id={this.props.parent_id} 
																	 loadFromSettings={this.props.loadFromSettings} 
																	 keyToLoad={this.props.keyToLoad}
																	 />					  
			break;
		}
		return output;
	}
	renderFieldsSelectorOptions(/* defaultValue */)
	{
		const type_selector_data = this.props.getAllFieldsMeta(false); 
		
		
		const output = type_selector_data.reduce((result, field_meta) => 
		{
			if(  this.itemCanBeUsedForSelection(this, field_meta))
			{
				result.push(<option key={field_meta.id} 
								value={field_meta.id} 
								data-type={field_meta.type} 
								data-parent-unique-id={field_meta.unique_id} 
								data-display-policy={field_meta.display_policy}
								data-position={field_meta.position}>{field_meta.name}</option>);
			}
							
			return result;
		}, []);
		
		
		return output;
	}
	renderLogicRules()
	{
		
				
		let output = [];
		const mySelf = this;
		const selector_options = mySelf.renderFieldsSelectorOptions();
		
		this.state.children.forEach(function(elem)
		{
			if(selector_options.length > 0 )
			{
				output.push(
							<div className="logic_rule_container" key={elem.id} >
								<input type="hidden" name={"wcpfc_data["+mySelf.props.parent_id+"][logic_condition]["+mySelf.props.id+"]["+elem.id+"][field_unique_id]"} value={elem.parent_unique_id} />
								<input type="hidden" name={"wcpfc_data["+mySelf.props.parent_id+"][logic_condition]["+mySelf.props.id+"]["+elem.id+"][type]"} value={elem.type} />
							
								<select onChange={(event) => mySelf.onTypeSelection(event, elem.id)} 
										name={"wcpfc_data["+mySelf.props.parent_id+"][logic_condition]["+mySelf.props.id+"]["+elem.id+"][parent_id]"}
										defaultValue={elem.defaultValue} >
									{selector_options}
								</select>
								<div className="logic_options_container">
									{mySelf.renderLogicOptionsByType(elem.type, elem.id)}
								</div>
								<div className="logic_remove_container">
									<button className="button inline_button remove_logic_option_button" onClick = {(event) => mySelf.removeLogicCondition(event, elem.id)}>{wcpfc_settings.remove_text}</button>
								</div>
							</div>
						);
			}
		});
		
		this.logic_rules_number = output.length;
		return output;
	}
	componentWillMount()
	{
		const type_selector_data = this.props.getAllFieldsMeta(false); 
		//settings loading
		if(type_selector_data.length > 0 && !this.props.loadFromSettings)
			this.state.children[0] = this.getDefaultChildData(type_selector_data);
	}
	componentDidMount()
	{
		if(this.logic_rules_number == 0)
			this.props.unmountMe(this.props.id);
		else{
			this._ismounted = true;
			this.initDatePicker();
			this.initTimePicker();
		}
	}
	componentWillUnmount() 
	{
		document.removeEventListener('wcpfcRefreshLogicOptions', this.refreshUI);
		this._ismounted = false;
	}
	componentDidUpdate()
	{
		if(this.logic_rules_number == 0)
			this.props.unmountMe(this.props.id);
		else{
			this.initDatePicker();
			this.initTimePicker();
		}
	}
	render()
	{
		
		return (
			<div className="and_logic_rules_container">
				{this.renderLogicRules()}
				<button className="button logic_or_button" onClick = {this.addNewLogicCondition}>{wcpfc_settings.or_text}</button>
			</div>	
		);
	}
}