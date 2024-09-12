"use strict";
class Field extends React.Component
{
	constructor(props)
	{
		super(props);
		this.field_types = ['text', 'textarea', 'email', 'number', 'select', 'checkbox', 'date', 'time', 'country_state', 'html'];	
		this.uniqueID = this.generateId();
		this.name = null;
		this.defaultValues = this.props.loadFromSettings ? wcpfc_settings.field_data[this.props.id] : false;
		this.currentOptionChildRef = null;
		this.isMultipleSelect = false;
		this.displayPolicy = "each_product";
		this.position = "after_billing_form";
		this.onTypeSelection = this.onTypeSelection.bind(this);
		this.setFieldName = this.setFieldName.bind(this);
		this.onNewConditionRule = this.onNewConditionRule.bind(this);
		this.removeLogicCondition = this.removeLogicCondition.bind(this);
		this.renderOptionContainer = this.renderOptionContainer.bind(this);
		this.setIsMultiple = this.setIsMultiple.bind(this);
		this.state = 
		{
			field_type: this.props.loadFromSettings ? this.defaultValues.type : this.field_types[0],
			logic_conditions: [],
			logic_condition_refs: []
		}
		
		//init
		//settings loading
		if(this.defaultValues !== false)
		{
			const lang_data = this.getLangData();
			
			this.uniqueID = this.defaultValues.id;
			this.displayPolicy = this.defaultValues.options.display_policy;
			this.position = this.defaultValues.options.position;
			this.name =  this.defaultValues.name[wcpfc_settings.default_lang];
		}
		
		this.onExpandButtonClick = this.onExpandButtonClick.bind(this);
		this.onNameFieldClick = this.onNameFieldClick.bind(this);
		this.setDisplayPolicy = this.setDisplayPolicy.bind(this);
		this.onPositionSelection = this.onPositionSelection.bind(this);
	} 
	generateId()
	{
		return Math.random().toString(36).slice(2);
	}
	getFieldsMeta()
	{
		return {name: this.name, 
				type: this.state.field_type, 
				 id: this.props.id, 
				 unique_id: this.uniqueID, 
				 is_multiple_value: this.isMultipleSelect,
				 display_policy:this.displayPolicy,
				 position: this.position };
	}
	notifyUIRefreshToLogicOptionManager()
	{
		const event = new CustomEvent('wcpfcRefreshLogicOptions');
		event.field_id = this.props.id;
		document.dispatchEvent(event);
	}
	//Force refreshing the UI of the Logic options containers
	refreshChildrenUI()
	{
		
		for(let i = 0; i < this.state.logic_condition_refs.length; i++)
			this.state.logic_condition_refs[i].current.refreshUI(); 
	}
	setDisplayPolicy(event)
	{
		this.displayPolicy = event.currentTarget.value;
		
		this.notifyUIRefreshToLogicOptionManager();
	}
	setFieldName(event)
	{
		this.name = event.currentTarget.value;
		this.notifyUIRefreshToLogicOptionManager();
		
		//Non used anymore, see the parent class. Is now used event system
		//this.props.refreshFieldsUI();
		
	}
	
	setIsMultiple(value)
	{
		this.isMultipleSelect = value;
		//Non used anymore, see the parent class. Is now used event system
		//this.props.refreshFieldsUI()
		
		this.notifyUIRefreshToLogicOptionManager();
	}
	isOptionAMultipleSelectField()
	{
		return this.state.field_type == 'select' && this.isMultipleSelect;
	}
	onNewConditionRule(event)
	{
		event.preventDefault();
		this.createNewConditionRule(false, 0);
	}
	createNewConditionRule(loadFromSettings, key_to_load)
	{
		const ref = React.createRef();
		
		this.setState((prevState, props) =>
		({
				//ToDo: manage the "remove logic condition" event
				logic_conditions: prevState.logic_conditions.concat(
															<div key = {prevState.logic_conditions.length + 1}>
																<h4 className="rule_title">{wcpfc_settings.condition_title_text+" "+(prevState.logic_conditions.length + 1)}</h4>
																<LogicCondition 
																   parent_id = {this.props.id} 
																   parent_unique_id = {this.uniqueID}
																   display_policy = {this.displayPolicy}
																   parent_position = {this.position}
																   unmountMe={(i) => this.removeLogicCondition (i) } 
																   key = {prevState.logic_conditions.length + 1}
																   id = {prevState.logic_conditions.length + 1}
																   ref = {ref}	
																   loadFromSettings = {loadFromSettings}
																   keyToLoad = {key_to_load}
																   getAllFieldsMeta = {(return_object) => this.props.getAllFieldsMeta(return_object)}
															   />
														   </div>),
				logic_condition_refs: prevState.logic_condition_refs.concat(ref) //No need anymore. Used event system instead of callbacks
		}));
	}
	onExpandButtonClick(event, body_index)
	{
		event.preventDefault();
		event.stopPropagation();
		jQuery('#field_body_'+body_index).toggleClass('body_collapsed');
	}
	onNameFieldClick(event)
	{
		event.preventDefault();
		event.stopPropagation();
	}
	removeLogicCondition(index_to_remove)
	{
		this.setState((prevState, props) =>
		{
			let new_logic_condition = [];
			let new_logic_condition_refs = [];
			
			prevState.logic_conditions.forEach(function(elem, index)
			{
				if(elem.key != index_to_remove)
				{
					new_logic_condition.push(elem);
					new_logic_condition_refs.push(prevState.logic_condition_refs[index]);
				}
			});
			
			return{
				logic_conditions :new_logic_condition,
				logic_condition_refs: new_logic_condition_refs //No need anymore. Used event system instead of callbacks
			}
		});
	}
	onTypeSelection(event)
	{
		const current_value = event.currentTarget.value;
		this.setState({field_type: current_value}, this.notifyUIRefreshToLogicOptionManager );
		
	}
	onPositionSelection(event)
	{
		this.position = event.currentTarget.value;
		this.notifyUIRefreshToLogicOptionManager();
	}
	renderTypeSelectorOptions()
	{
		const option_names = JSON.parse(wcpfc_settings.button_type_texts);
		
		
		const output = this.field_types.map((field_name, index) =>
		{
			return(
				<option key={index} value={this.field_types[index]}>{option_names[this.field_types[index]]}</option>
			)
		});  
		 
		return (output);
	}
	//settings loading
	getDefaultValueByName(field_name, options)
	{
		if(this.defaultValues === false && field_name != 'id')
			return "";
		
		let output = "";
		switch(field_name)
		{
			case 'id':  output = this.uniqueID;
						
				break;
			case 'name': 				
				output = this.defaultValues.name[options.locale];
				
				break;
			case 'type': output = this.defaultValues.type;
				break;			
				
			
			case 'product_id':
					if(this.defaultValues.options.hasOwnProperty('product_id'))
						output = this.defaultValues.options.product_id.map((elem, index) =>
						{
							return(<option key={elem.id} value={elem.id}>{elem.name}</option>)
						});
				break;
			case 'category_id':
					if(this.defaultValues.options.hasOwnProperty('category_id'))
					{
						
						output = this.defaultValues.options.category_id.map((elem, index) =>
						{
							return(<option key={elem.id} value={elem.id}>{elem.name}</option>)
						});
					}
				break;
			default: output = this.defaultValues.options[field_name];
				break;
		}
		
		return output;
	}
	sortLangArray(a, b)
	{
	  const genreA = a.id;
	  const genreB = b.id;

	  let comparison = 0;
	  if (genreA > genreB) 
	  {
		comparison = 1;
	  } 
	  else if (genreA < genreB) 
	  {
		comparison = -1;
	  }
	  return comparison;
	}
	renderProductSelectors()
	{
		let output = [];
		let already_selected = "";
		
		//products
		if(this.defaultValues!== false && this.defaultValues.options.hasOwnProperty('product_id'))
			this.defaultValues.options.product_id.forEach(function(elem,index)
			{
				already_selected += already_selected != "" ? ","+elem.id: elem.id;
			});			
		output.push(<label className="option_label" key={0}>{wcpfc_settings.select_product_text}</label>);
		output.push(<select className="wcpfc_products_select2" key={1} id={"wpcf_product_select2_"+this.props.id}
				name={"wcpfc_data["+this.props.id+"][options][product_id][]"}
				multiple="multiple"
				defaultValue={already_selected.split(",")}>			
				{this.getDefaultValueByName('product_id')}
				</select>);
			
		//categories
		if(this.defaultValues!== false && this.defaultValues.options.hasOwnProperty('category_id'))
			this.defaultValues.options.category_id.forEach(function(elem,index)
			{
				already_selected += already_selected != "" ? ","+elem.id: elem.id;
			});
		output.push(<label className="option_label" key={2}>{wcpfc_settings.select_categories_text}</label>);
		output.push(<select className="wcpfc_products_select2" key={3} id={"wpcf_category_select2_"+this.props.id}
				name={"wcpfc_data["+this.props.id+"][options][category_id][]"}
				multiple="multiple"
				defaultValue={already_selected.split(",")}> 				
				{this.getDefaultValueByName('category_id')}
				</select>);
			
		return output;
	}
	renderOptionContainer()
	{
		let output = [];
		const ref =  React.createRef();
		this.currentOptionChildRef = ref;
		const loadFromSettings = this.props.loadFromSettings && this.defaultValues.type ==  this.state.field_type;
		switch(this.state.field_type)
		{
			//NOTE: in every component is reported the "type" that is lately stored in the this.defaultValues.type
			case 'email': 
			case 'text': 
					output = <TextType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} fieldType = {this.state.field_type} />; 
				break;
			case 'textarea': 
					output = <TextAreaType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} />; 
				break;
			case 'checkbox': 
					output = <CheckBoxType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} />; 
				break;
			case 'number': 
					output = <NumberType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} />; 
				break;
			case 'country_state': 
					output = <CountryStateType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} />; 
				break;
			case 'select': 
					output = <SelectType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} refreshFieldsUI={this.props.refreshFieldsUI} setIsMultiple={(value) => this.setIsMultiple(value)} />; 
				break;
			case 'time': 
					output = <TimeType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} />; 
				break;
			case 'date': 
					output = <DateType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} />; 
				break;
			case 'html': 
					output = <HtmlType id={this.props.id} ref={ref} loadFromSettings={loadFromSettings} settingsId={this.props.id} />; 
				break;
		}
		
		return output;
	}
	getLangData()
	{
		let keys = []; 
		for (var key in wcpfc_settings.lang_data) 
		{
		  keys.push({id: wcpfc_settings.lang_data[key].id, code: wcpfc_settings.lang_data[key].code});
		}
		keys.sort(this.sortLangArray);
		
		return keys;
	}
	renderFieldNameInput()
	{
		const keys = this.getLangData();
		let output = [];
		
		
		for(let i = 0; i<keys.length; i++)
		{
			if(wcpfc_settings.lang_data[keys[i].code].country_flag_url != 'none')
				output.push(<img key={"flag_"+keys[i].code} className="wcpfc_flag" src={wcpfc_settings.lang_data[keys[i].code].country_flag_url} />);
			
			output.push( <input required="required" placeholder=""  
							key={keys[i].code}
							className="wcpfc_field_label" 
							defaultValue={this.getDefaultValueByName('name', {locale: wcpfc_settings.lang_data[keys[i].code].default_locale, is_default: i == 0} )} 
							placeholder = {wcpfc_settings.field_label_text}
							name={"wcpfc_data["+this.props.id+"][name]["+wcpfc_settings.lang_data[keys[i].code].default_locale+"]"} 
							onClick={this.onNameFieldClick}
							onChange={i == 0 ? this.setFieldName : null} type="text" /> );
		}

		return output;
	}
	componentWillMount()
	{
		
	}
	componentDidMount()
	{
		if(this.props.loadFromSettings)
		{
			
			//settings loading
			this.loadComponentsValuesFromSettings()
		}
		
		wcpfc_initProductSelector("#wpcf_product_select2_"+this.props.id); //product-fields-configurator-misc.js
		wcpfc_initCategorySelector("#wpcf_category_select2_"+this.props.id); //product-fields-configurator-misc.js
	}
	loadComponentsValuesFromSettings()
	{
		//init
		if(this.props.loadFromSettings !== false && wcpfc_settings.field_data[this.props.id].hasOwnProperty('logic_condition'))
		{
			const settings_key =  Object.keys(wcpfc_settings.field_data[this.props.id].logic_condition);
			const myself = this;
			settings_key.forEach(function(key, index)
			{
				myself.createNewConditionRule(true, key);
			});
		}
	}
	render()
	{
		
		return (
			<div className="field_component">
				{/* Header */ }
				<input type="hidden" name={"wcpfc_data["+this.props.id+"][id]"} defaultValue={this.getDefaultValueByName('id')} />
					
				<h2 className="ui-sortable-handle field_header" onClick={(event) => this.onExpandButtonClick(event, this.props.id)}>
					<span className="configure">
						<a href="#" onClick={(event) => this.onExpandButtonClick(event, this.props.id)} >
							<span className="dashicons dashicons-menu expand-field"></span>
						</a>
						<a href="#" onClick={(event) => this.props.onRemove(event, this.props.id)}>
							<span className="dashicons dashicons-trash remove-field" ></span>
						</a>
					</span>
					{this.renderFieldNameInput()}					
				</h2>				
				{/* Body */ }
				<div className={this.props.loadFromSettings ? "field_body body_collapsed": "field_body"} id={"field_body_"+this.props.id} >
					<span className="field_unique_id_text">
						(ID: {this.uniqueID})
					</span>
					<h3>{wcpfc_settings.products_selection_area_title_text}</h3>
					<p className="products_selection_area_description">{wcpfc_settings.products_selection_area_description_text}</p>
					<div className="options_container">
						
						
						
						<div className="block_container">
							<div className="inline_block_container">
								{this.renderProductSelectors()}
							</div>
							<div className="inline_block_container">
								<label className="option_label">{wcpfc_settings.display_category_policy_text}</label>	
								<select name={"wcpfc_data["+this.props.id+"][options][display_category_policy]"} defaultValue={this.getDefaultValueByName('display_category_policy')} >
									<option value="categories">{wcpfc_settings.categories_only_text}</option>
									<option value="categories_and_children">{wcpfc_settings.categories_and_children_text}</option>
								</select>
							</div>
						</div>
						<div className="inline_block_container">
							<label className="option_label display_policy_label">{wcpfc_settings.display_policy_text}</label>	
							<p className="display_policy_description">{wcpfc_settings.display_policy_description_text}</p>
							<select name={"wcpfc_data["+this.props.id+"][options][display_policy]"} defaultValue={this.getDefaultValueByName('display_policy')} onChange={this.setDisplayPolicy}>
								<option value="each_product">{wcpfc_settings.each_product_text}</option>
								<option value="each_product_quantity">{wcpfc_settings.each_product_quantity_text}</option>
								<option value="once">{wcpfc_settings.once_text}</option>
							</select>
						</div>
						<div className="inline_block_container">
							<label className="option_label">{wcpfc_settings.field_position_text}</label>
							<select onChange={this.onPositionSelection} defaultValue={this.getDefaultValueByName('position')} name={"wcpfc_data["+this.props.id+"][options][position]"}>
								<option value="after_billing_form">{wcpfc_settings.after_billing_form_text}</option>
								<option value="after_shipping_form">{wcpfc_settings.after_shipping_form_text}</option>
							</select>
						</div>					
						
					</div>	
					
					<div className="contaners">	
						<h3 className="container_title">{wcpfc_settings.option_area_title_text}</h3>
						<div className="options_container">
							<div className="block_container">
								<label className="option_label">{wcpfc_settings.type_tex}</label>
								<select onChange={this.onTypeSelection} defaultValue={this.getDefaultValueByName('type')} name={"wcpfc_data["+this.props.id+"][options][type]"}>
									{this.renderTypeSelectorOptions()}
								</select>
							</div>
						
							{this.renderOptionContainer()}
						</div>	
						<h3 className="container_title">{wcpfc_settings.conditional_logic_area_title_text}</h3>
						<p className="logic_area_description">{wcpfc_settings.conditional_logic_area_description_text}</p>
						<div className="condition_container">
							{this.state.logic_conditions}
							<button className="button button-primary add_new_logic_rule_button" onClick={this.onNewConditionRule}>{wcpfc_settings.add_new_logic_rule_text}</button>
						</div>	
					</div>
					
					<button className="button button_remove" onClick={(event) => this.props.onRemove(event, this.props.id)}>{wcpfc_settings.remove_text}</button>
				</div>
			</div>
		);
	}
}