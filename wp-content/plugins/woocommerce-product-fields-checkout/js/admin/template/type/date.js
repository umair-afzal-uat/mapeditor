"use strict";
class DateType extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
			min_date_html: [],
			max_date_html: [],
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
			this.updateStateAccordingType(this.getDefaultValueByName('min_date_type'), "min");
			this.updateStateAccordingType(this.getDefaultValueByName('max_date_type'), "max");
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
									<label className="option_label">{wcpfc_settings[min_or_max+"_date_text"]}</label>
									<input type="text" className="wcpfc_date_selector" name={"wcpfc_data["+this.props.id+"][options]["+min_or_max+"_date]"} defaultValue={this.getDefaultValueByName(min_or_max+"_date")}></input>
								</div>	); 
					if(min_or_max == "min")
						output.push( <div className = "option_container" key="1">
										<label className="option_label">{wcpfc_settings.min_date_can_be_before_now_text}</label>
										<select name={"wcpfc_data["+this.props.id+"][options]["+min_or_max+"_date_can_be_before_now]"} defaultValue={this.getDefaultValueByName(min_or_max+'_date_can_be_before_now')}>
												<option value="no" >{wcpfc_settings.no_text}</option>
												<option value="yes">{wcpfc_settings.yes_text}</option>
										</select>
									</div>	);				
			break;
			case "relative":
					output.push( <div className = "option_container" key="2">
									<label className="option_label">{wcpfc_settings[min_or_max+"_relative_date_from_now"]}</label>
									<input type="number" step={1} name={"wcpfc_data["+this.props.id+"][options]["+min_or_max+"_date_offset]"} defaultValue={this.getDefaultValueByName(min_or_max+"_date_offset")}></input>
									<select className="time_offset_selector" name={"wcpfc_data["+this.props.id+"][options][date_"+min_or_max+"_offset_type]"} defaultValue={this.getDefaultValueByName('date_'+min_or_max+'_offset_type')}>
											<option value="days" >{wcpfc_settings.days_text}</option>
											<option value="months">{wcpfc_settings.months_text}</option>
											<option value="years">{wcpfc_settings.years_text}</option>
									</select>
								</div>	);
			break;
			
		}
		
		this.setState(
					{
						[min_or_max+"_date_html"]: output
					}, this.initDatePicker);	
	}
	componentDidUpdate()
	{
		
	}
	initDatePicker()
	{
		jQuery('.wcpfc_date_selector').pickadate({
			format: 'yyyy-mm-dd',
			formatSubmit: 'yyyy-mm-dd',
			selectMonths: true,
			selectYears: true,
			hiddenSuffix: ''
			
		});
	}
	getDefaultValueByName(setting_name)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options
		
		return settings.hasOwnProperty(setting_name) ? settings[setting_name] : "";
	}
	getChecBoxDefaultValueByName(setting_name, index)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const settings = wcpfc_settings.field_data[this.props.settingsId].options
		
		return settings.hasOwnProperty(setting_name) && settings[setting_name].hasOwnProperty(index) ? true : false;
	}
	 handleChangeChk = changeEvent => 
	{
		
    }
	render()
	{
		return(
				<div className="date_options_container">
					<input type="hidden" name={"wcpfc_data["+this.props.id+"][type]"} value="date"></input>
					
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.dateformat_text}</label>
						<p>{wcpfc_settings.dateformat_description_text}</p>
						<select name={"wcpfc_data["+this.props.id+"][options][date_frontend_format]"} defaultValue={this.getDefaultValueByName('date_frontend_format')}>
							<option value="dd/mm/yyyy">d/m/Y</option>
							<option value="mm/dd/yyyy">m/d/Y</option>
							<option value="yyyy-mm-dd">Y-m-d</option>
							<option value="mmmm d, yyyy">F j, Y</option>
						</select>
					</div>
					
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.date_num_of_years_text}</label>
						<p>{wcpfc_settings.date_num_of_years_description_text}</p>
						<input min="1" step="1" type="number" name={"wcpfc_data["+this.props.id+"][options][date_num_of_years]"} defaultValue={this.getDefaultValueByName('date_num_of_years')}></input>
					</div>
					
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.min_date_type_text}</label>
						<select name={"wcpfc_data["+this.props.id+"][options][min_date_type]"} onChange = {(event) => this.renderDateTimeSelector(event, "min") } defaultValue={this.getDefaultValueByName('min_date_type')}>
							<option value="absolute">{wcpfc_settings.absolute_text}</option>
							<option value="relative">{wcpfc_settings.relative_text}</option>
						</select>
					</div>
					{this.state.min_date_html}
							
					<div className = "option_container">
						<label className="option_label">{wcpfc_settings.max_date_type_text}</label>
						<select name={"wcpfc_data["+this.props.id+"][options][max_date_type]"} onChange = {(event) => this.renderDateTimeSelector(event, "max") } defaultValue={this.getDefaultValueByName('max_date_type')}>
							<option value="absolute">{wcpfc_settings.absolute_text}</option>
							<option value="relative">{wcpfc_settings.relative_text}</option>
						</select>
					</div>	
					{this.state.max_date_html}
					
					<div className="option_container">
						<label className="option_label">{wcpfc_settings.days_of_the_week_to_disable}</label>
						<input type="checkbox" 
							   name={"wcpfc_data["+this.props.id+"][options][day_to_disable][1]"}
							   value="true"
							   id={"checkbox_1_"+this.props.id}
							   defaultChecked={this.getChecBoxDefaultValueByName('day_to_disable', '1')}
							   data-id={1}
							   onChange={this.handleChangeChk}></input>
							   <label htmlFor={"checkbox_1_"+this.props.id} className="checbox_label">{wcpfc_settings.monday_text}</label>
							   
						<input type="checkbox" 
							   name={"wcpfc_data["+this.props.id+"][options][day_to_disable][2]"}
							   value="true"
							   id={"checkbox_2_"+this.props.id}
							   defaultChecked={this.getChecBoxDefaultValueByName('day_to_disable', '2')}
							   data-id={1}
							   onChange={this.handleChangeChk}></input>
							   <label htmlFor={"checkbox_2_"+this.props.id} className="checbox_label">{wcpfc_settings.tuesday_text}</label>
						
						<input type="checkbox" 
							   name={"wcpfc_data["+this.props.id+"][options][day_to_disable][3]"}
							   value="true"
							   id={"checkbox_3_"+this.props.id}
							   defaultChecked={this.getChecBoxDefaultValueByName('day_to_disable', '3')}
							   data-id={1}
							   onChange={this.handleChangeChk}></input>
							   <label htmlFor={"checkbox_3_"+this.props.id} className="checbox_label">{wcpfc_settings.wednesday_text}</label>
							   
						<input type="checkbox" 
							   name={"wcpfc_data["+this.props.id+"][options][day_to_disable][4]"}
							   value="true"
							   id={"checkbox_4_"+this.props.id}
							   defaultChecked={this.getChecBoxDefaultValueByName('day_to_disable', '4')}
							   data-id={1}
							   onChange={this.handleChangeChk}></input>
							   <label htmlFor={"checkbox_4_"+this.props.id} className="checbox_label">{wcpfc_settings.thursday_text}</label>
							   
						<input type="checkbox" 
							   name={"wcpfc_data["+this.props.id+"][options][day_to_disable][5]"}
							   value="true"
							   id={"checkbox_5_"+this.props.id}
							   defaultChecked={this.getChecBoxDefaultValueByName('day_to_disable', '5')}
							   data-id={1}
							   onChange={this.handleChangeChk}></input>
							   <label htmlFor={"checkbox_5_"+this.props.id} className="checbox_label">{wcpfc_settings.friday_text}</label>
							   
						<input type="checkbox" 
							   name={"wcpfc_data["+this.props.id+"][options][day_to_disable][6]"}
							   value="true"
							   id={"checkbox_6_"+this.props.id}
							   defaultChecked={this.getChecBoxDefaultValueByName('day_to_disable', '6')}
							   data-id={1}
							   onChange={this.handleChangeChk}></input>
							   <label htmlFor={"checkbox_6_"+this.props.id} className="checbox_label">{wcpfc_settings.saturday_text}</label>
							   
						<input type="checkbox" 
							   name={"wcpfc_data["+this.props.id+"][options][day_to_disable][7]"}
							   value="true"
							   id={"checkbox_7_"+this.props.id}
							   defaultChecked={this.getChecBoxDefaultValueByName('day_to_disable', '7')}
							   data-id={1}
							   onChange={this.handleChangeChk}></input>
							   <label htmlFor={"checkbox_7_"+this.props.id} className="checbox_label" >{wcpfc_settings.sunday_text}</label>
					</div>
					

					<CommonFieldOptions id={this.props.id} loadFromSettings={this.props.loadFromSettings} settingsId={this.props.settingsId} />
				</div>
		);
	}
}