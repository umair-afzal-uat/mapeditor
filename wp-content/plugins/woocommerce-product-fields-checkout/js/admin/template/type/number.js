"use strict";
class NumberType extends React.Component
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
				<div className="number_options_container">
					<input type="hidden" name={"wcpfc_data["+this.props.id+"][type]"} value="number"></input>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.min_value_text}</label>
						<input type="number" name={"wcpfc_data["+this.props.id+"][options][min_value]"} defaultValue={this.getDefaultValueByName('min_value')} step="0.001" ></input>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.max_value_text}</label>
						<input type="number" name={"wcpfc_data["+this.props.id+"][options][max_value]"} defaultValue={this.getDefaultValueByName('max_value')} step="0.001"></input>
					</div>
					<CommonFieldOptions id={this.props.id} loadFromSettings={this.props.loadFromSettings} settingsId={this.props.settingsId} />
				</div>
		);
	}
}