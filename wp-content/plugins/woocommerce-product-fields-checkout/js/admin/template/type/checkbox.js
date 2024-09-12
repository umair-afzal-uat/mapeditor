"use strict";
class CheckBoxType extends React.Component
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
	renderLabelInput()
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
							name={"wcpfc_data["+this.props.id+"][options][label]["+wcpfc_settings.lang_data[keys[i].code].default_locale+"]"} 
							key={keys[i].code}
							className="wcpfc_field_label_checkbox" 
							defaultValue={this.getDefaultMultilanguageValueByName('label', {locale: wcpfc_settings.lang_data[keys[i].code].default_locale})} ></input>
							);
		}

		return output;
	}
	render()
	{
		return(
				<div className="checkbox_options_container">
					<input type="hidden" name={"wcpfc_data["+this.props.id+"][type]"} value="checkbox"></input>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.label_text}</label>
						{this.renderLabelInput()}
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.is_checked}</label>
						<select type="text" name={"wcpfc_data["+this.props.id+"][options][is_checked]"} defaultValue={this.getDefaultValueByName('is_checked')}>
							<option value="no">{wcpfc_settings.no_text}</option>
							<option value="yes">{wcpfc_settings.yes_text}</option>
						</select>
					</div>
					<CommonFieldOptions id={this.props.id} loadFromSettings={this.props.loadFromSettings} settingsId={this.props.settingsId} />
				</div>
		);
	}
}