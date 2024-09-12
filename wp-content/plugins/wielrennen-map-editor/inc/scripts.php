<?php

//Enqueue some script when some post uses some shortcode. 

function map_front_scripts() {
	
	wp_enqueue_script('custom_page_script', ASSETS_URL.'js/custom_page_script.js' ,array('jquery'));

	if(is_page('5454') || is_page('5451')) {
	wp_register_style( 'styles_css', ASSETS_URL.'css/style.css');

   wp_enqueue_style('styles_css');
   
   wp_enqueue_style('basemap.css', ASSETS_URL.'css/bmap-style.css');
   
   wp_enqueue_style('render.css', ASSETS_URL.'css/render.css');
   // wp_register_style( 'apps_css', ASSETS_URL.'css/app.css');

   // wp_enqueue_style('apps_css');
   if(is_page('5454') || is_page('5451')) {

	wp_register_style( 'front_end_css', ASSETS_URL.'css/front-index.css');

	wp_enqueue_style('front_end_css');
  }
//    if(is_page('5451')) {

// 	wp_register_style( 'front-2index.css', ASSETS_URL.'css/front-2index.css');

// 	wp_enqueue_style('front-2index.css');
//   }

  
  

	wp_register_style( 'mapbox_css', ASSETS_URL.'css/mapbox.css');
  

	wp_enqueue_style('mapbox_css');

	

	

	


	

	wp_enqueue_script('arctext_js', ASSETS_URL.'js/arctext.js' ,array('jquery'));
	wp_enqueue_script('chart_js', ASSETS_URL.'js/chart.js' ,array('jquery'));
	wp_enqueue_script('mapboxgl_js', ASSETS_URL.'js/mapbox.js',array('jquery'));
	wp_enqueue_script('moment_js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',array('jquery'));
	wp_enqueue_script('sortable-js', ASSETS_URL.'js/sortable.js',array('jquery'));
	wp_enqueue_script('turf-js', ASSETS_URL.'js/turf.js',array('jquery'));

	wp_enqueue_script('methods-js', ASSETS_URL.'js/methods.js',array('jquery'));
   
	wp_enqueue_script('var-js', ASSETS_URL.'js/var.js',array('jquery'));
	wp_enqueue_script('message-js', ASSETS_URL.'js/message.js',array('jquery'));
	wp_enqueue_script('basemap-js', ASSETS_URL.'js/basemap.js',array('jquery'));
	wp_enqueue_script('jspdf-js', ASSETS_URL.'js/jspdf.js',array('jquery'));
	 wp_enqueue_script('html2canvas-js', ASSETS_URL.'js/html2canvas.js',array('jquery'));
   
	if(is_page('5451')) {
		wp_enqueue_script('front-js', ASSETS_URL.'js/front-index_2.js',array('jquery'));
	} elseif(is_page('5454')) {
		wp_enqueue_script('front-js', ASSETS_URL.'js/front-index.js',array('jquery'));
	}

	
	wp_localize_script( 'var-js', 'ajax_object', array(

	  'ajax_url' => admin_url( 'admin-ajax.php' ),

   ) );
   

   
	 wp_localize_script( 'front-js', 'ajax_object', array(

		'ajax_url' => admin_url( 'admin-ajax.php' ),

	) );
 }
 if(is_page('5601')) {
   wp_enqueue_script('methods-js', ASSETS_URL.'js/methods.js',array('jquery'));
	wp_enqueue_script('var-js', ASSETS_URL.'js/var.js',array('jquery'));
   wp_localize_script( 'var-js', 'ajax_object', array(

	  'ajax_url' => admin_url( 'admin-ajax.php' ),

   ) );
 }



}

add_action( 'wp_enqueue_scripts', 'map_front_scripts', 20 );



add_action('admin_enqueue_scripts', 'wie_admin_scripts');
function wie_admin_scripts($hook) {
	global $current_screen;


	if ('post.php' !== $hook) {
	    return;
	}
	if($current_screen->post_type == 'page') {
		return;
	}


	wp_enqueue_style('basemap.css', ASSETS_URL.'css/bmap-style.css');
	wp_enqueue_style('basemap-style.css', ASSETS_URL.'css/admin-basemap.css');
	wp_register_style( 'mapbox_css', ASSETS_URL.'css/mapbox.css');
  

	wp_enqueue_style('mapbox_css');
	wp_register_style( 'admin-front-index', ASSETS_URL.'css/admin-front-index.css');
  

	wp_enqueue_style('admin-front-index');
	wp_enqueue_script('arctext_js', ASSETS_URL.'js/arctext.js' ,array('jquery'));
	wp_enqueue_script('chart_js', ASSETS_URL.'js/chart.js' ,array('jquery'));
	wp_enqueue_script('mapboxgl_js', ASSETS_URL.'js/mapbox.js',array('jquery'));
	wp_enqueue_script('moment_js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',array('jquery'));
	wp_enqueue_script('sortable-js', ASSETS_URL.'js/sortable.js',array('jquery'));
	wp_enqueue_script('basemap-js', ASSETS_URL.'js/basemap.js',array('jquery'));
	wp_enqueue_script('turf-js', ASSETS_URL.'js/turf.js',array('jquery'));
	wp_enqueue_script('changeDPI-js', ASSETS_URL.'js/changeDPI.js',array('jquery'));
	wp_enqueue_script('jspdf-js', ASSETS_URL.'js/jspdf.js',array('jquery'));



	
	wp_enqueue_script('html2canvas-js', ASSETS_URL.'js/html2canvas.js',array('jquery'));
	wp_enqueue_script('FileSaver-js', 'https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js',array('jquery'));





	wp_enqueue_script('admin-front-js', ASSETS_URL.'js/admin-front-index.js',array('jquery'));
	wp_localize_script( 'admin-front-js', 'ajax_object', array(

		'ajax_url' => admin_url( 'admin-ajax.php' ),

	) );
}