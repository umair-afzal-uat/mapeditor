/* ////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////////////////////////////////////////// DOCUMENT /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* -------------------------------------------------------------------- READY --- */
jQuery(init);
jQuery(document).ready(function($) {
	let strava_tokens = '';


	if (localStorage.getItem("wielrennen.strava.token") !== undefined ) {

		activity_strava_callback();
		$('html').addClass('scale_active');

	}
});
/* --------------------------------------------------------------------- INIT --- */
function init() {
    // browser
    browser_init();

	// alert
	alert_init();

	// action
	action_init();

	// option
	option_init();

	// view
	view_init();

	// app
	app_init();

	// design
	design_init();

	// basemap
	basemap_init();

	// preview
	preview_init();

	// paper
	paper_init();

	// poster
	poster_init();
	
	// font
	font_init();

	// layer
	layer_init();
	
	// map
	map_init();

	// overlay
	overlay_init();

	// activity
	activity_init();

	// label
	label_init();

	// outline
	outline_init();

	// elevation
	elevation_init();

	// text
	text_init();

	// control
	control_init();

	// review
	review_init();

	// strava
	strava_init();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////// BROWSER /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function browser_init() {
	// vh
	browser_vh();
	
    // load
	browser_load();

	// resize
	browser_resize();
}

/* ----------------------------------------------------------------------- VH --- */
function browser_vh() {
	// listen for resize
	jQuery(window).on('resize', function() {
		var vh;

		// single viewport unit
		vh = (jQuery(window).innerHeight() * 0.01);

		// --vh value
		jQuery(':root').css('--vh', [vh, 'px',].join(''));
	});

	// trigger resize
	jQuery(window).trigger('resize');
}

/* --------------------------------------------------------------------- LOAD --- */
function browser_load() {
	// remove load class
	setTimeout(function() { jQuery('html').removeClass('browser_load'); }, 50);
}

/* ------------------------------------------------------------------- RESIZE --- */
function browser_resize() {
	var timer;

	// listen for resize
	jQuery(window).on('resize', function() {
		// destroy timer
		clearTimeout(timer);

		// debounce
		timer = setTimeout(function() {
			// remove resize class
			jQuery('html').removeClass('browser_resize');
		}, 500);

		// add resize class
		jQuery('html').addClass('browser_resize');
	});
}

/* ---------------------------------------------------------------- SUPPORTED --- */
function browser_supported() {
	var supported = true;

	// determine whether mobile device
	(function(a){
		if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) supported = false;
	})(navigator.userAgent || navigator.vendor || window.opera);

	return supported;
}

/* --------------------------------------------------------------- URL : COPY --- */
function browser_url_copy(event) {
	var $target,
		$input;
	var url;

	// cache elements
	$target = jQuery(event.currentTarget);

	// store url
	url = location.href;

	// legacy
	if (empty(navigator.clipboard)) {
		// build input
		$input = jQuery('<input>');
		// append to page
		jQuery('body').append($input);
		// set copy value
		$input.val(url);
		// focus and select
		$input.focus().select();
		// execute command
		document.execCommand('copy');
	} 
	// clipboard
	else navigator.clipboard.writeText(url);

	// remove input
	$input.remove();

	// add active class
	$target.addClass('active');
}

/* -------------------------------------------------------------- URL : EMAIL --- */
function browser_url_email(event) {
	var subject,
		body,
		url;

	// store location
	url = location.href;

	// build mail data
	subject = 'English Cyclist map designer link';
	body = url;

	// open mail client
	location.href = ['mailto:', jQuery.param({ subject: subject, body: body, }),].join('?');
}

/* -------------------------------------------------------------------- CLASS --- */
function browser_class(name) {
	var $basemap;
	var delimiter,
		prefix;

	// cache element
	$basemap = jQuery('section.layer iframe.basemap');

	// determine delimiter
	delimiter = name.split('-').length > 1 ? '-' : '_';
	// determine class prefix
	prefix = name.split(delimiter)[0];

	// remove class
	jQuery('html').removeClassPrefix(prefix);
	$basemap.contents().find('html').removeClassPrefix(prefix);

	// add class
	jQuery('html').addClass(name);
	$basemap.contents().find('html').addClass(name);
}

/* ------------------------------------------------------------------- WINDOW --- */
function browser_window(url, width, height, center, options) {
	var params = [];

	// default values
	width = width || 500;
	height = height || 500;
	center = center || 'parent';
	options = options || {};

	// set window dimensions
	options.width = width;
	options.height = height;

	// center on parent
	if (center === 'parent') {
		options.top = (window.screenY + Math.round((jQuery(window).height() - height) / 2));
      	options.left = (window.screenX + Math.round((jQuery(window).width() - width) / 2));
	}
	// center on screen
	else {
		options.top = ((screen.height - options.height) / 2) - 50;
		options.left = window.screenLeft + (screen.width - options.width) / 2;
	}

	// loop through window options
	for (var i = 0; i < Object.keys(options).length; i++) {
		var key;

		// store option pair
		key = Object.keys(options)[i];
		value = options[key];

		// convert boolean to string
		if (typeof options[key] === 'boolean') value = value === true ? 'yes' : 'no';

		// add option value to params
		params.push([key, value,].join('='));
	}

	// open window
	return window.open(url, null, params.join(','));
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////////////////////////////////////////////// ALERT /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function alert_init() {
	//
}

/* ------------------------------------------------------------------- RENDER --- */
function alert_render(data) {
	var $alert,
		$item;

	// cache element
	$alert = jQuery('aside.alert');

	// clone
	$item = $alert.find('.item.clone').clone().removeClass('clone');

	// define properties
	$item.addClass(['type', data.type,].join('_'));

	// name
	$item.find('h6').text(data.name);
	// text
	$item.find('p').html(data.text);

	// append
	$alert.append($item);

	// listen for animationend
	$item.on('animationend', function() {
		// destroy render
		$item.remove();
	});
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////// ACTION /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function action_init() {
	// event
	action_event();
}

/* -------------------------------------------------------------------- EVENT --- */
function action_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// click event
		action_event_click();
	})();
}

/* ------------------------------------------------------------ EVENT : CLICK --- */
function action_event_click() {
	// listen for click
	jQuery(document).on('click', '[data-action-target]', function(event) {
		var action;

		// store action
		action = jQuery(this).attr('data-action-target');

		// trigger
		window[action](event);
	});
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////// OPTION /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function option_init() {
	// event
	option_event();
}

/* -------------------------------------------------------------------- EVENT --- */
function option_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// scroll event
		option_event_scroll();

		// resize event
		option_event_resize();
	})();
}

/* ----------------------------------------------------------- EVENT : SCROLL --- */
function option_event_scroll() {
	// listen for scroll
	jQuery('aside.option .scroll').on('scroll', function() {
		var $scroll;

		// cache element
		$scroll = jQuery(this);

		// mask
		option_mask();

		// remove display class
		$scroll.find('section.step.display .prompt').removeClass('display');
	});
}

/* ----------------------------------------------------------- EVENT : SCROLL --- */
function option_event_resize() {
	// listen for resize
	jQuery(window).on('resize', function() {
		// mask
		option_mask();
	});
}

/* --------------------------------------------------------------------- MASK --- */
function option_mask() {
	var $section,
		$scroll,
		$header,
		$footer;
	var opacity = {},
		scroll = {},
		offset,
		delta = 96;

	// cache elements
	$section = jQuery('aside.option').find('section.stage.display section.step.display');
	$scroll = $section.closest('.scroll');
	$header = $section.find('header');
	$footer = $section.find('footer');

	// bail when no active section
	if ($section.length === 0) return;

	// store element height
	height = $scroll.outerHeight();

	// store scroll data
	scroll.top = $scroll.scrollTop();
	scroll.height = $scroll.prop('scrollHeight');
	scroll.distance = (scroll.height - height);

	// store section offset
	offset = $section.offset().top;

	// calculate header opacity
	opacity.header = (1 - ((delta + offset) / delta));
	opacity.header = Math.min(Math.max(opacity.header, 0), 1);

	// calculate footer opacity
	opacity.footer = (1 - ((delta - (scroll.distance - scroll.top)) / delta));
	opacity.footer = Math.min(Math.max(opacity.footer, 0), 1);

	// set opacity
	$header.css('--opacity', opacity.header);
	$footer.css('--opacity', opacity.footer);
}

/* ------------------------------------------------------------------- TOGGLE --- */
function option_toggle(event) {
	var $target;

	// cache element
	$target = jQuery(event.currentTarget);

	// toggle active class
	$target.toggleClass('active');
	jQuery('html').toggleClass('option_active');

	// scale
	poster_scale();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////// SWITCH /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function view_init() {
	// event
	view_event();
}

/* -------------------------------------------------------------------- EVENT --- */
function view_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// click event
		view_event_click();
	})();
}

/* ------------------------------------------------------------ EVENT : CLICK --- */
function view_event_click() {
	// listen for click
	jQuery('[data-view-target]').on('click', function() {
		var $target;
		var view;

		// cache element
		$target = jQuery(this);

		// store view target
		view = $target.attr('data-view-target');

		// view
		view_toggle(view);

		// trigger scroll
		jQuery('aside.option .scroll').trigger('scroll');
	});
}

/* ------------------------------------------------------------------- TOGGLE --- */
function view_toggle(view) {
	var $source,
		$option;
	// cache element
	$option = jQuery('aside.option');

	// reset scroll
	$option.scrollTop(0);

	// determine whether double view switch
	if (view.indexOf(':') !== -1) {
		// split into stage/step
		view = view.split(':');

		// cache element
		$source = $option.find('[data-view-source=' + view[0] + ']');

		// add stage class
		browser_class(['stage', view[0],].join('_'));
	}
	// single switch
	else {
		// cache element
		$source = $option.find('[data-view-source=' + view + ']');

		// add stage class
		browser_class(['stage', view,].join('_'));
	}

	// toggle display class
	$option.find('[data-view-source]').removeClass('display');
	$source.addClass('display');

	// double view switch
	if (jQuery.isArray(view) === true) {
		// toggle display class
		$source.find('[data-view-source]').removeClass('display');
		$source.find('[data-view-source=' + view[1] + ']').addClass('display');

		// add step class
		browser_class(['step', view[1],].join('_'));
	}

	// mask
	option_mask();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////////// APP /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function app_init() {
	// prepare
	app_prepare();
}

/* ------------------------------------------------------------------ PREPARE --- */
function app_prepare() {
    // async
    (async function() {
		// determine browser support
		APP.support = browser_supported();
		// determine whether app is rendering
		APP.render = jQuery('html').is('.render_active');
		// determine iframe state
        APP.iframe = window !== window.parent;

		// supported
		if (APP.support) {
			// add supported class
			browser_class('browser_supported');
			
			// preview
			try { await preview_prepare(); } catch { return; }
		}

		// render
		if (APP.render === true) {
				
			// add active class
			browser_class('render_active');
		}

        // flag ready
        APP.ready = true;
		// trigger ready
		APP.trigger('ready');

		// iframe
		if (APP.iframe === true) {
			// trigger parent ready method
			message_post(window.parent, 'app_ready');
		}

		// add active class
		browser_class('app_active');
    })();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////// DESIGN /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function design_init() {
	// event
	design_event();
}

/* -------------------------------------------------------------------- EVENT --- */
function design_event() {

	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// click event
		design_event_click();

		// focus event
		design_event_focus();

		// input event
		design_event_input();
	})();
}

/* ------------------------------------------------------------ EVENT : CLICK --- */
function design_event_click() {
	// listen for click
	jQuery('aside.option').on('click', '[data-design-value]', function(event) {
		var $target,
			$parent;

		// cache elements
		$target = jQuery(event.currentTarget);
		$parent = $target.closest('[data-design-key]');

		// action
		design_action(event);

		// toggle active class
		$parent.find('a.item').removeClass('active').filter($target).addClass('active');
	});
}

/* ------------------------------------------------------------ EVENT : FOCUS --- */
function design_event_focus() {
	// listen for focus
	jQuery('aside.option').on('focus', '[data-design-key] input', function(event) {
		// action
		design_action(event);
	});
}

/* ------------------------------------------------------------ EVENT : INPUT --- */
function design_event_input() {
	// listen for input
	jQuery('aside.option').on('input', '[data-design-key] input', function(event) {
		// action
		design_action(event);
	});
}

/* ------------------------------------------------------------------- ACTION --- */
function design_action(event) {
	var $target,
		$parent;
	var prefix,
		action,
		key;

	// cache elements
	$target = jQuery(event.currentTarget);
	$parent = $target.closest('[data-design-key]');

	// store design data
	key = $parent.attr('data-design-key');

	// determine prefix
	prefix = key.split('_').shift();
	// build action
	action = [prefix, 'design', 'action',].join('_');

	// trigger
	window[action](event, key);

	// update
	design_store();
}

/* ------------------------------------------------------------------ PREPARE --- */
function design_prepare() {
	// promise
	return new Promise(async function(resolve, reject) {
		var response,
			design;

		// request guid
		
		DESIGN.guid = await design_guid_request();

		// validate
		if (!empty(DESIGN.guid)) {
			// request
			response = await design_request();
	
			

			// validate
			if (empty(response)) {
				// view
				view_toggle('activity:intro');

				// alert
				// alert_render({
				// 	type: 'negative', 
				// 	name: 'Design Load Failed', 
				// 	text: 'Please reload this page to try again. If this problem persists contact support.',
				// });

				// reject
				return reject();
			}
		}

		// update
		if (!empty(DESIGN.guid) && !empty(response)) {
			// store design
			// design = response;

			// validate
			// if (design.status !== 'draft' && APP.render !== true) {
			// 	// alert
			// 	alert_render({
			// 		type: 'negative', 
			// 		name: 'Design Load Prohibited', 
			// 		text: 'Purchased designs cannot be modified.',
			// 	});

			// 	// reject
			// 	return reject();
			// }

			// store design config
			design = response.config;


			// determine whether design has activities
			if (design.layer.activity.item.length > 0) {
				// view
				view_toggle('design');
			}
			// no activities
			else {
				// view
				view_toggle('activity:intro');
			}
		}
		// insert
		else {

			// view
			view_toggle('activity:intro');

			// insert
			response = await design_insert();

		

			// validate
			if (empty(response)) {
				// resolve
				return resolve();
			}

			// store guid
			DESIGN.guid = response;
			

			// update guid
			design_guid_update();
		
			
			// build design
			design = JSON.parse(JSON.stringify(DESIGN));
		}

		// load
		await design_load(design);


		// resolve
		resolve();
	});
}

/* ------------------------------------------------------------------ REQUEST --- */
function design_request() {
	// promise
	return new Promise(async function(resolve) {
        var response;
        config = jQuery.extend(true, {}, DESIGN);

        fd = new FormData();
		fd.append('guid', DESIGN.guid);
		fd.append('config', JSON.stringify(config));

		// request
		try { 
		
			response = await api_request('post', '?action=wpe_design_show', fd); } catch { 
			// resolve
			return resolve(); 
		};

		// resolve
		resolve(response.data.design);
	});
}

/* ------------------------------------------------------------------- INSERT --- */
function design_insert() {
	// promise
	return new Promise(async function(resolve) {
		var response,
			config,
			fd;

		// initialize form data
		fd = new FormData();

		// clone design config
		config = jQuery.extend(true, {}, DESIGN);
		
		// build config data
		fd.append('config', JSON.stringify(config));

		// request
		try { response = await api_request('post', '?action=wpe_design', fd); } catch(error) {
			// alert
			// alert_render({
			// 	type: 'negative',
			// 	name: 'Design Insert Failed',
			// 	text: error.responseJSON.error.description,
			// });

			// resolve
			return resolve();
		}


		// resolve
		resolve(response.data.design.guid);
	});
}

/* ------------------------------------------------------------------- UPDATE --- */
function design_update() {
	// promise
	return new Promise(async function(resolve) {
		var config,
			fd;

		// initialize form data
		fd = new FormData();

		// design
		config = design_clone();

		// build config data
		fd.append('config', JSON.stringify(config));
		fd.append('guid', DESIGN.guid);

		// request
		try { await api_request('post', '?action=wpe_design', fd); } catch(error) {
			// alert
			// alert_render({
			// 	type: 'negative', 
			// 	name: 'Design Update Failed', 
			// 	text: error.responseJSON.error.description,
			// });

			// resolve
			return resolve();
		}

		// resolve
		return resolve();
	});
}

/* -------------------------------------------------------------------- STORE --- */
function design_store() {
	// promise
	return new Promise(async function(resolve) {
        // bail when not ready
        if (APP.ready === false) return resolve();
        
		// destroy timer
		clearTimeout(APP.store.timer);

		// rate limit requests
		APP.store.timer = setTimeout(async function() {
			// update
			await design_update();

			// resolve
			resolve();
		}, 1000);
	});
}

/* -------------------------------------------------------------------- CLONE --- */
function design_clone() {
	var design;

	// clone design
	design = jQuery.extend(true, {}, DESIGN);

	// process labels
	for (var a = 0; a < design.layer.label.item.length; a++) {
		// remove circular dependencies
		delete design.layer.label.item[a].instance;
	}

	return design;
}

/* ----------------------------------------------------------- GUID : REQUEST --- */
function design_guid_request() {
	// promise
	return new Promise(async function(resolve) {
		var guid;

		// top
		if (APP.iframe !== true) {
			// retrieve guid from hash
			guid = location.hash.substr(1).replace(/\//g, '');

			// resolve
			return resolve(guid);
		}
	
		// post message
		message_post(window.parent, 'design_guid_request', undefined, 'design_guid_callback');

		// wait for guid response
		DESIGN.guid = {};
		DESIGN.guid.request = jQuery.Deferred();
		guid = await DESIGN.guid.request;

		// resolve
		resolve(guid);
	});
}

/* ---------------------------------------------------------- GUID : CALLBACK --- */
function design_guid_callback(event) {
	// resolve
	DESIGN.guid.request.resolve(event.data.payload.guid);
}

/* ------------------------------------------------------------ GUID : UPDATE --- */
function design_guid_update() {
	// top
	if (APP.iframe !== true) {
		// append design guid
		return location.hash = '/' + DESIGN.guid;
	}

	// post message
	message_post(window.parent, 'design_guid_update', { guid: DESIGN.guid, });
}

/* --------------------------------------------------------------------- LOAD --- */
function design_load(data) {
    // promise
	return new Promise(async function(resolve) {
		// paper load
		await design_load_paper(data.paper);
		// poster load
		await design_load_poster(data.poster);
		// font load
		await design_load_font(data.font);
		// map load
		await design_load_map(data.layer.map);
		// overlay load
		await design_load_overlay(data.layer.overlay);
		// // activity load
		await design_load_activity(data.layer.activity);
		// // label load
		await design_load_label(data.layer.label);
		// outline load
		await design_load_outline(data.layer.outline);
		// // text load
		await design_load_text(data.layer.text);
		// elevation load
		await design_load_elevation(data.layer.elevation);

        // resolve
        resolve();
    });
}

/* ------------------------------------------------------------- LOAD : PAPER --- */
function design_load_paper(paper) {
	// promise
	return new Promise(function(resolve) {
		var $group = {};

		// add paper size class
		browser_class(['paper_size', paper.size,].join('-'));
		// add paper orientation class
		browser_class(['paper_orientation', paper.orientation,].join('-'));

		// cache elements
		$group.size = jQuery('aside.option .group.size');
		$group.orientation = jQuery('aside.option .group.orientation');

		// size
		$group.size.find('a.item').filter('[data-design-value=' + paper.size + ']').addClass('active');
		// orientation
		$group.orientation.find('a.item').filter('[data-design-value=' + paper.orientation + ']').addClass('active');

		// scale
		poster_scale();

		// store data
		DESIGN.paper = paper;

		// resolve
		resolve();
	});
}

/* ------------------------------------------------------------ LOAD : POSTER --- */
function design_load_poster(poster) {
	// promise
	return new Promise(async function(resolve) {
		var $group;

		// add poster style class
		browser_class(['poster_style', poster.style,].join('-'));

		// cache element
		$group = jQuery('aside.option .group.style');

		// style
		$group.find('a.item').filter('[data-design-value=' + poster.style + ']').addClass('active');

		// enabled
		if (APP.basemap.enable === true) {
			// style
			await basemap_style('map', MAPBOX.style[poster.style]);
		}

		// store data
		DESIGN.poster = poster;

		// resolve
		resolve();
    });
}

/* -------------------------------------------------------------- LOAD : FONT --- */
function design_load_font(font) {
	// promise
	return new Promise(function(resolve) {
		var $group = {};

		// add font family class
		browser_class(['font_family', font.family,].join('-'));
		// add font size class
		browser_class(['font_size', font.size,].join('-'));

		// cache elements
		$group.family = jQuery('aside.option .group.font_family');
		$group.size = jQuery('aside.option .group.font_size');

		// family
		$group.family.find('a.item').filter('[data-design-value=' + font.family + ']').addClass('active');
		// size
		$group.size.find('a.item').filter('[data-design-value=' + font.size + ']').addClass('active');

		// store data
		DESIGN.font = font;

		// resolve
		resolve();
	});
}

/* --------------------------------------------------------------- LOAD : MAP --- */
function design_load_map(map) {
    // promise
	return new Promise(async function(resolve) {
		var $group;

		// cache element
		$group = jQuery('aside.option .group.style');

		// style
		$group.find('a.item').filter('[data-design-value=' + map.style + ']').addClass('active');

		// enabled
		if (APP.basemap.enable === true) {
			// bound
			basemap_bound(map.bound, false, false);
		}

		// store data
		DESIGN.layer.map = map;

		// enabled
		if (APP.basemap.enable === true) {
			// resolve
			BASEMAP.main.once('idle', resolve);
		}
		// disabled
		else {
			// resolve
			resolve();
		}
    });
}

/* ----------------------------------------------------------- LOAD : OVERLAY --- */
function design_load_overlay(overlay) {
	// promise
	return new Promise(function(resolve) {
		var $group;

		// add overlay type class
		browser_class(['overlay_type', overlay.type,].join('-'));

		// cache element
		$group = jQuery('aside.option .group.overlay');

		// type
		$group.find('a.item').filter('[data-design-value=' + overlay.type + ']').addClass('active');

		// store data
		DESIGN.layer.overlay = overlay;

		// resolve
		resolve();
	});
}

/* ---------------------------------------------------------- LOAD : ACTIVITY --- */
function design_load_activity(activity) {
    // promise
	return new Promise(async function(resolve) {
		var $group = {};		

		// add activity line width class
		browser_class(['activity_line_width', activity.line_width,].join('-'));
		// add activity point finish class
		browser_class(['activity_point_finish', activity.point_finish,].join('-'));
		// add activity point activity class
		browser_class(['activity_point_activity', activity.point_activity,].join('-'));
		
		// cache elements
		$group.line_width = jQuery('aside.option .group.line_width');
		$group.point_finish = jQuery('aside.option .group.point_finish')
		$group.point_activity = jQuery('aside.option .group.point_activity');

		// weight
		$group.line_width.find('a.item').filter('[data-design-value=' + activity.line_width + ']').addClass('active');
		// point finish
		$group.point_finish.find('a.item').filter('[data-design-value=' + activity.point_finish + ']').addClass('active');
		// point track
		$group.point_activity.find('a.item').filter('[data-design-value=' + activity.point_activity + ']').addClass('active');

		// process activities
		for (var a = 0; a < activity.item.length; a++) {
			var data;

			// store data
			data = activity.item[a];

			// insert track
			activity_insert(data);
		}

		// store data
		DESIGN.layer.activity.line_width = activity.line_width;
		DESIGN.layer.activity.point_finish = activity.point_finish;
		DESIGN.layer.activity.point_activity = activity.point_activity;

		// enabled
		if (APP.basemap.enable === true) {
			// width line
			activity_line_width();

			// process point
			activity_point_process();
		}

        // resolve
        resolve();
	});
}

/* ------------------------------------------------------------- LOAD : LABEL --- */
function design_load_label(label) {
	// promise
	return new Promise(function(resolve) {
		// process labels
		for (var a = 0; a < label.item.length; a++) {
			var data;

			// store data
			data = label.item[a];

			// insert
			label_insert(data);
		}

		// resolve
        resolve();
	});
}

/* ------------------------------------------------------------ LOAD: OUTLINE --- */
function design_load_outline(outline) {
	// promise
	return new Promise(async function(resolve) {
		var $group;

		// add outline type class
		browser_class(['outline_type', outline.type,].join('-'));

		// cache element
		$group = jQuery('aside.option .group.outline');

		// type
		$group.find('a.item').filter('[data-design-value=' + outline.type + ']').addClass('active');

		// style
		await poster_style(DESIGN.poster.style);

		// store data
		DESIGN.layer.outline = outline;

		// resolve
		resolve();
	});
}

/* -------------------------------------------------------------- LOAD : TEXT --- */
function design_load_text(text) {
	// promise
	return new Promise(function(resolve) {
		var $section,
			$group;

		// cache elements
		$section = jQuery('section.layer.text');
		$group = jQuery('aside.option .group.text');

		// headline
		$section.find('.headline').text(text.headline).arctext({ radius: 8000, });
		$group.find('[name*=headline').val(text.headline);

		// subtitle
		$section.find('.subtitle').text(text.subtitle);
		$group.find('[name*=subtitle').val(text.subtitle);

		// footnote
		$section.find('.footnote').text(text.footnote);
		$group.find('[name*=footnote').val(text.footnote);

		// metadata
		$section.find('.metadata').text(text.metadata);
		$group.find('[name*=metadata').val(text.metadata);

		// store data
		DESIGN.layer.text = text;

		// resolve
		resolve();
	});
}

/* --------------------------------------------------------- LOAD : ELEVATION --- */
function design_load_elevation(elevation) {
	// promise
	return new Promise(function(resolve) {
		var $group = {};

		// add elevation enable class
		browser_class(['elevation_enable', elevation.enable,].join('-'));
		// add elevation multiply class
		browser_class(['elevation_multiply', elevation.multiply,].join('-'));

		// cache elements
		$group.enable = jQuery('aside.option .group.elevation_enable');
		$group.multiply = jQuery('aside.option .group.elevation_multiply');

		// enable
		$group.enable.find('a.item').filter('[data-design-value=' + elevation.enable + ']').addClass('active');
		// multiply
		$group.multiply.find('a.item').filter('[data-design-value=' + elevation.multiply + ']').addClass('active');

		// destroy
		elevation_destroy();

		// enabled
		if (elevation.enable === true) {
			// elevation
			elevation_process();

			// add display class
			$group.multiply.addClass('display');
		}

		// store data
		DESIGN.layer.elevation = elevation;

		// resolve
		resolve();
	});
}

/* ----------------------------------------------------------- ORDER : RENDER --- */
function design_order_render() {
	var $section,
		$footer;
	var variant,
		order = {};

	// cache elements
	$section = jQuery('aside.option').find('section.stage.display section.step.display');
	$footer = $section.find('footer');

	// validate
	if (empty(WORDPRESS.product)) return;

	// determine active variant
	variant = jQuery.grep(WORDPRESS.product.variants, function(tmp) {
		return tmp.metafields.size === DESIGN.paper.size && tmp.metafields.style === DESIGN.poster.style;
	})[0];

	// bail when variant not found
	if (empty(variant)) return;

	// build order data
	order.size = jQuery('.group.size a.item').filter('[data-design-value=' + DESIGN.paper.size + ']').text();
	order.size = jQuery.trim(order.size.replace(/ *\([^)]*\) */g, ''));
	order.price = (variant.price / 100).toFixed(2);

	// render order
	$footer.find('span.size').text(order.size);
	$footer.find('span.price').text(order.price);
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////// BASEMAP /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function basemap_init() {
	// event
	basemap_event();
}

/* --------------------------------------------------------------------- LOAD --- */
function basemap_load(basemap, type) {
    // promise
	return new Promise(async function(resolve) {
        var $basemap;
        var payload,
            target;
        
        // cache elements
        $basemap = jQuery(basemap);

		// bust cache
		$basemap.attr('src', $basemap.attr('src'));

        // build basemap
        BASEMAP[type] = {};

        // build message
        target = $basemap[0].contentWindow;
        payload = { layer: { type: type, }, };

        // listen for load
        await async_listener($basemap, 'load');
		// thread load
		await sleep(1);


		// post message
		message_post(target, 'basemap_render', payload, 'basemap_callback');
		

		// wait for basemap to render
		BASEMAP[type].request = jQuery.Deferred();
	
		BASEMAP[type] = await BASEMAP[type].request;
		

		// style
		await basemap_style(type, MAPBOX.style.basemap);
	

		// determine whether current basemap is main
		if (APP.basemap.main === type) {
			// trigger main.load
			APP.trigger('main.load');

			// store main basemap
			BASEMAP.main = BASEMAP[type];
		}

		// resolve
		resolve();
    });
}

/* ----------------------------------------------------------------- CALLBACK --- */
function basemap_callback(event) {
	var type;

	// store layer type
	type = event.data.payload.layer.type;

	// trigger basemap.load
	APP.trigger('basemap.load');

	// resolve
	BASEMAP[type].request.resolve(event.source.BASEMAP);
}

/* -------------------------------------------------------------------- EVENT --- */
function basemap_event() {
	// async
	(async function() {
	
		// listen for ready
		await async_listener(APP, 'main.load');
			

		// move event
		basemap_event_move();
	})();
}

/* ------------------------------------------------------------- EVENT : MOVE --- */
function basemap_event_move() {
	// listen for move
	BASEMAP.main.on('move', function() {
		var state = {};

		// store state
		state.center = BASEMAP.main.getCenter();
		state.zoom = BASEMAP.main.getZoom();

		// mirror
		basemap_mirror(state);
	});
}

/* ------------------------------------------------------------------- MIRROR --- */
function basemap_mirror(state) {
    // process basemaps
	for (var type in BASEMAP) {
		// skip when basemap is source
		if (type === 'main' || type === APP.basemap.main) continue;

		// mirror states
		BASEMAP[type].setCenter(state.center);
		BASEMAP[type].setZoom(state.zoom);
	}
}

/* -------------------------------------------------------------------- STYLE --- */
function basemap_style(type, style) {
	// promise
	return new Promise(async function(resolve) {
		// set style
		BASEMAP[type].setStyle(['mapbox://styles', style,].join('/'));

		// listen for style.load
		BASEMAP[type].on('style.load', function() {
			var timer;

			// remove event listener
			BASEMAP[type].off('style.load');
			
			// poll style load state
			timer = setInterval(function() {
				// determine whether style loaded
				if (BASEMAP[type].isStyleLoaded()) {
					// destroy timer
					clearInterval(timer);
					// resolve
					resolve();
				}
			}, 100);
		});
	});
}

/* -------------------------------------------------------------------- BOUND --- */
function basemap_bound(bounds, padding, animate) {
	var padding,
		camera;

	// validate
	if (padding !== false) {
		// store padding
		padding = basemap_padding();
	}

	// determine camera
	camera = BASEMAP.main.cameraForBounds(bounds, { padding: padding, });

	// center on camera
	if (animate !== false) BASEMAP.main.easeTo(camera);
	else BASEMAP.main.jumpTo(camera);
}

/* ------------------------------------------------------------------ PADDING --- */
function basemap_padding() {
	var $basemap;
	var padding;

	// cache element
	$basemap = jQuery(['section', 'layer', APP.basemap.main,].join('.'));

	// determine basemap padding
	padding = {
		top: $basemap.css('padding-top'),
		right: $basemap.css('padding-right'),
		bottom: $basemap.css('padding-bottom'),
		left: $basemap.css('padding-left'),
	};

	// format padding
	Object.keys(padding).map(function(key) { padding[key] = parseInt(padding[key]); });

	return padding;
}

/* --------------------------------------------------------------------- ZOOM --- */
function basemap_zoom(action) {
	var zoom,
		step = 0.5;

	// store map zoom
	zoom = BASEMAP.main.getZoom();

	// positive
	if (action === 'positive') zoom = (zoom + step);
	// negative
	else if (action === 'negative') zoom = (zoom - step);

	// update zoom
	BASEMAP.main.flyTo({ zoom: zoom, });
}

/* ------------------------------------------------------------------- STATIC --- */
function basemap_static(geojson, width, height) {
	var coordinates,
		tolerance,
		url;

	// format geojson
	geojson.features = geojson.features.filter(function(feature) { return feature.geometry.type === 'LineString'; });
	// retrieve coordinates
	coordinates = geojson.features.flatMap(function(feature) { return turf.getCoords(feature); });

	// determine simplification tolerance
	tolerance = 0.0001;
	if (coordinates.length > 250) tolerance = 0.005;

	// simplify geojson
	geojson = turf.simplify(geojson, { tolerance: tolerance, });

	// root
	url = 'https://api.mapbox.com/styles/v1';
	// style
	url += '/mapbox/outdoors-v11';
	// static
	url += '/static';
	// overlay
	url += '/geojson(' + encodeURIComponent(JSON.stringify(geojson)) + ')';
	// bound
	url += '/auto';
	// dimensions
	url += '/' + [width, height,].join('x');
	// retina
	url += '@2x';
	// additional params
	url += '?' + jQuery.param({ access_token: MAPBOX.token, logo: false, attribution: false, });

	return url;
}

/* ------------------------------------------------------- COORDINATE : BOUND --- */
function basemap_coordinate_bound(coordinates) {
	var bounds;

	// reduce coordinates to bounds
	bounds = coordinates.reduce(function(bounds, coordinates) {
		return bounds.extend(coordinates);
	}, new mapboxgl.LngLatBounds(coordinates[0], coordinates[1]));

	return bounds;
}

/* ---------------------------------------------------------- FEATURE : QUERY --- */
function basemap_feature_query(type, source, point, threshold) {
	var feature,
		bounds;

	// default value
	threshold = threshold || 2;

	// build enlarged click bounds
	bounds = [[(point.x - threshold), (point.y - threshold),], [(point.x + threshold), (point.y + threshold),],];
	// query bounds for feature
	feature = BASEMAP[type].queryRenderedFeatures(bounds, { layers: [source,], })[0];

	return feature;
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////// PREVIEW /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function preview_init() {
	//
}

/* ------------------------------------------------------------------ PREPARE --- */
function preview_prepare() {
    // promise
	return new Promise(async function(resolve, reject) {
        // layer
      
		try { await layer_prepare(); } catch { return reject(); }
		
        // design
		try { await design_prepare(); } catch { return reject(); }


        // resolve
        resolve();
    });
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////////////////////////////////////////////// PAPER /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function paper_init() {
	//
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function paper_design_action(event, key) {
	var $target;
	var value,
		child;

	// cache elements
	$target = jQuery(event.currentTarget);

	// determine child
	child = key.split('_').slice(1).join('_');
	// store value
	value = $target.attr('data-design-value');

	// add paper class
	browser_class([key, value].join('-'));

	// scale
	poster_scale();

	// enabled
	if (APP.basemap.enable === true) {
		// bound
		setTimeout(activity_bound, 100);
	}

	// store value
	DESIGN.paper[child] = value;

	// render order
	design_order_render();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////// POSTER /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function poster_init() {
	// event
	poster_event();
}

/* -------------------------------------------------------------------- EVENT --- */
function poster_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// resize event
    	poster_event_resize();
	})();
    
}

/* ----------------------------------------------------------- EVENT : RESIZE --- */
function poster_event_resize() {
    // listen for resize
    jQuery(window).on('resize', debounce(function() {
        // scale
        poster_scale();
    }, 100));
}

/* -------------------------------------------------------------------- SCALE --- */
function poster_scale() {
    var $preview,
        $poster;
	var scale;

    // cache elements
	$preview = jQuery('section.preview');
    $poster = jQuery('section.poster');

    // calculate scale
	scale = Math.min( 
		($preview.width() / $poster.outerWidth()), 
		($preview.height() / $poster.outerHeight())
	);

    // set poster scale
	$poster.css('--poster-scale', scale);
}

/* ----------------------------------------------------------- SCALE : TOGGLE --- */
function poster_scale_toggle(event) {
	var $section,
		$target;
	var scroll = {};

	// cache elements
	$section = jQuery('section.preview');
	$target = jQuery(event.currentTarget);

	// toggle active class
	jQuery('html').toggleClass('scale_active');
	$target.toggleClass('active');

	// determine scroll center
	scroll.top = (($section.prop('scrollHeight') - $section.height()) / 2);
	scroll.left = (($section.prop('scrollWidth') - $section.width()) / 2);

	// re-center scroll
	$section.scrollTop(scroll.top).scrollLeft(scroll.left);
}

/* -------------------------------------------------------------------- STYLE --- */
function poster_style(style) {
	// promise
	return new Promise(async function(resolve) {
		var color = {};

		// add poster style class
		browser_class(['poster_style', style,].join('-'));

		// store colors
		color.activity = jQuery.trim(jQuery(':root').css('--style-activity'));

		// enabled
		if (APP.basemap.enable === true) {
			// validate
			if (style !== DESIGN.poster.style) {
				// map
				await basemap_style('map', MAPBOX.style[style]);
			}
			// activity
			BASEMAP.activity.setPaintProperty('activity-track', 'line-color', color.activity);
		}

		// enabled
		if (DESIGN.layer.elevation.enable === true) {
			// elevation
			elevation_process();
		}

		// resolve
		resolve();
	});
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function poster_design_action(event, key) {
	// async
	(async function() {
		var $target;
		var value,
			child;

		// cache element
		$target = jQuery(event.currentTarget);

		// determine child
		child = key.split('_').slice(1).join('_');
		// store value
		value = $target.attr('data-design-value');

		// add poster class
		browser_class([key, value].join('-'));

		// child
		switch (child) {
			case 'style' :
				// style
				await poster_style(value);
			break;
		}

		// store value
		DESIGN.poster[child] = value;

		// render order
		design_order_render();
	})();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////////////////////////////////////////////// FONT /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function font_init() {
	//
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function font_design_action(event, key) {
	var $target;
	var value,
		child;

	// cache elements
	$target = jQuery(event.currentTarget);

	// determine child
	child = key.split('_').slice(1).join('_');
	// store value
	value = $target.attr('data-design-value');

	// add font class
	browser_class([key, value].join('-'));

	// store value
	DESIGN.font[child] = value;
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////////////////////////////////////////////// LAYER /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function layer_init() {
	//
}

/* ------------------------------------------------------------------ PREPARE --- */
function layer_prepare() {
	// promise
	return new Promise(async function(resolve) {
		var promises = [];

		// enabled
		if (APP.basemap.enable === true) {
			// map load
			promises.push(map_load());
			// activity load
			promises.push(activity_load());
			// label load
			promises.push(label_load());
			// elevation load
			promises.push(elevation_load());
		} else {
			promises.push(elevation_load());
		}
		

		// wait for promises
        await Promise.all(promises);

        // resolve
        resolve();
	});
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////////// MAP /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function map_init() {
	// event
	map_event();
}

/* --------------------------------------------------------------------- LOAD --- */
function map_load() {
	// promise
	return new Promise(async function(resolve) {
		var $basemap,
			$credit;

		// cache elements
		$basemap = jQuery('section.layer.map iframe.basemap');
		$credit = jQuery('section.credit');

		// load
		await basemap_load($basemap, 'map');

		// attribution size
		BASEMAP.map.addControl(new mapboxgl.AttributionControl({ compact: true, }));
		// move attribution elements
		$basemap.contents().find('.mapboxgl-ctrl-bottom-left, .mapboxgl-ctrl-bottom-right').detach().appendTo($credit);
        
		// scale
		poster_scale();

		// trigger map.load
		APP.trigger('map.load');

		// resolve
		resolve();
	});
}

/* -------------------------------------------------------------------- EVENT --- */
function map_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// validate
		if (APP.basemap.enable === false) return;

		// moveend event
		map_event_moveend();
	})();
}

/* ---------------------------------------------------------- EVENT : MOVEEND --- */
function map_event_moveend() {
	// listen for moveend
	BASEMAP.map.on('moveend', function() {
		// store zoom
		DESIGN.layer.map.zoom = BASEMAP.map.getZoom();
		// store center
		DESIGN.layer.map.center = BASEMAP.map.getCenter().toArray();
		// store bounds
		DESIGN.layer.map.bound = BASEMAP.map.getBounds().toArray();

		// store
		design_store();
	});
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////// OVERLAY /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function overlay_init() {
	//
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function overlay_design_action(event, key) {
	var $target;
	var value,
		child;

	// cache elements
	$target = jQuery(event.currentTarget);

	// determine child
	child = key.split('_').slice(1).join('_');
	// store value
	value = $target.attr('data-design-value');

	// add overlay class
	browser_class([key, value].join('-'));

	// store value
	DESIGN.layer.overlay[child] = value;
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////////////////////////////////////////// ACTIVITY /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function activity_init() {
	// event
	activity_event();

	// position prepare
	activity_position_prepare();

	// strava validate
	activity_strava_validate();
}

/* --------------------------------------------------------------------- LOAD --- */
function activity_load() {
	// promise
	return new Promise(async function(resolve) {
		var $basemap;

		// cache element
		$basemap = jQuery('section.layer.activity iframe.basemap');

		// load
		await basemap_load($basemap, 'activity');

		// trigger activity.load
		APP.trigger('activity.load');

		// prepare source
		activity_source_prepare();

		// prepare layer
		activity_layer_prepare();

		// resolve
		resolve();
	});
}

/* -------------------------------------------------------------------- EVENT --- */
function activity_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// input event
		activity_event_input();

		// click event
		activity_event_click();

		// sortupdate event
		activity_event_sortupdate();
	})();
}

/* ------------------------------------------------------------ EVENT : INPUT --- */
function activity_event_input() {
	// listen for input
	jQuery('aside.option').on('input', '.transfer .item.upload input', function(event) {
		// file upload
		activity_upload_file(event);
	});

	// listen for input
	jQuery('aside.option').on('input', 'section.step.strava .field.search input', function(event) {
		// query strava
		activity_strava_query(event);
	});
}

/* ------------------------------------------------------------ EVENT : CLICK --- */
function activity_event_click() {
	// listen for click
	jQuery('aside.option').on('click', 'section.step.inventory section.list .item a.delete', function() {
		var $item;
		var activity = {};

		// cache element
		$item = jQuery(this).closest('.item');

		// store activity id
		activity.id = $item.attr('data-activity-id');

		// retrieve activity
		data = jQuery.grep(DESIGN.layer.activity.item, function(tmp) { return tmp.id === activity.id; })[0];

		// delete
		activity_delete(data);

		// enabled
		if (APP.basemap.enable === true) {
			// process point
			activity_point_process();

			// bound
			activity_bound();
		}

		// enabled
		if (DESIGN.layer.elevation.enable === true) {
			// elevation
			elevation_process();
		}

		// render metadata
		activity_metadata_render();

		// store
		design_store();

		// validate
		if (DESIGN.layer.activity.item.length === 0) {
			// view
			view_toggle('activity:intro');
		}
	});
}

/* ------------------------------------------------------- EVENT : SORTUPDATE --- */
function activity_event_sortupdate() {
	// listen for click
	jQuery('aside.option').on('sortupdate', 'section.step.inventory section.list', function() {
		// process position
		activity_position_process();
	});
}

/* ------------------------------------------------------------------- INSERT --- */
function activity_insert(data) {
	// insert render
	activity_render_insert(data);

	// enabled
	if (APP.basemap.enable === true) {
		// insert source
		activity_source_insert(data.file.geojson.features);
	}

	// insert activity
	DESIGN.layer.activity.item.push(data);
}

/* ------------------------------------------------------------------- DELETE --- */
function activity_delete(data) {
	// delete render
	activity_render_delete(data);

	// enabled
	if (APP.basemap.enable === true) {
		// delete source
		activity_source_delete(data.file.geojson.features);
	}

	// delete activity
	DESIGN.layer.activity.item = jQuery.grep(DESIGN.layer.activity.item, function(activity) { return activity.id !== data.id; });
}

/* --------------------------------------------------------------- COORDINATE --- */
function activity_coordinate(data) {
	var coordinates = [];

	// store activities
	data = data || DESIGN.layer.activity.item;

	// format
	if (!Array.isArray(data)) data = [data,];

	// process activities
	for (var a = 0; a < data.length; a++) {
		var features;

		// store features
		features = data[a].file.geojson.features;

		// process features
		for (var b = 0; b < features.length; b++) {
			// retrieve coordinates
			if (features[b].geometry.type === 'Point') coordinates.push(turf.getCoord(features[b]));
			else coordinates = coordinates.concat(turf.getCoords(features[b]));
		}
	}

	return coordinates;
}

/* ---------------------------------------------------------------- MULTILINE --- */
function activity_multiline(data) {
	var multiline = [],
		lines = [];

	// store activities
	data = data || DESIGN.layer.activity.item;

	// format
	if (!Array.isArray(data)) data = [data,];

	// process activities
	for (var a = 0; a < data.length; a++) {
		var coordinates,
			lines;

		// process tracks
		for (var b = 0; b < data[a].file.data.trk.length; b++) {
			// process track segments
			for (var c = 0; c < data[a].file.data.trk[b].trkseg.length; c++) {
				var points,
					tmp;

				// format points
				points = data[a].file.data.trk[b].trkseg[c].points.map(function(point) { return [point.lon, point.lat,]; });
				// convert to line string
				tmp = turf.lineString(points);

				// store line
				lines.push(tmp);
			}
		}

		// process lines
		for (var d = 0; d < lines.length; d++) {
			// convert to coordinates
			coordinates = turf.getCoords(lines[d]);

			// store coordinates
			multiline.push(coordinates);
		}
	}

	// convert to multiline string
	multiline = turf.multiLineString(multiline);

	return multiline;
}

/* -------------------------------------------------------------------- BOUND --- */
function activity_bound() {
	var coordinates,
		bounds;

	// bail when no activities
	if (empty(DESIGN.layer.activity.item)) return;

	// retrieve coordinates
	coordinates = activity_coordinate();
	// reduce coordinates to bounds
	bounds = basemap_coordinate_bound(coordinates);

	// bound map
	basemap_bound(bounds.toArray());
}

/* ---------------------------------------------------------------- ELEVATION --- */
function activity_elevation(data) {
	var elevations = [];

	// process tracks
	for (var a = 0; a < data.trk.length; a++) {
		// process track segments
		for (var b = 0; b < data.trk[a].trkseg.length; b ++) {
			var tmp;

			// format elevations
			tmp = data.trk[a].trkseg[b].points.map(function(point) { return point.ele; });
			// merge elevations
			elevations = elevations.concat(tmp);
		}
	}

	return elevations;
}

/* --------------------------------------------------------- SOURCE : PREPARE --- */
function activity_source_prepare() {
	// create empty track source
	BASEMAP.activity.addSource('activity-track', { 
		type: 'geojson',
		data: {
			type: 'FeatureCollection',
			features: [],
		},
	});
}

/* ---------------------------------------------------------- SOURCE : INSERT --- */
function activity_source_insert(data) {

	var features,
		source;

	// retrieve source
	source = BASEMAP.activity.getSource('activity-track');

	// retrieve track features
	features = DESIGN.layer.activity.item.flatMap(function(tmp) { return tmp.file.geojson.features; });
	// merge track features
	features = features.concat(data);
	console.log(features);

	// update data
	source.setData({
		type: 'FeatureCollection',
		features: features,
	});
}

/* ---------------------------------------------------------- SOURCE : DELETE --- */
function activity_source_delete(data) {
	var features,
		source;

	// retrieve source
	source = BASEMAP.activity.getSource('activity-track');

	// retrieve features
	features = DESIGN.layer.activity.item.flatMap(function(tmp) { return tmp.file.geojson.features; });

	// process features
	for (var a = 0; a < data.length; a++) {
		// delete track feature
		features = jQuery.grep(features, function(feature) { return feature.properties.id !== data[a].properties.id; });
	}
	
	// update data
	source.setData({
		type: 'FeatureCollection',
		features: features,
	});
}

/* ---------------------------------------------------------- LAYER : PREPARE --- */
function activity_layer_prepare() {
	// create empty track layer
	BASEMAP.activity.addLayer({
		id: 'activity-track',
		type: 'line',
		source: 'activity-track',
		paint: {
			'line-color': '#33C9EB',
			'line-width': 3
		},
		layout: {
			'line-join': 'round',
		},
	});
}

/* ---------------------------------------------------------- RENDER : INSERT --- */
function activity_render_insert(data) {
	var $section,
		$item;
	var picture;

	// cache element
	$section = jQuery('section.step.inventory section.list');

	// generate static basemap
	picture = basemap_static(jQuery.extend(true, {}, data.file.geojson), 160, 160);

	// clone
	$item = $section.find('.item.clone').clone(true, true).removeClass('clone');

	// id
	$item.attr('data-activity-id', data.id);
	// picture
	$item.find('.picture').css('background-image', 'url(\'' + picture + '\')');
	// name
	$item.find('h4').text(data.name);
	// time
	$item.find('span.time').text(moment(data.time).format('DD.MM.YY'));
	// distance
	$item.find('span.distance').text((data.data.distance / 1000).toFixed(1));
	// duration
	$item.find('span.duration').text(format_second(data.data.time.moving));

	// append
	$section.append($item);
}

/* ---------------------------------------------------------- RENDER : DELETE --- */
function activity_render_delete(data) {
	var $section;

	// cache element
	$section = jQuery('section.step.inventory section.list');

	// destroy render
	$section.find('.item[data-activity-id=' + data.id + ']').remove();
}

/* ------------------------------------------------------- POSITION : PREPARE --- */
function activity_position_prepare() {
	var $section;

	// cache elements
	$section = jQuery('section.step.inventory section.list.position');

	// initialize sortable
	$section.sortable({
		items: '.item:not(.clone)',
		containment: 'section.step.inventory',
		placeholder: 'placeholder',
		revert: true,
		start: function(_, ui) { ui.placeholder.height((ui.item.outerHeight() - 1)); },
	});
}

/* ------------------------------------------------------- POSITION : PROCESS --- */
function activity_position_process() {
	var $section;
	var position = [],
		data,
		tmp = [];

	// cache elements
	$section = jQuery('section.step.inventory section.list.position');

	// process items
	$section.find('.item:not(.clone)').each(function() {
		var $item;
		var activity = {};

		// cache element
		$item = jQuery(this);

		// store activity id
		activity.id = $item.attr('data-activity-id');

		// store activity position
		position.push(activity);
	});

	// process positions
	for (var a = 0; a < position.length; a++) {
		// retrieve activity
		data = jQuery.grep(DESIGN.layer.activity.item, function(tmp) { return tmp.id === position[a].id; })[0];
		// store activity
		tmp.push(data);
	}

	// overwrite activities with new position
	DESIGN.layer.activity.item = tmp;

	// enabled
	if (DESIGN.layer.elevation.enable === true) {
		// elevation
		elevation_process();
	}

	// update
	design_store();
}

/* -------------------------------------------------------- STRAVA : VALIDATE --- */
function activity_strava_validate() {
	// validate
	strava_validate();

	// validate
	if (!empty(STRAVA.token)) {
		// add active class
		jQuery('html').addClass('strava_active');
	}
}

/* ------------------------------------------------------- STRAVA : AUTHORIZE --- */
function activity_strava_authorize() {

	// define callback
	STRAVA.callback = 'activity_strava_callback';

	// authorize
	strava_auth_authorize();
}

/* -------------------------------------------------------- STRAVA : CALLBACK --- */
function activity_strava_callback() {
	// validate
	strava_validate();


	// explore strava
	activity_strava_explore();
}

/* --------------------------------------------------------- STRAVA : EXPLORE --- */
function activity_strava_explore() {
	// async
	(async function() {
		var data;

		// view
		view_toggle('activity:strava');
		
		// validate
		if (empty(STRAVA.activity)) {
			// add active class
			jQuery('html').addClass('search_active');

			// select activity
			
			STRAVA.activity = await strava_activity_select();

			// remove active class
			jQuery('html').removeClass('search_active');
		}

		// preview most recent
		data = STRAVA.activity.slice(0, 10);

		// destroy render
		jQuery('section.step.strava section.list').find('.item:not(.clone)').remove();

		// process activities
		for (var a = 0; a < data.length; a++) {
			// render strava
			activity_strava_render(data[a]);
		}

		// mask
		option_mask();
	})();
}

/* ----------------------------------------------------------- STRAVA : QUERY --- */
function activity_strava_query(event) {
	var $input;
	var response = [],
		value;

	// cache element
	$input = jQuery(event.currentTarget);

	// store query value
	value = $input.val();
	// convert to uppercase for more reliable string matching
	value = value.toUpperCase();

	// process activities
	for (var a = 0; a < STRAVA.activity.length; a++) {
		var match = false,
			data;

		// store activity
		data = STRAVA.activity[a];

		// name
		if (!empty(data.name) && data.name.toUpperCase().indexOf(value) !== -1) match = true;

		// bail when no match
		if (match === false) continue;

		// store activity
		response.push(data);
	}

	// destroy render
	jQuery('section.step.strava section.list').find('.item:not(.clone)').remove();

	// process activities
	for (var a = 0; a < response.length; a++) {
		// render strava
		activity_strava_render(response[a]);
	}

	// mask
	option_mask();
}

/* ---------------------------------------------------------- STRAVA : RENDER --- */
function activity_strava_render(data) {
	var $section,
		$item;

	// cache elements
	$section = jQuery('section.step.strava section.list');

	// clone
	$item = $section.find('.item.clone').clone().removeClass('clone');

	// id
	$item.attr('data-activity-id', data.id);
	// name
	$item.find('h4').text(data.name);
	// time
	$item.find('span.time').text(moment(data.time).format('DD.MM.YY'));
	// distance
	$item.find('span.distance').text((data.data.distance / 1000).toFixed(1));
	// duration
	$item.find('span.duration').text(format_second(data.data.time.moving));

	// determine whether activity already added
	if (jQuery.grep(DESIGN.layer.activity.item, function(tmp) { return tmp.id === data.id; })[0]) {
		// add active class
		$item.addClass('active');
	}

	// render
	$section.append($item);
}

/* ---------------------------------------------------------- STRAVA : TOGGLE --- */
function activity_strava_toggle(event) {
	// async
	(async function() {
		var $target,
			$item;
		var activity = {},
			data;

		// cache elements
		$target = jQuery(event.currentTarget);
		$item = $target.closest('.item');

		// store activity id
		activity.id = $item.attr('data-activity-id');

		// insert
		if (!$item.is('.active')) {
			// add loading class
			$item.addClass('loading');

			// strava activity
			data = await strava_activity_get(activity.id);

			// add loading class
			$item.removeClass('loading');

			// bail when no activities activity
			if (empty(data)) {
				// add failed class
				$item.addClass('failed');

				return;
			}

			// add active class
			$item.addClass('active');

			// insert
			activity_insert(data);
		}
		// delete
		else {
			// remove active class
			$item.removeClass('active');

			// retrieve activity
			data = jQuery.grep(DESIGN.layer.activity.item, function(tmp) { return tmp.id === activity.id; })[0];

			// delete
			activity_delete(data);
		}

		// enabled
		if (APP.basemap.enable === true) {
			// process point
			activity_point_process();

			// bound
			activity_bound();
		}

		// enabled
		if (DESIGN.layer.elevation.enable === true) {
			// elevation
			elevation_process();
		}

		// render metadata
		activity_metadata_render();

		// store
		design_store();
	})();
}

/* ------------------------------------------------------------ UPLOAD : FILE --- */
function activity_upload_file(event) {
	// async
	(async function() {
		var $input;
		var promises = [],
			data;

		// cache element
		$input = jQuery(event.currentTarget);

		// store file data
		data = $input[0].files;

		// process files
		for (var a = 0; a < data.length; a++) {
			// alert
			// alert_render({
			// 	type: 'info',
			// 	name: 'Processing Activity',
			// 	text: `Uploading ${data[a].name} and parsing data.`,
			// });

			// parse upload
			promises.push(activity_upload_parse(data[a]));
		}

		// reset input
		$input.val('');

		// wait for promises
		data = await Promise.all(promises);
		// remove failed data
		data = data.filter(Boolean);

		// process data
		for (var a = 0; a < data.length; a++) {
			// insert
			activity_insert(data[a]);
		}

		// enabled
		if (APP.basemap.enable === true) {
			// process point
			activity_point_process();

			// bound
			activity_bound();
		}

		// enabled
		if (DESIGN.layer.elevation.enable === true) {
			// elevation
			elevation_process();
		}

		// render metadata
		activity_metadata_render();

		// store
		design_store();

		// view
		view_toggle('activity:inventory');
	})();
}

/* ----------------------------------------------------------- UPLOAD : PARSE --- */
function activity_upload_parse(data) {
	// promise
	return new Promise(async function(resolve) {
		var response,
			fd;

		// initialize form data
		fd = new FormData();

		// build file data
		fd.append('file', data);

		// request
		try { response = await api_request('post', '/activity/parse', fd); } catch(error) {
			// alert
			// alert_render({
			// 	type: 'negative',
			// 	name: 'Processing Failed',
			// 	text: error.responseJSON.error.description,
			// });

			// resolve
			return resolve();
		}

		// resolve
		resolve(response.data.activity);
	});
}

/* ------------------------------------------------------------- LINE : WIDTH --- */
function activity_line_width() {
	var width;

	// store line width
	width = DESIGN.layer.activity.line_width;
	// paint property
	BASEMAP.activity.setPaintProperty('activity-track', 'line-width', width);
}

/* ---------------------------------------------------------- POINT : PROCESS --- */
function activity_point_process() {
	// destroy point
	activity_point_destroy();

	// enabled
	if (DESIGN.layer.activity.point_finish === true) {
		// finish point
		activity_point_finish();
	}

	// enabled
	if (DESIGN.layer.activity.point_activity === true) {
		// activity point
		activity_point_activity();
	}
}

/* ----------------------------------------------------------- POINT : FINISH --- */
function activity_point_finish() {
	var point_activity,
		points = [],
		data,
		prev;

	// store activities
	data = DESIGN.layer.activity.item;

	// bail when no activities
	if (empty(data)) return;

	// store shorthand
	point_activity = DESIGN.layer.activity.point_activity;

	// process tracks
	for (var a = 0; a < data.length; a++) {
		var coordinates = {},
			coordinate,
			distance;

		// retrieve coordinates
		coordinates.this = activity_coordinate(data[a]);

		// validate
		if (!empty(prev)) {
			// retrieve coordinates
			coordinates.prev = activity_coordinate(prev);

			// determine distance from previous track end point to start of current track
			distance = turf.distance(coordinates.prev[(coordinates.prev.length - 1)], coordinates.this[0]);
		}

		// determine whether point should be shown
		if (point_activity !== true || point_activity === true && a !== 0) {
			// determine whether to display point
			if (empty(distance) || distance > 100) {
				// retrieve first coordinate
				coordinate = coordinates.this[0];
				// build point
				points.push({ coordinate: coordinate, style: 'circle', });
			}
		}

		// determine whether point should be shown
		if (point_activity !== true || point_activity === true && a !== (data.length - 1)) {
			// retrieve last coordinate
			coordinate = coordinates.this[(coordinates.this.length - 1)];
			// build point
			points.push({ coordinate: coordinate, style: 'circle', });
		}

		// store previous
		prev = data[a];
	}

	// process points
	for (var a = 0; a < points.length; a++) {
		// render point
		activity_point_render(points[a]);
	}
}

/* --------------------------------------------------------- POINT : ACTIVITY --- */
function activity_point_activity() {
	var coordinates,
		coordinate,
		points = [],
		data,
		a,
		b;

	// store activities
	data = DESIGN.layer.activity.item;

	// bail when no activities
	if (empty(data)) return;

	// store first and last activity
	a = data[0];
	b = data[(data.length - 1)];

	// retrieve coordinates
	coordinates = activity_coordinate(a);
	// retrieve first coordinate
	coordinate = coordinates[0];
	// build point
	points.push({ coordinate: coordinate, style: 'triangle', });

	// retrieve coordinates
	coordinates = activity_coordinate(b);
	// retrieve last coordinate
	coordinate = coordinates[(coordinates.length - 1)];
	// build point
	points.push({ coordinate: coordinate, style: 'square', });

	// process points
	for (var a = 0; a < points.length; a++) {
		// render point
		activity_point_render(points[a]);
	}
}

/* ----------------------------------------------------------- POINT : RENDER --- */
function activity_point_render(data) {
	var $point;
	var instance;

	// cache element
	$section = jQuery('section.layer.activity');

	// build element
	$point = $section.find('.point.clone').clone().removeClass('clone');

	// define properties
	$point.addClass(['style', data.style,].join('_'));

	// initialize point instance
	instance = new mapboxgl.Marker({ element: $point[0], });

	// define coordinate
	instance.setLngLat(data.coordinate);
	// add to map
	instance.addTo(BASEMAP.activity);
}

/* ---------------------------------------------------------- POINT : DESTROY --- */
function activity_point_destroy() {
	var $basemap;

	// cache element
	$basemap = jQuery('section.layer.activity iframe.basemap');

	// destroy render
	$basemap.contents().find('.point').remove();
}

/* -------------------------------------------------------- METADATA : RENDER --- */
function activity_metadata_render() {
	var $input;
	var metadata,
		value;

	// cache element
	$input = jQuery('aside.option .group.text [name*=metadata]');

	// validate
	if (DESIGN.layer.activity.item.length > 0) {
		// calculate metadata
		metadata = activity_metadata_calculate();

		// build metadata value
		value = [
			[(metadata.distance / 1000).toFixed(1), 'km',].join(''),
			[Math.round(metadata.elevation).toLocaleString(), 'm',].join(''),
			format_second(metadata.time),
		].join(' â€” ');
	}

	// update value
	$input.val(value).trigger('input');
}

/* ----------------------------------------------------- METADATA : CALCULATE --- */
function activity_metadata_calculate() {
	var metadata = {},
		data;

	// store activities
	data = DESIGN.layer.activity.item;

	// distance
	metadata.distance = data.map(function(activity) { 
		return activity.data.distance; 
	}).reduce(function(a, b) { return (a + b); }, 0);

	// elevation
	metadata.elevation = data.map(function(activity) { 
		return activity.data.elevation.gain; 
	}).reduce(function(a, b) { return (a + b); }, 0);
	
	// time
	metadata.time = data.map(function(activity) {
		return activity.data.time.moving; 
	}).reduce(function(a, b) { return (a + b); }, 0);

	return metadata;
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function activity_design_action(event, key) {
	var $target;
	var value,
		child;

	// cache elements
	$target = jQuery(event.currentTarget);

	// determine child
	child = key.split('_').slice(1).join('_');
	// store value
	value = $target.attr('data-design-value');

	// child
	switch (child) {
		case 'line_width' :
			// format value
			value = parseInt(value);
		break;
		case 'point_finish' :
			// format value
			value = JSON.parse(value);
		break;
		case 'point_activity' :
			// format value
			value = JSON.parse(value);
		break;
	}

	// add activity class
	browser_class([key, value].join('-'));

	// store value
	DESIGN.layer.activity[child] = value;

	// child
	switch (child) {
		case 'line_width' :
			// enabled
			if (APP.basemap.enable === true) {
				// width line
				activity_line_width();
			}
		break;
		case 'point_finish' :
			// enabled
			if (APP.basemap.enable === true) {
				// process point
				activity_point_process();
			}
		break;
		case 'point_activity' :
			// enabled
			if (APP.basemap.enable === true) {
				// process point
				activity_point_process();
			}
		break;
	}
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////////////////////////////////////////////// LABEL /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function label_init() {
	// event
	label_event();
}

/* --------------------------------------------------------------------- LOAD --- */
function label_load() {
	// promise
	return new Promise(async function(resolve) {
		var $basemap;

		// cache element
		$basemap = jQuery('section.layer.label iframe.basemap');

		// load
		await basemap_load($basemap, 'label');

		// trigger label.load
		APP.trigger('label.load');

		// resolve
		resolve();
	});
}

/* -------------------------------------------------------------------- EVENT --- */
function label_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');
		
		// validate
		if (APP.basemap.enable === false) return;

		// click event
		label_event_click();

		// mouse event
		label_event_mouse();
	})();
}

/* ------------------------------------------------------------ EVENT : CLICK --- */
function label_event_click() {
	var $section,
		$scroll,
		$group;

	// cache elements
	$group = jQuery('aside.option .group.label');
	$section = $group.closest('section.step.display');
	$scroll = $section.closest('.scroll');

	// listen for click
	BASEMAP.label.on('click', function(event) {
		var source,
			target,
			offset,
			point,
			data;

		// store event data
		point = event.point;
		source = event.lngLat.toArray();

		// determine position target
		target = label_marker_position(data, point, source, 20);

		// label data
		data = { coordinate: target, };

		// insert
		label_insert(data);

		// calculate scroll offset
		offset = (($scroll.scrollTop() + $group.offset().top) - $section.find('header').outerHeight());
		// scroll to group
		$scroll.scrollTop(offset);

		// store
		design_store();
	});
}

/* ------------------------------------------------------------ EVENT : MOUSE --- */
function label_event_mouse() {
	var $basemap;
	var mouse = {},
		data;

	// cache element
	$basemap = jQuery('section.layer.label .basemap');

	// listen for mousedown
	$basemap.contents().on('mousedown', '.marker .label', function(event) {
		var $label;
		var label = {};

		// cache element
		$label = jQuery(this);

		// store label id
		label.id = $label.attr('data-label-id');
		// retrieve label item
		data = jQuery.grep(DESIGN.layer.label.item, function(tmp) { return tmp.id === label.id; })[0];

		// store event
		mouse.event = event;
	});

	// listen for mousemove
	BASEMAP.label.on('mousemove', function(event) {
		var source,
			target,
			point;

		// bail when mousedown inactive
		if (empty(mouse.event)) return;

		// store event data
		point = event.point;
		source = event.lngLat.toArray();

		// determine position
		target = label_marker_position(data, point, source, 10);
		// update position
		data.instance.setLngLat(target);
	});

	// listen for mouseup
	BASEMAP.label.on('mouseup', function() {
		// validate
		if (typeof data !== 'undefined') {
			// coordinate
			data.coordinate = data.instance.getLngLat();

			// update
			label_update(data);

			// store
			design_store();

			// reset data
			data = undefined;
		}

		// reset event
		mouse.event = undefined;
	});
}

/* ------------------------------------------------------------------- INSERT --- */
function label_insert(data) {
	// default values
	data.id = data.id || guid();
	data.text = data.text || 'Untitled label';
	data.anchor = data.anchor || 'left';

	// enabled
	if (APP.basemap.enable === true) {
		// insert marker
		data.instance = label_marker_insert(data);
	}

	// insert render
	label_render_insert(data);

	// store label
	DESIGN.layer.label.item.push(data);
}

/* ------------------------------------------------------------------- UPDATE --- */
function label_update(data) {
	var index,
		item;

	// retrieve label index
	index = DESIGN.layer.label.item.findIndex(function(item) { return item.id === data.id; });

	// store label item
	item = DESIGN.layer.label.item[index];
	
	// default values
	data.text = data.text || item.text;
	data.anchor = data.anchor || item.anchor;
	data.coordinate = data.coordinate || item.coordinate;
	data.instance = item.instance;

	// enabled
	if (APP.basemap.enable === true) {
		// update marker
		data.instance = label_marker_update(data);
	}

	// update render
	label_render_update(data);

	// store label
	DESIGN.layer.label.item[index] = data;
}

/* ------------------------------------------------------------------- DELETE --- */
function label_delete(id) {
	var data;

	// retrieve label item
	data = jQuery.grep(DESIGN.layer.label.item, function(item) { return item.id === id; })[0];

	// enabled
	if (APP.basemap.enable === true) {
		// delete marker
		label_marker_delete(data);
	}

	// delete
	label_render_delete(data);

	// remove label
	DESIGN.layer.label.item = jQuery.grep(DESIGN.layer.label.item, function(item) { return item.id !== id; });
}

/* ---------------------------------------------------------- MARKER : INSERT --- */
function label_marker_insert(data) {
	var $marker,
		$label;
	var instance;

	// cache element
	$section = jQuery('section.layer.label');

	// build element
	$marker = $section.find('.marker.clone').clone().removeClass('clone');

	// cache element
	$label = $marker.find('.label');

	// define properties
	$label.attr('data-label-id', data.id).addClass(['anchor', data.anchor,].join('_'));
	// set text
	$label.find('.text').text(data.text);

	// initalize label instance
	instance = new mapboxgl.Marker({
		element: $marker[0],
		draggable: true,
		anchor: data.anchor,
	});

	// override draggable move method
	instance._onMove = function() {
		// suppress click event
		instance._element.style.pointerEvents = 'none';
		// flag active state
		if (instance._state === 'pending') instance._state = 'active';
	};

	// define coordinate
	instance.setLngLat(data.coordinate);
	// add to map
	instance.addTo(BASEMAP.label);

	return instance;
}

/* ---------------------------------------------------------- MARKER : UPDATE --- */
function label_marker_update(data) {
	// delete marker
	label_marker_delete(data);

	// insert marker
	instance = label_marker_insert(data);

	return instance;
}

/* -------------------------------------------------------- MARKER : POSITION --- */
function label_marker_position(data, point, source, threshold) {
	var multiline,
		feature,
		target;

	// validate
	if (typeof data !== 'undefined') {
		// offset transform
		point = point.add(data.instance._positionDelta);
	}

	// query feature
	feature = basemap_feature_query('activity', 'activity-track', point, threshold);

	// feature
	if (typeof feature !== 'undefined') {
		// retrieve multiline
		multiline = activity_multiline();
		// determine nearest point on multiline
		target = turf.nearestPointOnLine(multiline, source, { units: 'radians', });
		// format target
		target = turf.getCoords(target);
	}
	// point
	else target = BASEMAP.label.unproject(point);

	return target;
}

/* ---------------------------------------------------------- MARKER : DELETE --- */
function label_marker_delete(data) {
	var instance;

	// store marker
	instance = data.instance;

	// destroy marker
	instance.remove();
}

/* ---------------------------------------------------------- RENDER : INSERT --- */
function label_render_insert(data) {
	var $group,
		$item;

	// cache element
	$group = jQuery('aside.option .group.label');

	// clone
	$item = $group.find('div.item.clone').clone().removeClass('clone');

	// id
	$item.attr('data-label-id', data.id);
	// input
	$item.find('input').attr('id', 'label[' + data.id + ']').attr('name', 'label[' + data.id + ']').val(data.text).focus();

	// append
	$group.append($item);

	// toggle active class
	$group.find('div.item').removeClass('active').filter($item).addClass('active');

	// toggle anchor class
	$item.removeClassPrefix('anchor');
	$item.addClass(['anchor', data.anchor,].join('_'));

	// toggle active class
	$item.find('.block a.item').removeClass('active').filter('[data-design-value=' + data.anchor + ']').addClass('active');
}

/* ---------------------------------------------------------- RENDER : UPDATE --- */
function label_render_update(data) {
	var $group,
		$item;

	// cache elements
	$group = jQuery('aside.option .group.label');
	$item = $group.find('div.item[data-label-id=' + data.id + ']');

	// toggle anchor class
	$item.removeClassPrefix('anchor');
	$item.addClass(['anchor', data.anchor,].join('_'));

	// toggle active class
	$item.find('.block a.item').removeClass('active').filter('[data-design-value=' + data.anchor + ']').addClass('active');
}

/* ---------------------------------------------------------- RENDER : DELETE --- */
function label_render_delete(data) {
	var $group,
		$item;

	// cache elements
	$group = jQuery('aside.option .group.label');
	$item = $group.find('div.item[data-label-id=' + data.id + ']');

	// destroy render
	$item.remove();
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function label_design_action(event, key) {
	var $target,
		$group,
		$item;
	var action,
		child,
		label = {},
		value,
		data;

	// cache elements
	$target = jQuery(event.currentTarget);
	$item = $target.closest('div.item');
	$group = $item.closest('.group');

	// determine event action
	action = event.originalEvent.type;

	// determine child
	child = key.split('_').slice(1).join('_');

	// store label id
	label.id = $item.attr('data-label-id');
	// retrieve label item
	data = jQuery.grep(DESIGN.layer.label.item, function(tmp) { return tmp.id === label.id; })[0];

	// child
	switch (child) {
		case 'text' :
			// focus
			if (action === 'focus') {
				// toggle active class
				$group.find('div.item').removeClass('active').filter($item).addClass('active');
			}
			// input
			else if (action === 'input') {
				// store value
				value = $target.val();

				// update label text
				data.text = value;

				// update
				label_update(data);
			}
		break;
		case 'anchor' :
			// click
			if (action === 'click') {
				// store value
				value = $target.attr('data-design-value');

				// anchor
				if (value !== 'delete') {
					// update label anchor
					data.anchor = value;
			
					// update
					label_update(data);
				}
				// delete
				else label_delete(label.id);
			}
		break;
	}
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////// OUTLINE /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function outline_init() {
	//
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function outline_design_action(event, key) {
	// async
	(async function() {
		var $target;
		var value,
			child;

		// cache elements
		$target = jQuery(event.currentTarget);

		// determine child
		child = key.split('_').slice(1).join('_');
		// store value
		value = $target.attr('data-design-value');

		// add outline class
		browser_class([key, value].join('-'));

		// style
		await poster_style(DESIGN.poster.style);

		// enabled
		if (APP.basemap.enable === true) {
			// bound
			setTimeout(activity_bound, 100);
		}

		// store value
		DESIGN.layer.outline[child] = value;
	})();
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* //////////////////////////////////////////////////////////////// ELEVATION /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function elevation_init() {
	// 
}

/* --------------------------------------------------------------------- LOAD --- */
function elevation_load() {
	// aspect ratio
	Chart.defaults.global.maintainAspectRatio = false;
	// animation
	Chart.defaults.global.animation.duration = 0;
	// layout
	Chart.defaults.global.layout.padding = 0;
	// legend
	Chart.defaults.global.legend.display = false;
	// line
	Chart.defaults.global.elements.line.tension = 0;
	Chart.defaults.global.elements.line.borderWidth = 0;
	// point
	Chart.defaults.global.elements.point.radius = 0;
	// scale
	Chart.defaults.scale.display = false;
}

/* ------------------------------------------------------------------ PROCESS --- */
function elevation_process() {
	var $section;
	var elevations = [],
		bounds = {},
		data;

	// cache element
	$section = jQuery('section.layer.elevation');

	// destroy
	elevation_destroy();

	// store activities
	data = DESIGN.layer.activity.item;

	// process activities
	for (var a = 0; a < data.length; a++) {
		var data,
			tmp;

		// retrieve elevation
		tmp = activity_elevation(data[a].file.data);
		// merge elevations
		elevations = elevations.concat(tmp);

		// temporarily store elevation
		data[a].elevation = tmp;
	}

	// format
	elevations = elevations.filter(Boolean).filter(function(elevation) { return elevation > 0; });

	// determine elevation bounds
	bounds.min = Math.min.apply(Math, elevations);
	bounds.max = Math.max.apply(Math, elevations);

	// process activities
	for (var a = 0; a < data.length; a++) {
		var $canvas;

		// build canvas
		$canvas = jQuery('<canvas>').attr('data-activity-id', data[a].id);

		// render
		$canvas = elevation_render($canvas, data[a].elevation, bounds);

		// append
		$section.append($canvas);
	}
}

/* ------------------------------------------------------------------- RENDER --- */
function elevation_render(canvas, points, bounds) {
	var $canvas;
	var color;

	// cache element
	$canvas = jQuery(canvas);

	// store color
	color = jQuery(':root').css('--style-elevation');
	
	// initalize chart
	new Chart($canvas[0].getContext('2d'), {
		type: 'line',
		data: {
			labels: points,
			datasets: [{
				data: points,
				borderColor: 'transparent',
				backgroundColor: color,
			},],
		},
		options: {
			scales: { yAxes: [{ ticks: { min: bounds.min, max: bounds.max, } ,},], },
		},
	});

	return $canvas;
}

/* ------------------------------------------------------------------ DESTROY --- */
function elevation_destroy() {
	var $section;

	// cache element
	$section = jQuery('section.layer.elevation');

	// destroy render
	$section.find('canvas').remove();
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function elevation_design_action(event, key) {
	var $target,
		$group;
	var value,
		child;

	// cache elements
	$target = jQuery(event.currentTarget);
	$group = jQuery('aside.option .group.elevation_multiply')

	// determine child
	child = key.split('_').slice(1).join('_');
	// store value
	value = $target.attr('data-design-value');

	// child
	switch (child) {
		case 'enable' :
			// format value
			value = JSON.parse(value);
		break;
	}

	// add elevation class
	browser_class([key, value].join('-'));

	// store value
	DESIGN.layer.elevation[child] = value;

	// child
	switch (child) {
		case 'enable' :
			// enabled
			if (value === true) {
				// add display class
				$group.addClass('display');

				// elevation
				elevation_process();
			}
			// disabled
			else {
				// remove display class
				$group.removeClass('display');

				// destroy
				elevation_destroy();
			}
		break;
		case 'multiply' :
			// elevation
			elevation_process();
		break;
	}
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ///////////////////////////////////////////////////////////////////// TEXT /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function text_init() {
	//
}

/* ---------------------------------------------------------- DESIGN : ACTION --- */
function text_design_action(event, key) {
	var $section,
		$element,
		$target;
	var value,
		child;

	// cache elements
	$section = jQuery('section.layer.text');
	$target = jQuery(event.currentTarget);

	// determine child
	child = $target.attr('name').match(/\[([^)]+)\]/)[1];
	// store value
	value = $target.val();

	// determine text element
	$element = $section.find(['.', child,].join(''));

	// store value
	DESIGN.layer.text[child] = value;

	// text
	$element.text(value);

	// child
	switch (child) {
		case 'headline' :
			// destory previous instance
			if ($element.data('arctext')) $element.arctext('destroy');

			// arctext
			$element.arctext({ radius: 8000, });
		break;
	}
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////// CONTROL /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function control_init() {
	// event
	control_event();
}

/* -------------------------------------------------------------------- EVENT --- */
function control_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// click event
		control_event_click();
	})();
}

/* ------------------------------------------------------------ EVENT : CLICK --- */
function control_event_click() {
	// listen for click
	jQuery('section.control .zoom a').on('click', function() {
		var action;

		// store action
		action = jQuery(this).attr('data-zoom-action');

		// zoom
		basemap_zoom(action);
	});
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////// REVIEW /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function review_init() {
	// event
	review_event();
}

/* -------------------------------------------------------------------- EVENT --- */
function review_event() {
	// async
	(async function() {
		// listen for ready
		await async_listener(APP, 'ready');

		// input event
		review_event_input();

		// mouse event
		review_event_mouse();
	})();
}

/* ------------------------------------------------------------ EVENT : INPUT --- */
function review_event_input() {
	// listen for input
	jQuery('aside.option [name=confirm]').on('input', function() {
		// toggle disabled class
		jQuery(this).closest('footer').find('a.checkout').toggleClass('disabled');
	});
}

/* ------------------------------------------------------------ EVENT : MOUSE --- */
function review_event_mouse() {
	var $section;
	var position = {};

	// cache element
	$section = jQuery('section.preview');

	// listen for mousedown
	$section.on('mousedown', function(event) {
		var scroll = {};

		// validate
		if (!jQuery('html').is('.stage_review')) return;

		// store current scroll
		scroll.top = $section.prop('scrollTop');
		scroll.left = $section.prop('scrollLeft');

		// store position
		position = {
			top: scroll.top,
			left: scroll.left,
			x: event.clientX,
			y: event.clientY,
		};

		// add active class
		$section.addClass('active');
	});

	// listen for mousemove
	$section.on('mousemove', function(event) {
		var distance = {},
			scroll = {};

		// validate
		if (empty(position)) return;

		// calculate distance travelled
		distance.x = (event.clientX - position.x);
		distance.y = (event.clientY - position.y);

		// determine scroll
		scroll.top = (position.top - distance.y);
		scroll.left = (position.left - distance.x);

		// scroll
		$section.scrollTop(scroll.top).scrollLeft(scroll.left);
	});

	// listen for mouseup
	$section.on('mouseup', function() {
		// reset position
		position = {};

		// remove active class
		$section.removeClass('active');
	});
}

/* ------------------------------------------------------------------- MODIFY --- */
function review_modify() {
	var $section;

	// cache element
	$section = jQuery('section.control');

	// add active class
	jQuery('html').addClass('scale_active');
	// remove active class
	$section.find('a.scale').removeClass('active');

	// toggle
	view_toggle('design');
}

/* ----------------------------------------------------------------- CHECKOUT --- */
function review_checkout() {
	var design;

	// bail when top
	if (APP.iframe !== true) return;

	// clone
	design = design_clone();	

	// post message
	message_post(window.parent, 'shopify_checkout', { design: design, });
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////////////////// API /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function api_init() {
	//
}

/* ------------------------------------------------------------------ REQUEST --- */
function api_request(method, endpoint, data) {
	// promise
	return new Promise(async function(resolve, reject) {
		var response,
            request,
			process;

		// post request
		if (jQuery.inArray(method, ['post',]) !== -1) {
			// request flag
			process = false;
		}
		// other request
		else {
			// request flag
			process = true;
		}

        // build request
		request = {
			method: method,
			url: [API.root, endpoint,].join(''),
			processData: process,
			contentType: false,
			dataType: 'json',
			headers: { 'Authorization': ['Basic', 'dXNlcjpwYXNz',].join(' '), },
			data: data,
		};

		// request
        try { response = await jQuery.ajax(request); } catch (error) {
			// fatal error
            if (error.status === 0 || empty(error.responseJSON)) {
				// render
                // alert_render({
                //     type: 'negative', 
                //     name: 'Fatal Error', 
                //     text: 'Something went wrong, please try again later>contact us</a>.'
                // });
			}

			// reject
            return reject(error);
		}

		// resolve
        return resolve(response);
	});
}


/* ////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////// STRAVA /// */
/* ////////////////////////////////////////////////////////////////////////////// */

/* --------------------------------------------------------------------- INIT --- */
function strava_init() {
	//
}

/* ----------------------------------------------------------------- VALIDATE --- */
function strava_validate() {
	var data;

	// token
	data = strava_token();

	// store token
	if (!empty(data)) STRAVA.token = data.token;	
}

/* -------------------------------------------------------------------- TOKEN --- */
function strava_token(token, expiry) {
	var data;

	// getter
	if (empty(token) && empty(expiry)) {
		// retrieve data
		data = localStorage.getItem('wielrennen.strava.token');

		// bail when not defined
		if (empty(data)) return;

		// parse data
		data = JSON.parse(data);

		// bail when token has expired
		if (moment(data.expiry * 1000).diff(moment().add(1, 'hour')) < 0) {
			// remove data
			localStorage.removeItem('wielrennen.strava.token');

			// alert
			// return alert_render({
			// 	type: 'info',
			// 	name: 'Strava Connection Expired',
			// 	text: 'To use the Strava search again please reconnect.',
			// });
		}
	}
	// setter
	else {
		// build data
		data = { token: token, expiry: expiry, };

		// store data
		localStorage.setItem('wielrennen.strava.token', JSON.stringify(data));
	}

	return data;
}

/* --------------------------------------------------------- AUTH : AUTHORIZE --- */
function strava_auth_authorize() {

	// async
	(async function() {
		var response;

		// request
		try { response = await api_request('post', '?action=authorize_strava_api'); } catch(error) {
			// alert
			// return alert_render({
			// 	type: 'negative',
			// 	name: 'Strava Authorization Failed',
			// 	text: error.responseJSON.error.description,
			// });
		}

		// open authorization window
		STRAVA.window = browser_window(response.authorization_url, 350, 585);
	})();
}

/* ---------------------------------------------------------- AUTH : CALLBACK --- */
function strava_auth_callback(event) {
	var response,
		callback;

	// close authorization window
	STRAVA.window.close();
	// reset window
	STRAVA.window = undefined;

	// validate origin
	if (!API.root.startsWith(event.origin)) return;

	// store event message
	response = event.data.payload;

	// bail when request failure
	if (response.status !== 200) {
		// alert
		// return alert_render({
		// 	type: 'negative',
		// 	name: 'Strava Authorization Failed',
		// 	text: response.error.text,
		// });
	}

	// token
	strava_token(response.token, response.expiry);
	
	// store callback
	callback = window[STRAVA.callback];
	// reset callback
	STRAVA.callback = undefined;

	// callback
	if (typeof callback === 'function') callback(event);
}

/* ----------------------------------------------------------- ACTIVITY : GET --- */
function strava_activity_get(id) {

	// promise
	return new Promise(async function(resolve) {
		var strava_tokens;
		var response;
		if (localStorage.getItem("wielrennen.strava.token") !== undefined ) {

			strava_tokens = localStorage.getItem("wielrennen.strava.token");

		}
		
		fd = new FormData();
		fd.append('id', id);
		fd.append('strava_tokens', strava_tokens);
	
		// request
        try { response = await api_request('post', '?action=wpe_strava_activity',fd); } catch(error) {
			// alert
			// alert_render({
			// 	type: 'negative',
			// 	name: 'Strava Request Failed',
			// 	text: error.responseJSON.error.description,
			// });
			
			// resolve
			return resolve();
		}

		// resolve
		resolve(response.data.activity);
	});
}

/* -------------------------------------------------------- ACTIVITY : SELECT --- */
function strava_activity_select() {
	// promise
	return new Promise(async function(resolve) {
		var response;
		let strava_tokens = '';

		if (localStorage.getItem("wielrennen.strava.token") !== undefined ) {
			strava_tokens = localStorage.getItem("wielrennen.strava.token");
		}

		fd = new FormData();
		fd.append('strava_tokens', strava_tokens);

		// request
        try { response = await api_request('post', '?action=wpe_strava_activities', fd); } catch (error) {
			// alert
			// alert_render({
			// 	type: 'negative',
			// 	name: 'Strava Request Failed',
			// 	text: error.responseJSON.error.description,
			// });
			
			// resolve
			return resolve([]);
		}

		// resolve
		resolve(response.data.activity);
	});
}


function parse_token(strava_tokens) {

    return JSON.parse(strava_tokens);

}