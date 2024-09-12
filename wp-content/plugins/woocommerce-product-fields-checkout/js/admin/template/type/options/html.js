"use strict";
class HtmlFieldOptions extends React.Component
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
	
	render()
	{
		return(
				<div className = "common_options_container">
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