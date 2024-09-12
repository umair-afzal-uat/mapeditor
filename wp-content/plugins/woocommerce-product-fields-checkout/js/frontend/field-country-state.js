"use strict";
class CountryState extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = 
		{
			state_field: <p  className={"form-row form-row-"+this.props.field_data.options.country_state_selector_width+" "+this.props.field_data.options.css_row_classes} 
							  id={"order_"+this.props.id+"_state_field"} data-priority={ "110" } />
		}
		this.onCountryChange = this.onCountryChange.bind(this);
		this.setupStateSelector = this.setupStateSelector.bind(this);
	}
	onCountryChange(event)
	{
		event.preventDefault();
		event.stopPropagation();
		
		this.props.onFieldValueChange(event);
		
		const country_code = jQuery(event.currentTarget).val();
		const random = Math.floor((Math.random() * 1000000) + 999);
		const formData = new FormData();
		const mySelft = this;
		const state_selector_name = "#order_"+this.props.id+"_state_field";
		const loader_elem_name = "#order_"+this.props.id+"_loader";
		const mySelf = this;
		
		//UI
		jQuery(loader_elem_name).fadeIn();
		jQuery(state_selector_name).fadeOut();
		
		//ajax
		formData.append('action', 'wcpfc_load_states_by_country_id_for_frontend');	
		formData.append('country_code', country_code); 
		jQuery.ajax({
			url: wcpfc_conf_data.ajaxurl,
			type: 'POST',
			data: formData,
			async: true,
			success: function (data) 
			{
				//UI
				jQuery(loader_elem_name).fadeOut();
				mySelft.renderStateSelector(JSON.parse(data));
				jQuery(state_selector_name).fadeIn();
			},
			error: function (data) 
			{
				console.log("Error on loading state");
				mySelf.onCountryChange(event);
			},
			cache: false,
			contentType: false,
			processData: false
		}); 
	}
	renderStateSelector(state_data)
	{
		//const mySelf = this;
		if(this.props.field_data.options.country_hide_states == 'no')
		{
		   this.setState( (prevState, props) => 
			{
				const is_required = props.field_data.options.required == 'yes';
			
				let output = <p  key={Math.random()} className={"form-row form-row-"+props.field_data.options.country_state_selector_width+" "+props.field_data.options.css_row_classes} 
											id={"order_"+props.id+"_state_field"} data-priority={ "110" } /> ;
				switch(state_data.type)
				{
					case 'hidden': 
								break;
					case 'input': output = <p  key={Math.random()} id={"order_"+props.id+"_state_field"} className={"form-row form-row-"+props.field_data.options.country_state_selector_width+" "+props.field_data.options.css_row_classes} id={"order_"+props.id+"_state_field"} data-priority={ "110" }>
												<label htmlFor={"order_"+props.id+"_state"} className="">{state_data.label} {is_required && <abbr className="required" title="required">*</abbr>}</label>
												<span className="woocommerce-input-wrapper">
													<input required={is_required} id={"order_"+props.id+"_state"}  name={"order_"+props.id+"[state]"} className={"input-text wcpfc-field"} type="text" onChange={this.props.onFieldStateValueChange} />
												</span>
											</p>;
								break;
					case 'select': output = <p  key={Math.random()} id={"order_"+props.id+"_state_field"} className={"form-row form-row-"+props.field_data.options.country_state_selector_width+" "+props.field_data.options.css_row_classes} id={"order_"+props.id+"_state_field"} data-priority={ "110" }>
									<label htmlFor={"order_"+props.id+"_state"} className="">{state_data.label} {is_required && <abbr className="required" title="required">*</abbr>}</label>
									<span className="woocommerce-input-wrapper">
										<select required={is_required} id={"order_"+props.id+"_state"} name={"order_"+props.id+"[state]"} className={"wcpfc-state-select wcpfc-field"} >
												{this.renderOptionsForStateSelector(state_data.states)}
										</select>
									</span>
								</p>;
								break;
				}
				
				return {					
					state_field: output
					}
			}, this.setupStateSelector);
		}
	}
	renderOptionsForStateSelector(states_data)
	{
		let output = [];
		let keys = Object.keys(states_data);
		keys.forEach(function(elem,index)
		{
			const label = jQuery('<textarea />').html(states_data[elem]).text();
			output.push(<option key={index} value={elem}>{label}</option>);
		});
		return output;
	}
	renderOptionsForCountrySelector()
	{
		let output = [];
		let country_array;
		
		switch(this.props.field_data.options['country_selection_type'] )
		{
			case 'all': country_array = wcpfc_conf_data.all_countries; break;
			case 'allowed_countries': country_array = wcpfc_conf_data.allowed_countries; break;
			case 'shipping_countries': country_array = wcpfc_conf_data.shipping_countries; break;
		}
		
		const keys = Object.keys(country_array);
		output = keys.map((elem, index) =>
		{
			const label = jQuery('<textarea />').html(country_array[elem]).text();
			return(<option key={index} value={elem}>{label}</option>
			);
		});
		return output;
	}
	setupStateSelector()
	{
		const mySelf = this;
		if(jQuery("#order_"+this.props.id+"_state").hasClass("wcpfc-state-select"))
		{
			jQuery("#order_"+this.props.id+"_state").selectWoo({width:""}); //forces a wrong width to force the inheritance
			jQuery("#order_"+this.props.id+"_state").on("change", mySelf.props.onFieldStateValueChange);
		}
		
		
		jQuery("#order_"+this.props.id+"_state").trigger("change");
	}
	componentDidMount()
	{
		let selector = jQuery("#order_"+this.props.id);
		selector.selectWoo(
		{
			
		});
		selector.on("change", this.onCountryChange);
		selector.trigger("change");
	}
	render()
	{
		const description = this.props.field_data.options.hasOwnProperty("description") ? this.props.field_data.options.description[wcpfc_conf_data.curr_lang] : "";
		const id = this.props.id;
		const is_required = this.props.field_data.options.required == 'yes';
		const loader_style = {
				backgroundImage: 'url(' + wcpfc_conf_data.loader_path + ')',
			};
		return(
				<div className="country_state_container">
					<p  className={"form-row form-row-"+this.props.field_data.options.row_width+" "+this.props.field_data.options.css_row_classes} id={"order_"+id+"_field"} data-priority={ "110" }>
							<label htmlFor={"order_"+id} className="">{this.props.field_data.name[wcpfc_conf_data.curr_lang]} {is_required && <abbr className="required" title="required">*</abbr>}</label>
							<span className="woocommerce-input-wrapper">
								<input type="hidden" name={"order_"+this.props.id+"[cart_key]"} value={this.props.cart_key}></input>
								<input type="hidden" name={"order_"+id+"[form_type]"} value={this.props.form_type}></input>
								<input type="hidden" name={"order_"+id+"[field_type]"} value="country_state"></input>
								<select name={"order_"+this.props.id+"[value]"} id={"order_"+this.props.id} 
										className={"wcpfc-country-select wcpfc-field "+this.props.field_data.options.css_input_classes} 
										multiple={this.props.field_data.options.select_multiple_selection =='yes'}
										required={is_required} >
									{this.renderOptionsForCountrySelector()}
								</select>
							</span>
					{description != "" &&
						<span className="description wcpfc-description">{description}</span>
					}
					</p>
					{this.props.field_data.options.country_hide_states == 'no' && this.state.state_field}
					<div className="wcpf-loader" id={"order_"+this.props.id+"_loader"} style={loader_style} ></div>
					
				</div>
		);
	}
} 