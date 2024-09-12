jQuery(document).ready(function($) {
	$(document).on('click','.wie_download_pdf',function() {
		$('body').append(`<div id="maploading">
		<img id="maploading-image" style="overflow:hidden" src="../wp-content/plugins/wielrennen-map-editor/assets/images/loader.gif" alt="Loading..." />
		</div>`);

		
		var guid = $(this).data('guid');
	
		var variate_pro = $(this).data('variation');
		$.ajax({

			url: ajax_object.ajax_url,

			type: 'POST',

			dataType: 'json',

			data: {

				'action':'get_gpx_file',
				'guid' : guid,
				'variate_pro' : variate_pro

			},

		success:function(response) {

		var design_map = response.design.config;
		var elev_data = response.design.config.layer.activity.item;
		var features = response.design.config.layer.activity.item[0].file.geojson.features;
		
		
		var size = 'a3';
		var new_size = response.design.config.paper.size;
		var orientation = design_map.paper.orientation;
		var style = 'cktgakgzr0x9d18wbux5erbgd';
		var font_color_style = '#fff';
		var color_style = '#e76b2e';
	

		var font_family = design_map.font.family;
		var font_size = design_map.font.size;
		var multiply_elevation = design_map.layer.elevation.multiply;
		var font_class_family = 'font_family-'+font_family;
		var font_class_size = 'font_size-'+font_size;
		var elevation_class_multiply = 'elevation_multiply-'+multiply_elevation;
		var paper_size_class = 'paper_size-'+new_size;
		var paper_orientation_class = 'paper_orientation-'+design_map.paper.orientation;

		var outline_add = 'outline_type-'+design_map.layer.outline.type;

		 // 'paper_orientation-'+design_map.paper.orientation;


		var overlay_class = design_map.layer.overlay.type;
		var overlay_class_apply  = design_map.paper.orientation+'-overlay-'+design_map.poster.style;
		var font_color_label  = '';
		var elevation_color = '';


		if(design_map.poster.style == 'basemap') {
			style = 'cktgakgzr0x9d18wbux5erbgd';
			color_style = '#e76b2e';
			font_color_style = '#000';
			if(design_map.layer.outline.type != 'classic' && design_map.layer.outline.type !='none') {
				font_color_style = '#1e1e1e';
			}
			font_color_label = '#1e1e1e';
			elevation_color = 'rgba(0, 0, 0, 0.1)';

		}
		if(design_map.poster.style == 'black_white') {
			style = 'cktgasv8w38st18pmkltf2hmw';
			color_style = '#000';
			font_color_style = '#000';
			font_color_label = '#000';
			elevation_color = '#000';

		}
		if(design_map.poster.style == 'blue') {
			style = 'cktgarp7d0ute17pfvhaemweb';
			color_style = '#fbfafa';
			font_color_style = '#fff';
			elevation_color = 'rgba(255, 255, 255, 0.75)';
			
			if(design_map.layer.outline.type != 'classic' && design_map.layer.outline.type !='none') {
				font_color_style = '#1e1e1e';
				elevation_color = 'rgba(42, 61, 145, 0.15)';
			}
			font_color_label = '#fbfafa';
		
		}
		if(design_map.poster.style == 'grey_dark') {
			style = 'cktgaqqr042qi18qrn6kaoqlr';
			color_style = '#e76b2e';
			font_color_style = '#fff';
			elevation_color = 'rgba(255, 255, 255, 0.75)';
			if(design_map.layer.outline.type != 'classic' && design_map.layer.outline.type !='none') {
				font_color_style = '#1e1e1e';
				elevation_color = 'rgba(0, 0, 0, 0.1)';
				
			}
			font_color_label = '#fff';
			
		}
		if(design_map.poster.style == 'grey_light') {
			style = 'cktgail7a19ag17r4iexbs9sb';
			color_style = '#e76b2e';
			font_color_style = '#1e1e1e';
			if(design_map.layer.outline.type != 'classic' && design_map.layer.outline.type !='none') {
				font_color_style = '#1e1e1e';
			}
			font_color_label = '#1e1e1e';
			elevation_color = 'rgba(0, 0, 0, 0.1)';
		}
		if(design_map.poster.style == 'outdoor') {
			style = 'cktgan9480unb18p97mx40qab';
			color_style = '#605650';
			font_color_style = '#605650';
			font_color_label = '#605650';
			elevation_color = 'rgba(96, 86, 80, 0.25)';
		}
		if(design_map.poster.style == 'pastel') {
			style = 'cktgaosrp0uoq18p9u45wh9fc';
			color_style = '#9f8b7d';
			font_color_style = '#786a5a';
			font_color_label = '#786a5a';
			elevation_color = 'rgba(120, 106, 90, 0.3)';
		}
	
		if(design_map.poster.style == 'spring') {
			style = 'cktgaprsq409w17n7xglu8y34';
			color_style = '#56b8b7';
			font_color_style = '#416464';
			font_color_label = '#416464';
			elevation_color = '#416464';
		}
		if(design_map.poster.style == 'orange') {
			style = 'cktgalz9a1zhi17mo9vpk3ks2';
			color_style = '#fff';
			font_color_style = '#fff';
			elevation_color = 'rgba(255, 255, 255, 0.75)';
			if(design_map.layer.outline.type != 'classic' && design_map.layer.outline.type !='none') {
			
				font_color_style = '#1e1e1e';
				elevation_color = 'rgba(229, 107, 46, 0.15)';
			}
			font_color_label = '#fff';
			
		}
		var orient = 'p';
	    var new_size_value = new_size.split("x"); //70x100
	    var cmtomm_width = 10 * new_size_value[0];
	    var cmtomm_height = 10 * new_size_value[1];

		// CM TO MM AND THEN PX
	    var width = cmtomm_width*3.77953;
	    var height = cmtomm_height*3.77953;

	    // Calculate pixel ratio
	    var actualPixelRatio = window.devicePixelRatio;
	    var dpi = 300;
	    var width1 = height;
	    if(orientation == "portrait") {
	    	cmtomm_width = cmtomm_width;
	    	cmtomm_height = cmtomm_height;
	    	width = width;
			height = height;
			orient = 'p';
		}
	    if(orientation == "landscape") {
	    	cmtomm_width = cmtomm_height;
	    	cmtomm_height = cmtomm_width;
	    	width1 = width;
	    	width = height;
			height = width1;
			orient = 'l';
	    }
	
	    var format = 'pdf';
	    var unit = 'mm';

	    Object.defineProperty(window, 'devicePixelRatio', {
	        get: function() {return dpi / 96}
	    });
	    // Create map container
	    var hidden = document.createElement('div');
	    var source;
	    hidden.className = 'hidden-map';
	    hidden.id = 'hidden-map';

	    var ele = document.createElement('section');

	    var source;
	    let eleToAdd = [ 'layer', 'elevation', elevation_class_multiply, paper_size_class];
	    ele.classList.add(...eleToAdd);
	    ele.id = 'elevation-layer';


	     var map_create = document.createElement('section');

	
	    let mapToAdd = [ 'layer', 'map', design_map.paper.orientation, paper_size_class, font_class_size];
	    map_create.classList.add(...mapToAdd);
	    map_create.id = 'map-layer';
		
		var map_outline = document.createElement('section');
		var map_frame_border = document.createElement('section');
		if(design_map.layer.outline.type !='none') {
			map_outline.style.backgroundImage =  "url('../wp-content/plugins/wielrennen-map-editor/assets/images/outline/"+design_map.layer.outline.type+"/white_"+new_size+"_"+design_map.paper.orientation+".svg')";
		}
		console.log(design_map.paper.material);
		console.log(design_map.paper.size);
		console.log(design_map.layer.framed_poster.poster);
		if(design_map.paper.material =='framed_poster') {
			var border_size = 20;
			if (design_map.paper.size =='30x40') {
				border_size = 30;
			} else if (design_map.paper.size =='50x70') { 
				border_size = 30;
			}
			if(design_map.layer.framed_poster.poster == 'wood') {
				map_frame_border.style.backgroundImage = "url('../wp-content/plugins/wielrennen-map-editor/assets/images/wood-frames/wood_"+new_size+"_"+design_map.paper.orientation+".svg')";
			}
			if(design_map.layer.framed_poster.poster == 'black') {
				map_frame_border.style.border = border_size+"px solid #000";
			}
			if(design_map.layer.framed_poster.poster == 'white') {
				map_frame_border.style.border = border_size+"px solid #fff";
			}
			
		} else {
			map_frame_border.style.border = '';
		}
		console.log(map_frame_border);

	
	    let outlineToAdd = [ 'layer', 'outline'];
	    map_outline.classList.add(...outlineToAdd);
	    map_outline.id = 'outline-layer';


		let framePosterToAdd = [ 'layer', 'frame_poster_border'];
	    map_frame_border.classList.add(...framePosterToAdd);
	    map_frame_border.id = 'frame_poster_border-layer';



		var map_overlay = document.createElement('section');
		
		let overlayToAdd = [];
		
		if(overlay_class == 'nonelay' || overlay_class == 'none') {
			overlayToAdd = [ 'layer', 'overlay', overlay_class];
		} else {
			overlayToAdd = [ 'layer', 'overlay', overlay_class, overlay_class_apply];
		}

		
		map_overlay.classList.add(...overlayToAdd);
		map_overlay.id = 'overlay-layer';


		var canvas_image = document.createElement('div');
		let classesToAdd = [ 'canvas_image', design_map.paper.orientation];
		canvas_image.classList.add(...classesToAdd);
		canvas_image.style.width =  width+'px';
		canvas_image.style.height =  height+'px';



		var activity_canvas = document.createElement('div');
		let activity_classes_to_add = [ 'point', 'clone'];
		activity_canvas.classList.add(...activity_classes_to_add);



		

		var canvas_content = document.createElement('div');
		canvas_content.className = 'canvas_content';
		var canvas_title = document.createElement('div');
		let canvas_title_to_add = [ 'canvas_title', font_class_family, font_class_size, paper_orientation_class ,paper_size_class, outline_add];
		canvas_title.classList.add(...canvas_title_to_add);
		
		
		canvas_title.style.cssText =  'color:'+font_color_style;
		

		var canvash1 = document.createElement('div');
		canvash1.className = 'headline';
		var subtitle = document.createElement('div');
		subtitle.className = 'subtitlee';

		var newContent = document.createTextNode(design_map.layer.text.headline);
		var subtitle_text = document.createTextNode(design_map.layer.text.subtitle);


		var bottom_canvas_title = document.createElement('div');
		let canvas_bottom_to_add = [ 'bottom-content', font_class_family, font_class_size, paper_orientation_class ,paper_size_class, outline_add];
		bottom_canvas_title.classList.add(...canvas_bottom_to_add);

		bottom_canvas_title.style.cssText =  'color:'+font_color_style;
		var bottom_div = document.createElement('div');
		bottom_div.className = 'footnote'
		var bottom_text = document.createTextNode(design_map.layer.text.footnote);
		var meta_data = document.createElement('div');
		meta_data.className = 'metadata'
		var meta_data_text = document.createTextNode(design_map.layer.text.metadata);



	    document.body.appendChild(hidden);
	    var container = document.createElement('div');
	    container.style.width = width+'px' //toPixels(width);
	    container.style.height = height+'px' //toPixels(height);
	    map_create.appendChild(container)
	    canvas_image.appendChild(map_create);
	    canvas_image.appendChild(map_overlay);





		var label_canvas = document.createElement('div');
		let label_classes_to_add = [ 'layer', 'label'];
		label_canvas.classList.add(...label_classes_to_add);
		var label_inner_canvas = document.createElement('div');
		let label_inner_classes_to_add = [ 'marker', 'clone'];
		label_inner_canvas.classList.add(...label_inner_classes_to_add);
		var label_under_canvas = document.createElement('div');
		label_under_canvas.className = 'label';
		var label_anchor_canvas = document.createElement('div');
		label_anchor_canvas.className = 'anchor';
		var label_text_canvas = document.createElement('div');
		label_text_canvas.className = 'text';
		label_text_canvas.style.cssText =  'color:'+font_color_label;
		label_anchor_canvas.style.cssText =  'color:'+font_color_label;



		label_under_canvas.appendChild(label_anchor_canvas);
		label_under_canvas.appendChild(label_text_canvas);
		label_inner_canvas.appendChild(label_under_canvas);
		label_canvas.appendChild(label_inner_canvas);
		canvas_image.appendChild(label_canvas);
	   

	   
	  
	    canvas_image.appendChild(map_outline);
		canvas_image.appendChild(map_frame_border);
	    canvas_image.appendChild(ele);
	    canvas_image.appendChild(activity_canvas);
		canvas_image.appendChild(canvas_content);
		canvas_image.appendChild(bottom_canvas_title);
		bottom_canvas_title.appendChild(bottom_div);
		bottom_div.appendChild(bottom_text);
		bottom_canvas_title.appendChild(meta_data);
		meta_data.appendChild(meta_data_text);
		canvas_content.appendChild(canvas_title);
		canvas_title.appendChild(canvash1);
		canvas_title.appendChild(subtitle);
		canvash1.appendChild(newContent);
	    subtitle.appendChild(subtitle_text);
	    hidden.appendChild(canvas_image);
	    
	    if (design_map.layer.elevation.enable === true) {
			elevation_load();
			elevation_process(elev_data,elevation_color);
		}
	    mapboxgl.accessToken = 'pk.eyJ1IjoiemFpYm1hc2hhZCIsImEiOiJja3QxaTNmMWYwNG1uMzByMXBkeGxyYzNlIn0.dfrxGDr92Sla189KhGPkfQ';
	    
	    var renderMap = new mapboxgl.Map({
	    	container: container,
	    	center: design_map.layer.map.center,
	    	zoom: design_map.layer.map.zoom,
	    	style: 'mapbox://styles/zaibmashad/'+style,
	    	interactive: false,
	    	preserveDrawingBuffer: true,
	    	fadeDuration: 0,
	    	attributionControl: false
	    });
	    

		if (design_map.layer.activity.point_activity === false && design_map.layer.activity.point_finish === true) {
		// finish point
			activity_point_finish(elev_data, renderMap, color_style,'circle');
			
		}
		if (design_map.layer.activity.point_activity === true && design_map.layer.activity.point_finish === true) {
			activity_point_finish(elev_data, renderMap, color_style,'');
			activity_point_activity(elev_data, renderMap, color_style);
		}
		if (design_map.layer.activity.point_activity === true && design_map.layer.activity.point_finish === false) {
			activity_point_finish(elev_data, renderMap, color_style,'');
			activity_point_activity(elev_data, renderMap, color_style);
		}
	
		
		if(!empty(design_map.layer.label)) {
			design_load_label(design_map.layer.label, renderMap);
		}
 
		renderMap.on('load', async () => {
			var design_map = response.design.config;
			var data = response.design.config.layer.activity.item[0].file.geojson;
			renderMap.addSource('route', { 
				type: 'geojson', 
				data: data,
				"attribution": ""
				 }

				);
			renderMap.addLayer({
				'id': 'route',
				'type': 'line',
				'source': 'route',
				'paint': {
					'line-color': color_style,
					'line-width': design_map.layer.activity.line_width
				}
			});

		});

		renderMap.once('idle', function() {
			html2canvas(document.querySelector('.canvas_image'),{scale:3.13}).then(function(canvas) { 
					var imgData = canvas.toDataURL(
						'image/png',1.0);
					// var toDataURL = imgData;//changeDpiDataUrl(imgData,300);      
					var toDataURL = changeDpiDataUrl(imgData,300); 
					var link = document.createElement('a');
					link.href = toDataURL;  // use realtive url 
					link.download = 'map.png';
					document.body.appendChild(link);
					link.click(); 
						// var image = new Image();
						// image.src = toDataURL;
						// document.body.appendChild(image);

					// var pdf = new jsPDF({
					// 	orientation: orient,
					// 	unit: unit,
					// 	format: [cmtomm_width, cmtomm_height],
					// 	compress: true
					// });
					// var w = pdf.internal.pageSize.getWidth();
					// var h = pdf.internal.pageSize.getHeight();

				
					// pdf.addImage(toDataURL,'jpeg', 0, 0, w, h, null, 'FAST');
					// pdf.save('map.pdf');
					
			});
			renderMap.remove();
			hidden.parentNode.removeChild(hidden);
			$('#maploading').remove();
			Object.defineProperty(window, 'devicePixelRatio', {
				get: function() {return actualPixelRatio}
			});
		});
			},

			error: function(errorThrown){



			}

		});
	});
	
});
function toPixels(length) {
    'use strict';
    var unit = 'mm';
    var conversionFactor = 96;
    if (unit == 'mm') {
        conversionFactor /= 25.4;
    }

    return conversionFactor * length + 'px';
}
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
function elevation_process(elev_data,color) {
	var $section;
	var elevations = [],
	bounds = {},
	data;

	// cache element
	$section = jQuery('#elevation-layer');

	
	
	// store activities
	data = elev_data;


	

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
		$canvas = elevation_render($canvas, data[a].elevation, bounds,color);
		

		// append
		$section.append($canvas);
	}
}

/* ------------------------------------------------------------------- RENDER --- */
function elevation_render(canvas, points, bounds,color) {
	var $canvas;
	var color;

	// cache element
	$canvas = jQuery(canvas);



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

function activity_point_activity(data, renderMap,style_color) {
	var coordinates,
	coordinate,
	points = [],
	data,
	a,
	b;

	// store activities


	

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
		activity_point_render(points[a], renderMap, style_color);
	}
}
function activity_point_finish(data, renderMap,style_color,circle) {
	var point_activity,
	points = [],
	data,
	prev;



	

	// bail when no activities
	if (empty(data)) return;

	// store shorthand
	point_activity = true;


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
		if (point_activity !== true || point_activity === true && !empty(circle)) {
				
			// determine whether to display point
			if (empty(distance) || distance > 100) {
			
				// retrieve first coordinate
				coordinate = coordinates.this[0];
				// build point
				points.push({ coordinate: coordinate, style: 'circle', });
			}
		}

		// determine whether point should be shown
		if (point_activity !== true || point_activity === true && !empty(circle)) {
					
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
		activity_point_render(points[a], renderMap, style_color);
	}
}
function activity_point_render(data, renderMap, style_color) {
	var $point;
	var instance;

	// cache element
	$section = jQuery('div');

	// build element
	$point = $section.find('.point.clone').clone().removeClass('clone');



	// define properties
	$point.addClass(['style', data.style,].join('_'));
	if(data.style == 'triangle') {
		$point.css({"border-color": 'transparent transparent transparent '+style_color, "width": "12"});
	} else {
		$point.css({"background-color": style_color, "width": "12"});
	}
	
	


	// initialize point instance
	instance = new mapboxgl.Marker({ element: $point[0], });

	// define coordinate
	instance.setLngLat(data.coordinate);
	// add to map
	instance.addTo(renderMap);
}


function empty(value) {
	var empty = [undefined, null, false, 0, '', '0',];
	for (var a = 0; a < empty.length; a++) {
		if (value === empty[a]) {
			return true;
		}
	}
	if (typeof value === 'object') {
		for (key in value) {
			if (value.hasOwnProperty(key)) {
				return false;
			}
		}
		return true;
	}
	return false;
}

function design_load_label(label,renderMap) {
	
	for (var a = 0; a < label.item.length; a++) {
		var data;

		// store data
		data = label.item[a];
		// insert
		label_insert(data,renderMap);
	}
}


function label_insert(data,renderMap) {
	// default values
	data.id = data.id;
	data.text = data.text;
	data.anchor = data.anchor;

	// enabled
	// if (APP.basemap.enable === true) {
		// insert marker
	data.instance = label_marker_insert(data,renderMap);

	// }

	// insert render
	// label_render_insert(data);

	// // store label
	// DESIGN.layer.label.item.push(data);
}

function label_marker_insert(data, renderMap) {
	var $marker,
	$label;
	var instance;
	// cache element
	$section = jQuery('.layer.label');
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
	instance.addTo(renderMap);

	return instance;
}