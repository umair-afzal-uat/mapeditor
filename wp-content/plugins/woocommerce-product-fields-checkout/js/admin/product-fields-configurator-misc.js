"use strict";
jQuery(document).ready(function()
{
	
	
});
function wcpfc_initProductSelector(elem)
{
	jQuery(elem).select2(
			{
			   width: 550,
			  closeOnSelect: false,
			  allowClear: true,
			  ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 250,
						tags: "true",
						multiple: true,
						data: function (params) {
						  return {
							search_string: params.term, // search term
							page: params.page || 1,
							action: 'wcpfc_get_product_list'
						  };
						},
						processResults: function (data, params) 
						{
						  //console.log(params);
						 
						   return {
							results: jQuery.map(data.results, function(obj) 
							{
								const sku = obj.product_sku == null ? "N/A" : obj.product_sku;
								return { id: obj.id, text: "<strong>(SKU: "+sku+" ID: "+obj.id+")</strong> "+obj.product_name };
							}),
							pagination: {
										  'more': typeof data.pagination === 'undefined' ? false : data.pagination.more
										}
							};
						},
						cache: true
			  },
			  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			  minimumInputLength: 0,
			  templateResult: wcpfc_formatRepo,  //product-fields-configurator-misc.js
			  templateSelection:  wcpfc_formatRepoSelection  //product-fields-configurator-misc.js
			});
		
	//Needed to trigger the event to resize product/category selection box
	jQuery(elem).on('select2:select', function (e) {
				window.scrollBy(0, 1);
	});
}
function wcpfc_initCategorySelector(elem)
{
	jQuery(elem).select2(
		{
			width:550,
			closeOnSelect: false,
			allowClear: true,
			ajax: {
					url: ajaxurl,
					dataType: 'json',
					delay: 250,
					multiple: true,
					data: function (params) {
					  return {
						product_category: params.term, // search term
						page: params.page,
						action: 'wcpfc_get_category_list'
					  };
					},
					processResults: function (data, page) 
					{
				   
					   return {
						results: jQuery.map(data, function(obj) {
							return { id: obj.id, text: obj.category_name };
							}),
						pagination: {
									  'more': typeof data.pagination === 'undefined' ? false : data.pagination.more
									}
						
						};
					},
					cache: true
		  },
		  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		  minimumInputLength: 0,
		  templateResult: wcpfc_formatRepo, 
		  templateSelection: wcpfc_formatRepoSelection  
		});
		
	//Needed to trigger the event to resize product/category selection box
	jQuery(elem).on('select2:select', function (e) {
				window.scrollBy(0, 1);
	});
}
function wcpfc_formatRepo (repo) 
{
	if (repo.loading) return repo.text;
	
	var markup = '<div class="clearfix">' +
			'<div class="col-sm-12">' + repo.text + '</div>';
    markup += '</div>'; 
	
    return markup;
}

function wcpfc_formatRepoSelection (repo) 
{
  return repo.full_name || repo.text;
}