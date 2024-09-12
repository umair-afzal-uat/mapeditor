"use strict";
class LogicOptionCountryState extends React.Component
{
	constructor(props)
	{
		super(props);
		this.selectCountryComp;
		this.state = {
			state_selector_html: [],
		};
		this.loadState = this.loadState.bind(this);
	}
	getDefaultValueByName(setting_name)
	{
		if(this.props.loadFromSettings === false)
			return "";
		
		const defaultSettings = wcpfc_settings.field_data[this.props.parent_id].logic_condition[this.props.keyToLoad];
		
		if(!defaultSettings.hasOwnProperty(this.props.id))
			return "";
	
		return typeof defaultSettings !== 'undefined' && defaultSettings[this.props.id].hasOwnProperty(setting_name) ? defaultSettings[this.props.id][setting_name] : "";
	}
	loadState(event)
	{
		const mySelf = this;
		event.preventDefault();
		
		const country_code = jQuery(event.currentTarget).val();
		const target = '#state_selector_'+ jQuery(event.currentTarget).data('target-id');
		const field_id = jQuery(event.currentTarget).data('id');
		const parent_id = jQuery(event.currentTarget).data('parent-id');
		const logic_field_id = jQuery(event.currentTarget).data('logic-field-id');
		let selectedState = ""; 
		//For event the hasOwnProperty is always false. This is the only method to retrieve the value if exists
		try{
			selectedState = event.detail.selectedState;
		}
		catch(e){};
		
		if(country_code == 'any')
		{
			jQuery(target).empty();
			return false;
		}
	
		//UI
		jQuery(target+"_loader").show();
		jQuery(target+"_loader").css('display', 'inline-block');
		jQuery(target).hide();
		
		//Ajax 
		var random = Math.floor((Math.random() * 1000000) + 999);
		var formData = new FormData();
		formData.append('action', 'wcpfc_load_states_by_country_id');	
		formData.append('country_code', country_code); 
		formData.append('field_id', field_id); 
		formData.append('parent_id', parent_id); 
		formData.append('logic_field_id', logic_field_id); 
		formData.append('selected_state', selectedState); 
		
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				//UI
				jQuery(target+"_loader").hide();
				jQuery(target).show();
				jQuery(target).html(data); 
				
			},
			error: function (data) 
			{
				
			},
			cache: false,
			contentType: false,
			processData: false
		}); 
		
	}
	renderCountryListOptions(country_list)
	{
		let output=[];
		for (var country_key in country_list) 
		{
			// skip loop if the property is from prototype
			if (!country_list.hasOwnProperty(country_key)) continue;
			output.push(<option key={country_key} value={country_key}>{country_list[country_key]}</option>)
		}
		return output;
			
	}
	componentDidMount()
	{
		
		
		//Force the country selector change event. In this way the state selector is displayed (if any) in case of settings loading
		var event = new CustomEvent('init', { detail: {selectedState: this.getDefaultValueByName("state")}});
		this.selectCountryComp.addEventListener('init', this.loadState);
		this.selectCountryComp.dispatchEvent(event);
	}
	render()
	{
		const country_list = JSON.parse(wcpfc_country_data.country_list);
		
		return(<div className="logic_country_state_options_container">
				<div className = "inline_block_container_no_align">
					<label className="option_label">{wcpfc_settings.condition_text}</label>
					<select name={"wcpfc_data["+this.props.parent_id+"][logic_condition]["+this.props.logic_field_id+"]["+this.props.id+"][logic_operator]"}  defaultValue={wcpfc_getDefaultValueByName('logic_operator', this.props)}>
										<option value="is">{wcpfc_settings.is_text}</option>
										<option value="not">{wcpfc_settings.not_text}</option>
					</select>
				</div>
				<div className = "inline_block_container_no_align">
					<select className="wcpfc_country_selector" 
						name={"wcpfc_data["+this.props.parent_id+"][logic_condition]["+this.props.logic_field_id+"]["+this.props.id+"][country]"}
						required="required" 
						data-target-id={this.props.parent_id+"-"+this.props.logic_field_id+"-"+this.props.id}
						data-parent-id={this.props.parent_id}
						data-logic-field-id={this.props.logic_field_id}
						data-id={this.props.id}
						onChange={this.loadState}
						defaultValue={this.getDefaultValueByName("country")}
						ref={input => this.selectCountryComp = input}
						>
							<option value="any" >{wcpfc_settings.any_text}</option>
								{this.renderCountryListOptions(country_list)}
					</select>
				</div>
				<div className = "inline_block_container_no_align">
					<div className="wcpfc_loader" id={"state_selector_"+this.props.parent_id+"-"+this.props.logic_field_id+"-"+this.props.id+"_loader"} ></div>
					<div id={"state_selector_"+this.props.parent_id+"-"+this.props.logic_field_id+"-"+this.props.id} className="wcpfc_state_container" >
						{this.state.state_selector_html}
					</div>
				</div>
		</div>
		);
	}
}