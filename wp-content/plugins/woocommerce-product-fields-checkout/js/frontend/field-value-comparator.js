"use strict";
function wcpfc_compare_value_by_field_type(value, logic)
{
	let result = false;
	const type = logic.type;
	const compare_function = logic.logic_compare_function;
	const is = logic.logic_operator;
	let moment_format = "";
	let  value_to_compare;
	
	
	
	switch(type)
	{
		case 'country_state':
						const country_to_compare = logic.country;
						const state_to_compare = logic.state;
						//state 
						result =  is == 'is' ? value.main == country_to_compare : value.main != country_to_compare;
						if(result && state_to_compare != undefined && state_to_compare != 'any')
							result =  is == 'is' ? value.state == state_to_compare : value.state != state_to_compare;
		break;
		case 'time':  moment_format = "HH:mm";
		case 'date':  moment_format = moment_format == "" ? "YYYY-MM-DD" : moment_format;
					
					value_to_compare = logic.value; 
					switch(compare_function)
					{
						case 'equal': 
										if(type == 'date')
											result =  is == 'is' ? moment(value.main, moment_format).isSame(value_to_compare, moment_format) : !moment(value.main, moment_format).isSame(value_to_compare, moment_format); 
										else 
										{
											result =  is == 'is' ? value.main == value_to_compare : value.main != value_to_compare;
											
										}
								break;
						case 'greater': result = is == 'is' ? moment(value.main, moment_format) > moment(value_to_compare, moment_format) : moment(value.main, moment_format) < moment(value_to_compare, moment_format); break;
						case 'greater_equal': result = is == 'is' ? moment(value.main, moment_format) >= moment(value_to_compare, moment_format) : moment(value.main, moment_format) < moment(value_to_compare, moment_format); break;
						case 'lesser': result = is == 'is' ? moment(value.main, moment_format) < moment(value_to_compare, moment_format) : moment(value.main, moment_format) > moment(value_to_compare, moment_format); break;
						case 'lesser_equal': result = is == 'is' ? moment(value.main, moment_format) <= moment(value_to_compare, moment_format) : moment(value.main, moment_format) > moment(value_to_compare, moment_format); break;
					}
		break;
		case 'checkbox':  result =  is == 'is_checked' ? value.main : !value.main;
		break;
		case 'number': 
				value_to_compare = logic.value;
				switch(compare_function)
				{
					case 'equal': result =  is == 'is' ? parseInt(value.main) == parseInt(value_to_compare) : parseInt(value.main) != parseInt(value_to_compare); break;
					case 'greater': result = is == 'is' ? parseInt(value.main) > parseInt(value_to_compare) : parseInt(value.main) < parseInt(value_to_compare); break;
					case 'lesser': result = is == 'is' ? parseInt(value.main) < parseInt(value_to_compare) : parseInt(value.main) > parseInt(value_to_compare); break;
				}
		break;
		
		case 'text':
		case 'textarea': 
		case 'select': 
				let temp_value = Array.isArray(value.main) ? value.main : [value.main];
				value_to_compare = logic.value != null ? logic.value.toLowerCase() : "";
				let temp_result = false;
				 for(let i = 0; i<temp_value.length; i++)
				{
					temp_value[i] = temp_value[i].toLowerCase();
					switch(compare_function)
					{
						case 'contains': temp_result =  is == 'is' ? temp_value[i].includes(value_to_compare) : !temp_value[i].includes(value_to_compare); break;
						case 'equal': temp_result = is == 'is' ? temp_value[i] === value_to_compare : temp_value[i] !== value_to_compare; break;
						case 'starts': temp_result = is == 'is' ? temp_value[i].startsWith(value_to_compare) : !temp_value[i].startsWith(value_to_compare); break;
						case 'ends': temp_result = is == 'is' ? temp_value[i].endsWith(value_to_compare) : !temp_value[i].endsWith(value_to_compare); break;
					}
					result =  result || temp_result;
				}
		break;
		case 'payment_method':  value_to_compare = "payment_method_"+value.main;
								result =  (is == 'is_checked' && value_to_compare == logic.field_unique_id) || (is != 'is_checked' && value_to_compare != logic.field_unique_id);
								
		break;
	}
	
	return result;
}