"use strict";
class WCPFC_FieldRendered extends React.Component
{
	constructor(props)
	{
		super(props);
	}
	initTimePicker()
	{
		const mySelf = this;
		jQuery('.wcpfc-time').each(function(index,elem)
		{
			const format = jQuery(elem).data('format') != "" ? jQuery(elem).data('format') : "HH:i";
			const interval = jQuery(elem).data('interval') != "" ? parseInt(jQuery(elem).data('interval')) : 15;
			const moment_format = "HH:mm";
			const settings = {
				 min_type : jQuery(elem).data('min-type'),
				 max_type : jQuery(elem).data('max-type'),
				 min_value : jQuery(elem).data('min-value') ? jQuery(elem).data('min-value') : 0,
				 max_value : jQuery(elem).data('max-value') ? jQuery(elem).data('max-value') : 0,
				 min_offset : jQuery(elem).data('min-offset') ? parseInt(jQuery(elem).data('min-offset')) : 0,
				 min_offset_type : jQuery(elem).data('min-offset-type'),
				 max_offset : jQuery(elem).data('max-offset') ? parseInt(jQuery(elem).data('max-offset')) : 0,
				 max_offset_type : jQuery(elem).data('max-offset-type'),
				 min_ca_be_before_now : jQuery(elem).data('min-can-be-before-now'),
				 max_ca_be_before_now : jQuery(elem).data('max-can-be-before-now'),
			};
			const variations = ['min','max'];
			let values = {
						min:  undefined,
						max:  undefined
			};
			
			variations.forEach(function(variation, index)
			{
				if(settings[variation+"_type"] == 'relative')
				{
					const abs_value = Math.abs(settings[variation+"_offset"]);
					values[variation] = settings[variation+"_offset"] < 0 ? moment(wcpfc_conf_data.current_time, moment_format).subtract(abs_value, settings[variation+"_offset_type"]).format(moment_format) : moment(wcpfc_conf_data.current_time, moment_format).add(settings[variation+"_offset"], settings[variation+"_offset_type"]).format(moment_format);
					values[variation] = values[variation].split(':');
				}	
				else 
				{
					values[variation] = settings[variation+"_value"] ? settings[variation+"_value"].split(':') : undefined;
				}
			});
			
			jQuery(elem).pickatime({
				format: format,
				formatSubmit: 'HH:i',
				hiddenSuffix: '',
				min: values.min,
				max: values.max,
				interval: interval,
				onSet: (context) => mySelf.props.onFieldDateTimeValueChange(context, this)
			}); 
		});
	}
	initDatePicker()
	{
		const mySelf = this;
		jQuery('.wcpfc-date').each(function(index,elem)
		{
			const format = jQuery(elem).data('format') != "" ? jQuery(elem).data('format') : "yyyy-mm-dd";
			const day_values = jQuery(elem).data('days-to-disable') != "" ? String(jQuery(elem).data('days-to-disable')).split(',') : [];
			const moment_format = "YYYY-MM-DD";
			const settings = {
				 min_type : jQuery(elem).data('min-type'),
				 max_type : jQuery(elem).data('max-type'),
				 min_value : jQuery(elem).data('min-value') ? jQuery(elem).data('min-value') : 0,
				 max_value : jQuery(elem).data('max-value') ? jQuery(elem).data('max-value') : 0,
				 min_offset : jQuery(elem).data('min-offset') ? parseInt(jQuery(elem).data('min-offset')) : 0,
				 min_offset_type : jQuery(elem).data('min-offset-type'),
				 max_offset : jQuery(elem).data('max-offset') ? parseInt(jQuery(elem).data('max-offset')) : 0,
				 max_offset_type : jQuery(elem).data('max-offset-type'),
			};
			
			
			const variations = ['min','max'];
			let values = {
						min:  undefined,
						max:  undefined
			};
			
			variations.forEach(function(variation, index)
			{
				if(settings[variation+"_type"] == 'relative')
				{
					
					const abs_value = Math.abs(settings[variation+"_offset"]);
					values[variation] = settings[variation+"_offset"] < 0 ? moment(wcpfc_conf_data.current_date).subtract(abs_value, settings[variation+"_offset_type"]).format(moment_format) : moment(wcpfc_conf_data.current_date).add(settings[variation+"_offset"], settings[variation+"_offset_type"]).format(moment_format);
					values[variation] = values[variation].split('-');
					
				}	
				else 
				{
					values[variation] = settings[variation+"_value"] ? settings[variation+"_value"].split('-') : undefined;
						
				}
				
				//Month from 0 to 11
				if(typeof values[variation] === 'object')
						values[variation][1] = parseInt(values[variation][1]) - 1; 
					
				//is necessary?
				if(typeof values[variation] === 'object')
					values[variation].forEach(function(elem, index)
					{
						values[variation][index] = parseInt(elem);
					});
			});
			
			
			
			let days_to_disable = [];
			for(var i = 0; i < day_values.length; i++)
				days_to_disable.push(parseInt(day_values[i]));
			
			jQuery(elem).pickadate({
				firstDay: 1,
				format: format,
				formatSubmit: 'yyyy-mm-dd',
				hiddenSuffix: '',
				selectMonths: true,
				selectYears: true,
				min: values.min,
				max: values.max,
				selectYears: jQuery(elem).data('num-years'),
				onSet: (context) => mySelf.props.onFieldDateTimeValueChange(context, this),
				disable: days_to_disable
				
			});
		});
		
		
	}
	escapeDoubleQuotes(str) 
	{
		return str.replace(/\\([\s\S])|(")/g,"\\$1$2"); 
	}
	initSpecialInputElements()
	{
		const mySelf = this;
		//select
		jQuery(".wcpfc-select").each(function(index, elem)
		{
			try 
			{
				if(!jQuery(elem).hasClass("select2-hidden-accessible")) //To avoid at first init that the first value is already selected
				{
					jQuery(elem).val(null);
				}		
				
				jQuery(elem).selectWoo({
					width:"", //forces a wrong width to force the inheritance
					placeholder: jQuery(elem).attr('placeholder')
				});
				jQuery(elem).on("select2:select select2:unselecting", mySelf.props.onFieldValueChange); //"onChange" on first eleme selection raised a "b.dataadapter is null select2" error
				jQuery(elem).trigger("change");
			}catch(e){}
		});
		//checkbox
		jQuery(".wcpfc-checkbox").each(function(index, elem)
		{
			
			if(elem.checked)
			{
				let argument = { currentTarget:elem};
				mySelf.props.onFieldValueChange(argument);
			}
		});
	}
	renderOptionsForSelector(field_data, is_multiple)
	{
		const  indexes = Object.keys(field_data.options.value_label);
		let options = [];
	
		indexes.forEach(function(elem_key, index)
		{
			options.push(<option key={index} value={field_data.options.value_label[elem_key].value}>{field_data.options.value_label[elem_key].label[wcpfc_conf_data.curr_lang]}</option>);
		});
		return options;
	}
	componentDidMount()
	{
		this.initDatePicker();
		this.initTimePicker();
		this.initSpecialInputElements();
	}
	render()
	{
		const indexes_array =  this.props.indexes_array;
		const field_data = this.props.field_data;
		
		//ToDo: priority? 
		const index = indexes_array.join("-"); 
		const id = field_data.id+"_"+index; 
		
		let output;
		const placeholder = field_data.options.hasOwnProperty("placeholder") ? field_data.options.placeholder[wcpfc_conf_data.curr_lang] : "";
		const description = field_data.options.hasOwnProperty("description") ? field_data.options.description[wcpfc_conf_data.curr_lang] : "";
		const value = field_data.options.hasOwnProperty("value") ? field_data.options.value[wcpfc_conf_data.curr_lang] : ""; //valid only for HTML field
		const minute_interval_timepicker = field_data.options.hasOwnProperty("minute_interval_timepicker") ? field_data.options["minute_interval_timepicker"] : "";
		const min_time_can_be_before_now = field_data.options.hasOwnProperty("min_time_can_be_before_now") ? field_data.options["min_time_can_be_before_now"] : "";
		const max_time_can_be_before_now = field_data.options.hasOwnProperty("max_time_can_be_before_now") ? field_data.options["max_time_can_be_before_now"] : "";
		const date_num_of_years = field_data.options.hasOwnProperty("date_num_of_years") ? field_data.options["date_num_of_years"] : "";
		const is_required = field_data.options.required == 'yes';
		const css_row_style = field_data.options.css_row_classes;
		const css_input_style = field_data.options.css_input_classes;
		const type = field_data.type;
		
		switch(field_data.type)
		{
			case 'country_state':
				output = 
						<CountryState key={id}
								field_data = {field_data}
								id = {id}
								cart_key = {this.props.cart_key}
								form_type = {this.props.form_type}
								onFieldValueChange = {this.props.onFieldValueChange}
								onFieldStateValueChange = {this.props.onFieldStateValueChange}
							/>
			break;
			case 'time':
			case 'date':
				const format = field_data.options.hasOwnProperty(type+"_frontend_format") ? field_data.options[type+"_frontend_format"] : "";
				const day_to_disable = field_data.options.hasOwnProperty("day_to_disable") ? Object.getOwnPropertyNames(field_data.options["day_to_disable"]) : "";
				
				output = <p key={id} className={"form-row form-row-"+field_data.options.row_width+" "+css_row_style} id={"order_"+id+"_field"} data-priority={ "110" }>
						<label htmlFor={"order_"+id} className="">{field_data.name[wcpfc_conf_data.curr_lang]} {is_required && <abbr className="required" title="required">*</abbr>}</label>
						<span className="woocommerce-input-wrapper">
							<input type="hidden" name={"order_"+id+"[cart_key]"} value={this.props.cart_key}></input>
							<input type="hidden" name={"order_"+id+"[form_type]"} value={this.props.form_type}></input>
							<input type="hidden" name={"order_"+id+"[field_type]"} value={field_data.type}></input>
							<input className={"input-text wcpfc-field wcpfc-"+type+" "+css_input_style} 
								name={"order_"+id+"[value]"}  
								type={"text"} 
								id = {"order_"+id}  
								data-format={format} 
								data-interval={minute_interval_timepicker} 
								data-min-value={field_data.options["min_"+type]} 
								data-max-value={field_data.options["max_"+type]}
								data-min-type={field_data.options["min_"+type+"_type"]}
								data-max-type={field_data.options["max_"+type+"_type"]} 
								data-min-offset={field_data.options["min_"+type+"_offset"]} 
								data-max-offset={field_data.options["max_"+type+"_offset"]} 
								data-min-offset-type={field_data.options[type+"_min_offset_type"]} 
								data-max-offset-type={field_data.options[type+"_max_offset_type"]} 
								data-min-before-now={field_data.options["min_"+type+"_can_be_before_now"]} 
								data-max-before-now={field_data.options["max_"+type+"_can_be_before_now"]}
								data-min-can-be-before-now={min_time_can_be_before_now}
								data-max-can-be-before-now={max_time_can_be_before_now}
								data-num-years={date_num_of_years}
								data-days-to-disable={day_to_disable}
								required={is_required}></input>
						</span>
						{description != "" &&
							<span className="description wcpfc-description">{description}</span>
						}
					</p>
			break;
			case 'checkbox': 
					const label = field_data.options.hasOwnProperty("label") ? field_data.options.label[wcpfc_conf_data.curr_lang] : "";
					const is_checked = field_data.options.hasOwnProperty("is_checked") ? field_data.options.is_checked == 'yes' : false;
					output = <p key={id} className={"form-row form-row-"+field_data.options.row_width+" "+css_row_style} id={"order_"+id+"_field"} data-priority={ "110" }>
						<label htmlFor={"order_"+id} className="">{field_data.name[wcpfc_conf_data.curr_lang]} {is_required && <abbr className="required" title="required">*</abbr>}</label>
						<span className="woocommerce-input-wrapper">
							<input type="hidden" name={"order_"+id+"[value]"} value="is_visible"></input>
							<input type="hidden" name={"order_"+id+"[cart_key]"} value={this.props.cart_key}></input>
							<input type="hidden" name={"order_"+id+"[form_type]"} value={this.props.form_type}></input>
							<input type="hidden" name={"order_"+id+"[label]"} value={this.escapeDoubleQuotes(label)}></input>
							<input type="hidden" name={"order_"+id+"[field_type]"} value={field_data.type}></input>
							<input required={is_required} type="checkbox" className="wcpfc-checkbox wcpfc-field" defaultChecked={is_checked} name={"order_"+id+"_checkbox"} id={"order_"+id} value="true" onChange = {this.props.onFieldValueChange}></input>
							<label className="wcpfc-checkbox-label" htmlFor={"order_"+id}>{label}</label>
						</span>
						{description != "" &&
							<span className="description wcpfc-description">{description}</span>
						}
					</p>
			break;
			case 'select': 
					output = <p key={id} className={"form-row form-row-"+field_data.options.row_width+" "+css_row_style} id={"order_"+id+"_field"} data-priority={ "110" }>
						<label htmlFor={"order_"+id} className="">{field_data.name[wcpfc_conf_data.curr_lang]} {is_required && <abbr className="required" title="required">*</abbr>}</label>
						<span className="woocommerce-input-wrapper">
							<input type="hidden" name={"order_"+id+"[cart_key]"} value={this.props.cart_key}></input>
							<input type="hidden" name={"order_"+id+"[form_type]"} value={this.props.form_type}></input>
							<input type="hidden" name={"order_"+id+"[field_type]"} value={field_data.type}></input>
							<select required={is_required} name={"order_"+id+"[value][]"}  id={"order_"+id} className={"wcpfc-select wcpfc-field "+css_input_style} multiple={field_data.options.select_multiple_selection =='yes'} placeholder={placeholder}>
								{this.renderOptionsForSelector(field_data, field_data.options.select_multiple_selection =='yes')}
							</select>
						</span>
						{description != "" &&
							<span className="description wcpfc-description">{description}</span>
						}
					</p>
			break;
			case 'number': //onBlur or onChange????
					output = <p key={id} className={"form-row form-row-"+field_data.options.row_width+" "+css_row_style} id={"order_"+id+"_field"} data-priority={ "110" }>
						<label htmlFor={"order_"+id} className="">{field_data.name[wcpfc_conf_data.curr_lang]} {is_required && <abbr className="required" title="required">*</abbr>}</label>
						<span className="woocommerce-input-wrapper">
							<input type="hidden" name={"order_"+id+"[cart_key]"} value={this.props.cart_key}></input>
							<input type="hidden" name={"order_"+id+"[form_type]"} value={this.props.form_type}></input>
							<input type="hidden" name={"order_"+id+"[field_type]"} value={field_data.type}></input>
							<input required={is_required} onChange  = {this.props.onFieldValueChange} className={"input-text wcpfc-field "+css_input_style} id={"order_"+id} name={"order_"+id+"[value]"} min={field_data.options.min_value} max={field_data.options.max_value} type={"number"}></input>
						</span>
						{description != "" &&
							<span className="description wcpfc-description">{description}</span>
						}
					</p>
			break;	
			case 'textarea':  //onBlur or onChange????
					output = <p key={id} className={"form-row form-row-"+field_data.options.row_width+" "+css_row_style} id={"order_"+id+"_field"} data-priority={ "110" }>
									<label htmlFor={"order_"+id} className="">{field_data.name[wcpfc_conf_data.curr_lang]} {is_required && <abbr className="required" title="required">*</abbr>}</label>
									<span className="woocommerce-input-wrapper">
										<input type="hidden" name={"order_"+id+"[cart_key]"} value={this.props.cart_key}></input>
										<input type="hidden" name={"order_"+id+"[form_type]"} value={this.props.form_type}></input>
										<input type="hidden" name={"order_"+id+"[field_type]"} value={field_data.type}></input>
										<textarea required={is_required} onChange  = {this.props.onFieldValueChange} className={"input-text wcpfc-field "+css_input_style} id={"order_"+id} name={"order_"+id+"[value]"} placeholder={placeholder}  ></textarea>
									</span>
									{description != "" &&
										<span className="description wcpfc-description">{description}</span>
									}
								</p>
			break;
			case 'html':  //onBlur or onChange????
					output = <div key={id} className={"form-row form-row-"+field_data.options.row_width+" "+css_row_style} id={"order_"+id+"_field"} data-priority={ "110" }>
									<div dangerouslySetInnerHTML={{__html: value}} />
									{description != "" &&
										<span className="description wcpfc-description">{description}</span>
									}
								</div>
			break;
			default: //onBlur or onChange????
				output = <p key={id} className={"form-row form-row-"+field_data.options.row_width+" "+css_row_style} id={"order_"+id+"_field"} data-priority={ "110" }>
						<label htmlFor={"order_"+id} className="">{field_data.name[wcpfc_conf_data.curr_lang]} {is_required && <abbr className="required" title="required">*</abbr>}</label>
						<span className="woocommerce-input-wrapper">
							<input type="hidden" name={"order_"+id+"[cart_key]"} value={this.props.cart_key}></input>
							<input type="hidden" name={"order_"+id+"[form_type]"} value={this.props.form_type}></input>
							<input type="hidden" name={"order_"+id+"[field_type]"} value={field_data.type}></input>
							<input required={is_required} onChange = {this.props.onFieldValueChange} className={"input-text wcpfc-field "+css_input_style} id={"order_"+id} name={"order_"+id+"[value]"} placeholder={placeholder}  type={"text"}></input>
						</span>
						{description != "" &&
							<span className="description wcpfc-description">{description}</span>
						}
					</p>
			break;
		}		

		return output;
	}
}