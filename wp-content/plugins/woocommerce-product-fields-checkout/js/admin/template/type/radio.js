"use strict";
class RadioType extends React.Component
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
	render()
	{
		return(
				<div className="text_options_container">
					<input type="hidden" name={"wcpfc_data["+this.props.id+"][type]"} value="radio" ></input>
					<CommonFieldOptions id={this.props.id} loadFromSettings={this.props.loadFromSettings} settingsId={this.props.settingsId} />
				</div>
		);
	}
}