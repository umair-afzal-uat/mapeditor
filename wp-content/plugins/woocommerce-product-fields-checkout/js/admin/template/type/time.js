"use strict";
class TimeType extends React.Component
{
	constructor(props)
	{
		super(props);
		this.interval = 15;
		this.state = {
			min_time_html: [],
			max_time_html: []
		}
		this.renderDateTimeSelector = this.renderDateTimeSelector.bind(this);
	}
	componentWillMount()
	{
		//settings loading
		if(this.props.loadFromSettings === false)
		{
			this.updateStateAccordingType("absolute", "min");
			this.updateStateAccordingType("absolute", "max");
		}
		else 
		{
			this.updateStateAccordingType(this.getDefaultValueByName('min_time_type'), "min");
			this.updateStateAccordingType(this.getDefaultValueByName('max_time_type'), "max");
		}
	}
	renderDateTimeSelector(event, min_or_max)
	{
		const type = event.currentTarget.value;
		this.updateStateAccordingType(type, min_or_max);
	}
	updateStateAccordingType(type, min_or_max)
	{
		let output = [];
		switch(type)
		{
			case "absolute":				
					output.push( <div className = "option_container" key="0">
									<label className="option_label">{wcpfc_settings[min_or_max+"_time_text"]}</label>
									<input type="text" className="wcpfc_time_selector" name={"wcpfc_data["+this.props.id+"][options]["+min_or_max+"_time]"} defaultValue={this.getDefaultValueByName(min_or_max+"_time")}></input>
								</div>	); 
					if(min_or_max == "min")
						output.push( <div className = "option_container" key="1">
										<label className="option_label">{wcpfc_settings.min_time_can_be_before_now_text}</label>
										<select name={"wcpfc_data["+this.props.id+"][options]["+min_or_max+"_time_can_be_before_now]"} defaultValue={this.getDefaultValueByName(min_or_max+"_time_can_be_before_now")}>
												<option value="no" >{wcpfc_settings.no_text}</option>
												<option value="yes">{wcpfc_settings.yes_text}</option>
										</select>
									</div>	);				
			break;
			case "relative":
					output.push( <div className = "option_container" key="2">
									<label className="option_label">{wcpfc_settings[min_or_max+"_relative_time_from_now"]}</label>
									<input type="number" step={1} name={"wcpfc_data["+this.props.id+"][options]["+min_or_max+"_time_offset]"} defaultValue={this.getDefaultValueByName(min_or_max+"_time_offset")}></input>
									<select className="time_offset_selector" name={"wcpfc_data["+this.props.id+"][options][time_"+min_or_max+"_offset_type]"} defaultValue={this.getDefaultValueByName("time_"+min_or_max+"_offset_type")}>
											<option value="seconds" >{wcpfc_settings.seconds_text}</option>
											<option value="minutes">{wcpfc_settings.minutes_text}</option>
											<option value="hours">{wcpfc_settings.hours_text}</option>
									</select>
								</div>	);
			break;
			
		}
		
		this.setState(
					{
						[min_or_max+"_time_html"]: output
					}, this.initTimePicker);	
	}
	componentDidUpdate()
	{
		
	}
	initTimePicker()
	{
		jQuery('.wcpfc_time_selector').pickatime({
			format: 'HH:i',
			formatSubmit: 'HH:i',
			hiddenSuffix: '',
			interval: 15
			
		});
	}
	getDefaultValueByName(setting_name)
	{
		if(this.props.loadFromSettings === false && setting_name == 'minute_interval_timepicker')
			return this.interval;
			
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options
		
	
		return settings.hasOwnProperty(setting_name) ? settings[setting_name] : "";
	}
	render()
	{
		return(
				<div className="time_options_container">
					<input type="hidden" name={"wcpfc_data["+this.props.id+"][type]"} value="time"></input>
					
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.dateformat_text}</label>
						<p>{wcpfc_settings.dateformat_description_text}</p>
						<select name={"wcpfc_data["+this.props.id+"][options][time_frontend_format]"} defaultValue={this.getDefaultValueByName('time_frontend_format')}>
							<option value="HH:i">H:i</option>
							<option value="h:i a">g:i a</option>
							<option value="h:i A">g:i A</option>						
						</select>
					</div>
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.min_time_type_text}</label>
						<select type="text" name={"wcpfc_data["+this.props.id+"][options][min_time_type]"} onChange = {(event) => this.renderDateTimeSelector(event, "min") } defaultValue={this.getDefaultValueByName("min_time_type")}>
							<option value="absolute">{wcpfc_settings.absolute_text}</option>
							<option value="relative">{wcpfc_settings.relative_text}</option>
						</select>
					</div>
					{this.state.min_time_html}
							
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.max_time_type_text}</label>
						<select type="text" name={"wcpfc_data["+this.props.id+"][options][max_time_type]"} onChange = {(event) => this.renderDateTimeSelector(event, "max") } defaultValue={this.getDefaultValueByName("max_time_type")}>
							<option value="absolute">{wcpfc_settings.absolute_text}</option>
							<option value="relative">{wcpfc_settings.relative_text}</option>
						</select>
					</div>	
					{this.state.max_time_html}
					
					
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.minute_interval_timepicker_text}</label>
						<input type="number" min="0" step="1" name={"wcpfc_data["+this.props.id+"][options][minute_interval_timepicker]"} defaultValue={this.getDefaultValueByName("minute_interval_timepicker")} required={true}></input>
					</div>
					<CommonFieldOptions id={this.props.id} loadFromSettings={this.props.loadFromSettings} settingsId={this.props.settingsId} />
				</div>
		);
	}
}