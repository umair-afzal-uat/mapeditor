"use strict";
class SelectType extends React.Component
{
	constructor(props)
	{
		super(props);
		this.nextIndex = 1;
		this.state = 
		{
			values_and_labels: [this.nextIndex-1]
		}
		this._isMultipleSelect = false;
		this.addNewLabeAndValue = this.addNewLabeAndValue.bind(this);
		this.deleteLabelAndValue = this.deleteLabelAndValue.bind(this);
		this.onMultipleSelectOptionChange = this.onMultipleSelectOptionChange.bind(this);
		
		//init
		if(this.props.loadFromSettings !== false)
		{
			const settings_key =  Object.keys(wcpfc_settings.field_data[this.props.settingsId].options.value_label);
			this.state.values_and_labels = [];
			const myself = this;
			settings_key.forEach(function(key, index)
			{
				myself.state.values_and_labels.push(key);
				myself.nextIndex = parseInt(key)+1;
			});
		}
	}
	addNewLabeAndValue(event)
	{
		event.preventDefault();
		this.setState((prevState) =>
		({
		    values_and_labels: prevState.values_and_labels.concat(this.nextIndex++)
		}));
	}
	deleteLabelAndValue(event, key_to_remove)
	{
		event.preventDefault();
		this.setState((prevState) =>
		{
			let new_values_and_labels = [];
			prevState.values_and_labels.forEach(function(elem,index)
			{
				if(elem != key_to_remove)
					new_values_and_labels.push(elem);
			});
			return {
				values_and_labels: new_values_and_labels
			}
		});
	}
	onMultipleSelectOptionChange(event)
	{
		this._isMultipleSelect = event.currentTarget.value == "yes";
		this.props.setIsMultiple(this._isMultipleSelect);
	}
	isMultipleSelect()
	{
		return this._isMultipleSelect;
	}
	renderLabelInput(elem)
	{
		let keys = []; 
		for (var key in wcpfc_settings.lang_data) 
		{
		  keys.push({id: wcpfc_settings.lang_data[key].id, code: wcpfc_settings.lang_data[key].code});
		}
		
		let output = [];
		
		for(let i = 0; i<keys.length; i++)
		{
			if(wcpfc_settings.lang_data[keys[i].code].country_flag_url != 'none')
				output.push(<img key={"flag_"+keys[i].code} className="wcpfc_flag" src={wcpfc_settings.lang_data[keys[i].code].country_flag_url} />);
			
			output.push(<input type="text"  
						placeholder={wcpfc_settings.label_text}  
						name={"wcpfc_data["+this.props.id+"][options][value_label]["+elem+"][label]["+wcpfc_settings.lang_data[keys[i].code].default_locale+"]"} 
						key={keys[i].code}
						defaultValue={this.getDefaultMultilanguageValueByName(elem, 'label', {locale: wcpfc_settings.lang_data[keys[i].code].default_locale})} 
						className="select_label"></input>
				);
		}

		return output;
	}
	renderLabelAndValues()
	{
		const output = this.state.values_and_labels.map((elem,index) =>
		{
			return( <div className="select_label_value_container" key={elem} id={elem}>
						<input type="text" placeholder={wcpfc_settings.value_text} name={"wcpfc_data["+this.props.id+"][options][value_label]["+elem+"][value]"} defaultValue={this.getDefaultValueorLabelByIndex(elem, "value")} className="select_value" ></input>
						{this.renderLabelInput(elem)}
						<button className="button button-delete delete_label_and_value_button" onClick={(event) => this.deleteLabelAndValue(event, elem)}>{wcpfc_settings.remove_text}</button>
					</div>
			);
		});
		
		return output;
	}
	renderPlaceholderInput()
	{
		//const keys = Object.keys(wcpfc_settings.lang_data); //is not sorted. First key is the default language
		let keys = []; 
		for (var key in wcpfc_settings.lang_data) 
		{
		  keys.push({id: wcpfc_settings.lang_data[key].id, code: wcpfc_settings.lang_data[key].code});
		}
		
		let output = [];
		
		for(let i = 0; i<keys.length; i++)
		{
			if(wcpfc_settings.lang_data[keys[i].code].country_flag_url != 'none')
				output.push(<img key={"flag_"+keys[i].code} className="wcpfc_flag" src={wcpfc_settings.lang_data[keys[i].code].country_flag_url} />);
			
			output.push(<input type="text" 
							name={"wcpfc_data["+this.props.id+"][options][placeholder]["+wcpfc_settings.lang_data[keys[i].code].default_locale+"]"} 
							key={keys[i].code}
							className="wcpfc_field_placeholder" 
							defaultValue={this.getDefaultMultilanguagePlaceholderText({locale: wcpfc_settings.lang_data[keys[i].code].default_locale})} ></input>
							);
		}
		return output;
	}
	getDefaultMultilanguagePlaceholderText(options)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options.placeholder;
		let output = settings.hasOwnProperty(options.locale) ? settings[options.locale] : "";
		
		return output;
	}
	getDefaultMultilanguageValueByName(index, type, options)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options.value_label;
		let output = settings.hasOwnProperty(index) ? settings[index][type][options.locale] : "";
		
		return output;
	}
	getDefaultValueorLabelByIndex(index, type)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options.value_label;
		
		return settings.hasOwnProperty(index) ? settings[index][type] : "";
		
	}
	getDefaultValueByName(setting_name)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options;
		
	
		return settings.hasOwnProperty(setting_name) ? settings[setting_name] : "";
	}
	componentWillMount()
	{
		
	}
	render()
	{
		return(
				<div className="select_options_container">
					<input type="hidden" name={"wcpfc_data["+this.props.id+"][type]"} value="select"></input>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.placeholder_text}</label>
						{this.renderPlaceholderInput()}
					</div>
					<div className="option_container">
						<label className="option_label">{wcpfc_settings.select_multiple_value_text}</label>
						<select name={"wcpfc_data["+this.props.id+"][options][select_multiple_selection]"} onChange={this.onMultipleSelectOptionChange} defaultValue={this.getDefaultValueByName('select_multiple_selection')}>
							<option value="no" >{wcpfc_settings.no_text}</option>
							<option value="yes">{wcpfc_settings.yes_text}</option>
						</select>
					</div>
					<div className="option_container">
						<label className="option_label">{wcpfc_settings.label_and_value_text}</label>
							{this.renderLabelAndValues()}
						<button className="button button-primary add_new_label_and_value_button" onClick={this.addNewLabeAndValue}>{wcpfc_settings.add_new_text}</button>
					</div>
					<CommonFieldOptions id={this.props.id} loadFromSettings={this.props.loadFromSettings} settingsId={this.props.settingsId} />
				</div>
		);
	}
}