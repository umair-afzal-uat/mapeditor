"use strict";
function wcpfc_getDefaultValueByName(setting_name, props)
{
	if(props.loadFromSettings === false)
		return "";
	
	const defaultSettings = wcpfc_settings.field_data[props.parent_id].logic_condition[props.keyToLoad];
	
	if(!defaultSettings.hasOwnProperty(props.id))
		return "";
	
	return typeof defaultSettings !== 'undefined' && defaultSettings[props.id].hasOwnProperty(setting_name) ? defaultSettings[props.id][setting_name] : "";
}
function LogicOptionText(props)
{
	return(<div className="logic_text_options_container">
		<div className = "inline_block_container_no_align">
			<label className="option_label">{wcpfc_settings.condition_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
								<option value="is">{wcpfc_settings.is_text}</option>
								<option value="not">{wcpfc_settings.not_text}</option>
			</select>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_compare_function]"} defaultValue={wcpfc_getDefaultValueByName('logic_compare_function', props)}>
								<option value="contains">{wcpfc_settings.contains_text}</option>
								<option value="equal">{wcpfc_settings.equal_to_text}</option>
								<option value="starts">{wcpfc_settings.starts_with_text}</option>
								<option value="ends">{wcpfc_settings.ends_with_text}</option>
			</select>
		</div>
		<div className = "inline_block_container_no_align">				
			<label className="option_label">{wcpfc_settings.value_text}</label>
			<input type="text" name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][value]"} defaultValue={wcpfc_getDefaultValueByName('value', props)}></input>
		</div>
	</div>
	);
}
function LogicOptionTextarea(props)
{
	return(<div className="logic_textarea_options_container">
		<div className = "inline_block_container_no_align">
			<label className="option_label">{wcpfc_settings.condition_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
								<option value="is">{wcpfc_settings.is_text}</option>
								<option value="not">{wcpfc_settings.not_text}</option>
			</select>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_compare_function]"} defaultValue={wcpfc_getDefaultValueByName('logic_compare_function', props)}>
								<option value="contains">{wcpfc_settings.contains_text}</option>
								<option value="equal">{wcpfc_settings.equal_to_text}</option>
								<option value="starts">{wcpfc_settings.starts_with_text}</option>
								<option value="ends">{wcpfc_settings.ends_with_text}</option>
			</select>
		</div>
		<div className = "inline_block_container_no_align">				
			<label className="option_label">{wcpfc_settings.value_text}</label>
			<input type="text" name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][value]"} defaultValue={wcpfc_getDefaultValueByName('value', props)}></input>
		</div>
	</div>
	);
}
function LogicOptionNumber(props)
{
	return(<div className="logic_number_options_container">
		<div className = "inline_block_container_no_align">
			<label className="option_label">{wcpfc_settings.condition_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
								<option value="is">{wcpfc_settings.is_text}</option>
								<option value="not">{wcpfc_settings.not_text}</option>
			</select>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_compare_function]"} defaultValue={wcpfc_getDefaultValueByName('logic_compare_function', props)}>
								<option value="equal">{wcpfc_settings.equal_to_text}</option>
								<option value="greater">{wcpfc_settings.greater_than_text}</option>
								<option value="lesser">{wcpfc_settings.lesser_than_text}</option>
			</select>
		</div>
		<div className = "inline_block_container_no_align">				
			<label className="option_label">{wcpfc_settings.value_text}</label>
			<input type="number" name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][value]"} defaultValue={wcpfc_getDefaultValueByName('value', props)}></input>
		</div>
	</div>
	);
}
function LogicOptionSelect(props)
{
	return(<div className="logic_select_options_container">
		<div className = "inline_block_container_no_align">
			<div className="select_logic_options_container">
				<label className="option_label">{wcpfc_settings.condition_text}</label>
				<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
									<option value="is">{wcpfc_settings.is_text}</option>
									<option value="not">{wcpfc_settings.not_text}</option>
				</select>
				<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_compare_function]"} defaultValue={wcpfc_getDefaultValueByName('logic_compare_function', props)}>
									<option value="contains">{wcpfc_settings.contains_text}</option>
									<option value="equal">{wcpfc_settings.equal_to_text}</option>
									<option value="starts">{wcpfc_settings.starts_with_text}</option>
									<option value="ends">{wcpfc_settings.ends_with_text}</option>
				</select>
			</div>
		</div>
		<div className = "inline_block_container_no_align">				
			<label className="option_label">{wcpfc_settings.value_text}</label>
			<input type="text" name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][value]"} defaultValue={wcpfc_getDefaultValueByName('value', props)}></input>
		</div>
		{props.is_multiple_select && 
				<div className="multiple_value_policy_container inline_block_container_no_align">
					<label className="option_label">{wcpfc_settings.multiple_value_policy_description_text /*wcpfc_settings.multiple_value_policy_text*/}</label>
					<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][multiple_value_policy]"} defaultValue={wcpfc_getDefaultValueByName('multiple_value_policy', props)}>
									<option value="at_least_one">{wcpfc_settings.at_least_one_text}</option>
									<option value="all">{wcpfc_settings.all_text}</option>
					</select>
				</div>
			
			}
	</div>
	);
}

function LogicOptionCheckbox(props)
{
	return(<div className="logic_checkbox_options_container">
		<div className = "inline_block_container_no_align">
			<label className="option_label">{wcpfc_settings.condition_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
								<option value="is_checked">{wcpfc_settings.is_checked}</option>
								<option value="not_checked">{wcpfc_settings.is_not_checked}</option>
			</select>
		</div>
	</div>
	);
}
function LogicOptionDate(props)
{
	return(<div className="logic_date_options_container">
		<div className = "inline_block_container_no_align">
			<label className="option_label">{wcpfc_settings.condition_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
								<option value="is">{wcpfc_settings.is_text}</option>
								<option value="not">{wcpfc_settings.not_text}</option>
			</select>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_compare_function]"} defaultValue={wcpfc_getDefaultValueByName('logic_compare_function', props)}>
								<option value="equal">{wcpfc_settings.equal_to_text}</option>
								<option value="greater">{wcpfc_settings.greater_than_text}</option>
								<option value="greater_equal">{wcpfc_settings.greater_equal_than_text}</option>
								<option value="lesser">{wcpfc_settings.lesser_than_text}</option>
								<option value="lesser_equal">{wcpfc_settings.lesser_equal_than_text}</option>
			</select>
		</div>
		<div className = "inline_block_container_no_align">				
			<label className="option_label">{wcpfc_settings.value_text}</label>
			<input type="text" className="wcpfc_logic_date_selector" name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][value]"} defaultValue={wcpfc_getDefaultValueByName('value', props)}></input>
		</div>
	</div>
	);
}
function LogicOptionTime(props)
{
	return(<div className="logic_time_options_container">
		<div className = "inline_block_container_no_align">
			<label className="option_label">{wcpfc_settings.condition_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
								<option value="is">{wcpfc_settings.is_text}</option>
								<option value="not">{wcpfc_settings.not_text}</option>
			</select>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_compare_function]"} defaultValue={wcpfc_getDefaultValueByName('logic_compare_function', props)}>
								<option value="equal">{wcpfc_settings.equal_to_text}</option>
								<option value="greater">{wcpfc_settings.greater_than_text}</option>
								<option value="greater_equal">{wcpfc_settings.greater_equal_than_text}</option>
								<option value="lesser">{wcpfc_settings.lesser_than_text}</option>
								<option value="lesser_equal">{wcpfc_settings.lesser_equal_than_text}</option>
			</select>
		</div>
		<div className = "inline_block_container_no_align">				
			<label className="option_label">{wcpfc_settings.value_text}</label>
			<input type="text" className="wcpfc_logic_time_selector" name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][value]"} defaultValue={wcpfc_getDefaultValueByName('value', props)}></input>
		</div>
	</div>
	);
}
function LogicOptionPaymentMethod(props)
{
	
	let options = [];
	for (var key in wcpfc_settings['payment_methods'])
	{
		if (!wcpfc_settings['payment_methods'].hasOwnProperty(key)) continue;
		options.push(<option value={key} key={key}>{wcpfc_settings['payment_methods'][key].name}</option>);
	}
	return(<div className="logic_time_options_container">
		<div className = "inline_block_container_no_align">
			<label className="option_label">{wcpfc_settings.condition_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][logic_operator]"} defaultValue={wcpfc_getDefaultValueByName('logic_operator', props)}>
								<option value="is">{wcpfc_settings.is_text}</option>
								<option value="not">{wcpfc_settings.not_text}</option>
			</select>
		</div>
		<div className = "inline_block_container_no_align">				
			<label className="option_label">{wcpfc_settings.value_text}</label>
			<select name={"wcpfc_data["+props.parent_id+"][logic_condition]["+props.logic_field_id+"]["+props.id+"][value]"} defaultValue={wcpfc_getDefaultValueByName('value', props)}>
				{options}
			</select>
		</div>
	</div>
	);
}