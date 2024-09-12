"use strict";
class CommonFieldOptions extends React.Component
{
	constructor(props)
	{
		super(props);
	}
	//settings loading
	getDefaultValueByName(setting_name)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options
		return settings.hasOwnProperty(setting_name) ? settings[setting_name] : "";
	}
	getDefaultMultilanguageValueByName(setting_name, options)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options;
		let output = settings.hasOwnProperty(setting_name) ? settings[setting_name][options.locale] : "";
		
		return output;
	}
	renderDescriptionInput()
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
			
			output.push( <textarea 
							name={"wcpfc_data["+this.props.id+"][options][description]["+wcpfc_settings.lang_data[keys[i].code].default_locale+"]"}
							key={keys[i].code}
							className="wcpfc_field_description" 
							defaultValue={this.getDefaultMultilanguageValueByName('description', {locale: wcpfc_settings.lang_data[keys[i].code].default_locale} )} 
							 /> );
		}

		return output;
	}
	render()
	{
		return(
				<div className = "common_options_container">
					<div className = "option_container"> 
						<label className="option_label">{wcpfc_settings.description_text}</label> 
						{this.renderDescriptionInput()}
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.css_classes_text}</label>
						<input type="text" name={"wcpfc_data["+this.props.id+"][options][css_row_classes]"} defaultValue={this.getDefaultValueByName('css_row_classes')}></input>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.css_input_text}</label>
						<input type="text" name={"wcpfc_data["+this.props.id+"][options][css_input_classes]"} defaultValue={this.getDefaultValueByName('css_input_classes')} ></input>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.mandatory_text}</label>
						<select type="text" name={"wcpfc_data["+this.props.id+"][options][required]"} defaultValue={this.getDefaultValueByName('required')}>
							<option value="no">{wcpfc_settings.no_text}</option>
							<option value="yes">{wcpfc_settings.yes_text}</option>
						</select>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.show_in_emails_text}</label>
						<select type="text" name={"wcpfc_data["+this.props.id+"][options][show_in_emails]"} defaultValue={this.getDefaultValueByName('show_in_emails')} >
							<option value="yes">{wcpfc_settings.yes_text}</option>
							<option value="no">{wcpfc_settings.no_text}</option>
						</select>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.show_in_order_details_text}</label>
						<select type="text" name={"wcpfc_data["+this.props.id+"][options][show_in_order_details_page]"} defaultValue={this.getDefaultValueByName('show_in_order_details_page')} >
							<option value="yes">{wcpfc_settings.yes_text}</option>
							<option value="no">{wcpfc_settings.no_text}</option>
						</select>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.row_width_text}</label>
						<select type="text" name={"wcpfc_data["+this.props.id+"][options][row_width]"} defaultValue={this.getDefaultValueByName('row_width')}>
							<option value="wide">{wcpfc_settings.row_width_full}</option>
							<option value="first">{wcpfc_settings.row_width_first}</option>
							<option value="last">{wcpfc_settings.row_width_last}</option>
						</select>
					</div>
				</div>
				);
	}
}