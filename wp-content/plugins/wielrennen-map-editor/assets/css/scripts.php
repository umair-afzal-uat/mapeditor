<?php

//Enqueue some script when some post uses some shortcode. 

function map_front_scripts() {

    wp_register_style( 'front_end_css', ASSETS_URL.'css/map-style.css');

    wp_enqueue_style('front_end_css');



    wp_register_style( 'mapbox_css', ASSETS_URL.'css/mapbox.css');

    wp_enqueue_style('mapbox_css');

    wp_register_style( 'render_css', ASSETS_URL.'css/renders.css');
    wp_enqueue_style('render_css');

    

    

    wp_enqueue_script('moment_js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',array('jquery'));
    wp_enqueue_script('sortable-js', ASSETS_URL.'js/sortable.js',array('jquery'));

    

    wp_enqueue_script('arctext_js', ASSETS_URL.'js/arctext.js' ,array('jquery'));
    wp_enqueue_script('chart_js', ASSETS_URL.'js/chart.js' ,array('jquery'));
    wp_enqueue_script('mapboxgl_js', ASSETS_URL.'js/mapbox.js',array('jquery'));

   wp_enqueue_script('methods-js', ASSETS_URL.'js/methods.js',array('jquery'));

    wp_enqueue_script('var-js', ASSETS_URL.'js/var.js',array('jquery'));
    wp_enqueue_script('message-js', ASSETS_URL.'js/message.js',array('jquery'));
    wp_enqueue_script('basemap-js', ASSETS_URL.'js/basemap.js',array('jquery'));
    wp_enqueue_script('app-js', ASSETS_URL.'js/app.js',array('jquery'));

   // wp_enqueue_script('front-js', ASSETS_URL.'js/front-index.js',array('jquery'));
    wp_localize_script( 'var-js', 'ajax_object', array(

      'ajax_url' => admin_url( 'admin-ajax.php' ),

   ) );

    // wp_localize_script( 'front-js', 'ajax_object', array(

    //     'ajax_url' => admin_url( 'admin-ajax.php' ),

    // ) );
     wp_localize_script( 'app-js', 'ajax_object', array(

        'ajax_url' => admin_url( 'admin-ajax.php' ),

    ) );

}

add_action( 'wp_enqueue_scripts', 'map_front_scripts', 20 );