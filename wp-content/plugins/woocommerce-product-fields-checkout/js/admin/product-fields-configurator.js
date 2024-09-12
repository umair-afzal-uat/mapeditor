"use strict";
class FieldConfigurator extends React.Component
{
	constructor(props) 
	{
		super(props);
		this.state = {
			fields: [],
			fields_ref: [],
			nextIndex: 0
		}
		this.idPrefix = "id";		
		this.onAddNewFieldClick = this.onAddNewFieldClick.bind(this);
		this.onRemoveFieldClick = this.onRemoveFieldClick.bind(this);
		this.getAllFieldsMeta = this.getAllFieldsMeta.bind(this);
		this.refreshFieldsUI = this.refreshFieldsUI.bind(this);
	} 
	
	getAllFieldsMeta(return_object)
	{
		let names = return_object ? {} : [];
		let checkout_native_fields_types = ['billing_fields', 'shipping_fields', 'payment_methods']; //extends this array to add "native" WooCommerce fields
		const field_types_to_exclude = ['html'];
		
		for(let i = 0; i<this.state.fields.length; i++)
		{
			let field_data = this.state.fields_ref[i].current.getFieldsMeta();
			
			//Field types to exclude
			if(field_types_to_exclude.includes(field_data.type))
				continue;
			
			if(return_object)
				names[field_data.id] = field_data;
			else 
				names.push(field_data);
		}
		for(let type = 0; type < checkout_native_fields_types.length; type++)
			for(let i in wcpfc_settings[checkout_native_fields_types[type]])
			{
				if (!wcpfc_settings[checkout_native_fields_types[type]].hasOwnProperty(i)) continue;
				
				let current_elem = wcpfc_settings[checkout_native_fields_types[type]][i];
				
				
				if(return_object)
					names[i] = current_elem;
				else 
					names.push(current_elem); 
			}
		
		
		return names;
	}
	onAddNewFieldClick(event)
	{
		event.preventDefault();
		
		
		this.createNewField(false, 0);
	}
	createNewField(loadFromSettings, index)
	{
		const ref = React.createRef();
		this.setState((prevState) => 
		{
			const nextIndex = parseInt(prevState.nextIndex) + 1 > parseInt(index) + 1 ? parseInt(prevState.nextIndex) + 1 : parseInt(index) + 1 ;
			return {
				//New fields rendering
				fields: prevState.fields.concat(
						<Field key={loadFromSettings ? index : prevState.nextIndex} 
							   id={loadFromSettings ? this.idPrefix+index : this.idPrefix+prevState.nextIndex}  //This is done because using just numeric values, item order is lost (JSON.parse() reorder numeric keys)
							   ref = {ref}
							   getAllFieldsMeta = {(return_object) => this.getAllFieldsMeta(return_object)}
							   onRemove = {(event, index_to_remove) => this.onRemoveFieldClick(event, index_to_remove)}
							   refreshFieldsUI = {() => this.refreshFieldsUI(null)} //No need anymore. Used event system instead of callbacks
							   loadFromSettings = {loadFromSettings}
							   />
						),
				fields_ref: prevState.fields_ref.concat(ref), //No need anymore. Used event system instead of callbacks
				nextIndex: nextIndex
			}
		}/* , function(){console.log(this.state.nextIndex);} */);
	}
	//Force refreshing the UI of all the fields
	refreshFieldsUI(fields_ref)
	{
		fields_ref = fields_ref == null ? this.state.fields_ref :fields_ref;
		fields_ref.forEach(function (reference) 
		{
			reference.current.refreshChildrenUI();			  
		});	 
	}
	onRemoveFieldClick(event, index_to_remove)
	{
		event.preventDefault();
		if(confirm(wcpfc_settings.confirm_field_delete_text))
		{
			let new_fields_ref = [];
			let new_fields_array = [];
			this.setState((prevState) => 
			{
				for (let i=0; i < prevState.fields.length; i++)
					if(prevState.fields[i].props.id != index_to_remove)
					{
						new_fields_array.push(prevState.fields[i]);
						new_fields_ref.push(prevState.fields_ref[i]);
					}
					
				return {
					//New fields rendering
					fields: new_fields_array,
					fields_ref: new_fields_ref
				}
			}, function(){this.refreshFieldsUI(new_fields_ref);	});
		}
	}	
	componentWillMount()
	{
		
		
	}
	componentDidMount()
	{
		//settings loading
		this.loadComponentsValuesFromSettings()
		
			
		
		//sort 
		jQuery('.field_container').sortable(
			{
				handle: 'h2.field_header',
				cursor: 'move',
				placeholder: 'drag_placeholder',
				forcePlaceholderSize: true,
				opacity: 0.4,
				stop: function(event, ui)
				{
					
				}
			})
			.disableSelection();
	}
	
	loadComponentsValuesFromSettings()
	{
		const myself = this;
		const keys = Object.keys(wcpfc_settings.field_data);
		keys.forEach(function(key, index)
		{
			myself.createNewField(true, key.replace(myself.idPrefix,""));  //This is done because using just numeric values, item order is lost (JSON.parse() reorder numeric keys)
		});
		
		
	}
	render()
	{
		
		return(
			<ErrorBoundary>
				<div className = "field_and_button_container ">
					<div className = "field_container" id="field_container">
					{this.state.fields}
					</div>
					<div className="add_new_field_button_container">
						<button className="button button-primary add_new_field_button" data-loader="" onClick={this.onAddNewFieldClick}>{wcpfc_settings.add_new_text}</button>
						<div className="wcpfc_loader" id=""></div>
						{/*if(true) a*/}
					</div>
				</div>
			</ErrorBoundary>
		);
	}	
}	 
	 
//Done to wait all the libraries are loaded	 
jQuery(document).ready(function () 
{
	setTimeout(function() 
	{
		ReactDOM.render(
		  <FieldConfigurator />,
		  document.getElementById('product_fields_container')
		);
	}, 3000);
});