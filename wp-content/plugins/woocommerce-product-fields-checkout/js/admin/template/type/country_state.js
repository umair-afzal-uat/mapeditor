"use strict";
class CountryStateType extends React.Component
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
				<div className="country_state_options_container">
					<input type="hidden" name={"wcpfc_data["+this.props.id+"][type]"} value="country_state"></input>
					<div className = "option_container country_type_selection">
						<label className="option_label">{wcpfc_settings.country_to_show_text}</label>
						<p><i>{wcpfc_settings.country_to_show_description_text}</i></p>
						<select  name={"wcpfc_data["+this.props.id+"][options][country_selection_type]"} defaultValue={this.getDefaultValueByName('country_selection_type')}> 
							<option value="all" >{wcpfc_settings.coultry_all_selection}</option>
							<option value="allowed_countries" >{wcpfc_settings.coultry_selling_selection}</option>
							<option value="shipping_countries">{wcpfc_settings.coultry_shipping_selection}</option>
						</select>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.hide_state_text}</label>
						<select name={"wcpfc_data["+this.props.id+"][options][country_hide_states]"} defaultValue={this.getDefaultValueByName('country_hide_states')} >
							<option value="no" >{wcpfc_settings.no_text}</option>
							<option value="yes">{wcpfc_settings.yes_text}</option>
						</select>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.state_selector_width_text}</label>
						<select name={"wcpfc_data["+this.props.id+"][options][country_state_selector_width]"} defaultValue={this.getDefaultValueByName('country_state_selector_width')} >
							<option value="wide">{wcpfc_settings.row_width_full}</option>
							<option value="first">{wcpfc_settings.row_width_first}</option>
							<option value="last">{wcpfc_settings.row_width_last}</option>
						</select>
					</div>
					<CommonFieldOptions id={this.props.id} loadFromSettings={this.props.loadFromSettings} settingsId={this.props.settingsId} />
				</div>
		);
	}
}