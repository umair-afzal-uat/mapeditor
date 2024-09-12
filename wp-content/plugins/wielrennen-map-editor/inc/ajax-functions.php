<?php

use phpGPX\phpGPX;



function authorize_strava_api() {

		echo json_encode(['status' => 200, 'authorization_url' => 'https://www.strava.com/oauth/authorize?client_id=70094&response_type=code&redirect_uri=https://mapeditor.arhamsoft.info/wp-admin/admin-ajax.php?action=exchange_token&approval_prompt=force&scope=read%2Cactivity%3Aread_all']);

		wp_die();

}

add_action( 'wp_ajax_authorize_strava_api', 'authorize_strava_api' );

add_action("wp_ajax_nopriv_authorize_strava_api", "authorize_strava_api");



function exchange_token() {

		$url = 'https://www.strava.com/oauth/token';

		$code = $_GET['code'];

		$response = wp_remote_post( $url, array(

			'method' => 'POST',

			'timeout' => 45,

			'redirection' => 5,

			'httpversion' => '1.0',

			'blocking' => true,

			'headers' => array(),

			'body' => array( 'client_id' => '70094', 'client_secret' => '42d87b5f8aa18315bc22f9b5fdd98fe18545638c' , 'code' => $code , 'grant_type' => 'authorization_code'),

			'cookies' => array()

	)); 

	$res =  wp_remote_retrieve_body($response);

	$decoded_value = json_decode($res);



	$access_token = $decoded_value->access_token;

	$refresh_token = $decoded_value->refresh_token;

	$exp_date = $decoded_value->expires_at;

	$combine_token = [

			'token' => $access_token, 

			'r_token' => $refresh_token, 

			'expire_time' => $exp_date

	];



	?>

	<script>

			var code = <?php echo json_encode($combine_token); ?>;

			localStorage.setItem("wielrennen.strava.token", JSON.stringify(code)); 

			window.close();

	</script>

	<?php

}

add_action( 'wp_ajax_exchange_token', 'exchange_token' );

add_action("wp_ajax_nopriv_exchange_token", "exchange_token");



function wpe_strava_activities() {

	$tokens = stripslashes($_POST['strava_tokens']);

	$strava_tokens = json_decode($tokens);

	$access_token = $strava_tokens->token;

	$args = [

		'headers' => [

				'Authorization' => 'Bearer '.$access_token,

				'Content-Type' => 'application/json' 

		]

	];

	$response = wp_remote_get( 'https://www.strava.com/api/v3/athlete/activities', $args );

	$body_response = json_decode(wp_remote_retrieve_body($response));

	$activities = [];

	foreach($body_response as $i => $res) {

			$activities['activity'][$i]['id'] = @$res->id;

			$activities['activity'][$i]['name'] = @$res->name;

			$activities['activity'][$i]['time'] = @$res->start_date_local;

			$activities['activity'][$i]['data']['distance'] = @$res->distance;

			$activities['activity'][$i]['data']['time']['moving'] = @$res->moving_time;

			$activities['activity'][$i]['data']['time']['total'] = @$res->elapsed_time;

			$activities['activity'][$i]['data']['elevation']['min'] = @$res->elev_low;

			$activities['activity'][$i]['data']['elevation']['max'] = @$res->elev_high;

			$activities['activity'][$i]['data']['elevation']['gain'] = @$res->total_elevation_gain;

			$activities['activity'][$i]['data']['speed'] = @$res->average_speed;

			$activities['activity'][$i]['data']['pace'] = @$res->average_speed;

	}

	echo json_encode(['status' => 200, 'data' => $activities]);

	wp_die();

			

}

add_action( 'wp_ajax_wpe_strava_activities', 'wpe_strava_activities' );

add_action("wp_ajax_nopriv_wpe_strava_activities", "wpe_strava_activities");



function wpe_strava_activity() {

	$tokens = stripslashes($_POST['strava_tokens']);

	$strava_tokens = json_decode($tokens);

	$access_token = $strava_tokens->token;

	$id = $_POST['id'];

			$args = [

							'headers' => [

							'Authorization' => 'Bearer '.$access_token,

							'Content-Type' => 'application/json' 

					]

			];

	

	$response = wp_remote_get( 'https://www.strava.com/api/v3/activities/'.$id, $args );

	$body_res = json_decode(wp_remote_retrieve_body($response));



	$streams_response = wp_remote_get( 'https://www.strava.com/api/v3/activities/'.$id.'/streams?keys=latlng,altitude,velocity_smooth,heartrate,cadence,watts,temp,moving,grade_smooth&key_by_type=true', $args );

	$rbody_res = json_decode(wp_remote_retrieve_body($streams_response));







	





	

	$old_coord = [];

	$new_coord = [];

	if(isset($body_res->map->polyline) && !empty($body_res->map->polyline)) {

		$points = polyline_decode($body_res->map->polyline);

		$multi_coord = array_chunk($rbody_res->latlng->data, 2);

			

		$count = 0;

		$clat_long = [];

		$imp_long = [];

		foreach($rbody_res->latlng->data as $i => $coord) {



				$old_coord[$i]['lat'] = $coord[0];

				$old_coord[$i]['lon'] = $coord[1];

				$old_coord[$i]['ele'] = $rbody_res->altitude->data[$i];

				// $clat_long[$i] = [$coord[0],$coord[1]];

				// if($i <= 10) {

				// $old_coord[$i]['ele'] = $rbody_res->altitude->data;

				// } else {

				// 	$old_coord[$i]['ele'] = 11;

				// }

				

				// if($i <= 50) {

				// 	$old_coord[$i]['ele'] = $rbody_res->altitude->data;

				// }



				





				$old_coord[$i]['time'] = '1970-01-01T00:00:00+00:00';

				$new_coord[$i]['0'] = $coord[1];

				$new_coord[$i]['1'] = $coord[0];

		$count++;

		} 

	}







	// $last_index = end($lat_long_data);

	// foreach($clat_long as $key => $lat_long_data) {

	// 	$str = '|';

	// 	if($key <= 30) {

	// 		$data[] = implode(',', $lat_long_data);

	// 	}

		

		



	// }

	// $implode_data = implode('|', $data);

	// $aargs = [

	// 	'headers' => [

	// 		'Content-Type' => 'application/json' 

	// 	]

	// ];

	// $response = wp_remote_get( 'https://api.open-elevation.com/api/v1/lookup?locations='.$implode_data, $aargs );

	// $body_res = json_decode(wp_remote_retrieve_body($response));

	// foreach($body_res as $res) {

	// 	foreach()

	// 	echo '<pre>';

	// 	die(print_r($res));

	// }

	



	// $body_res->results[0]->elevation;

	

	



	







	







	$activities = [];

	$activities['activity']['id'] = @$body_res->id;

	$activities['activity']['name'] = @$body_res->name;

	$activities['activity']['time'] = '2021-09-18T11:40:24+01:00';

	$activities['activity']['data']['distance'] = @$body_res->distance;

	$activities['activity']['file'] = json_decode($files,true);

	$activities['activity']['file']['data']['meta_data']['name'] = @$body_res->name;

	$activities['activity']['file']['data']['meta_data']['time'] = '2021-09-27T12:08:18+00:00';

	$activities['activity']['file']['data']['trk'][]['trkseg'][]['points'] = $old_coord;

	$activities['activity']['file']['geojson']['type'] = 'FeatureCollection';

	$activities['activity']['file']['geojson']['features'][0]['type'] = 'Feature';

	$activities['activity']['file']['geojson']['features'][0]['geometry']['type'] = 'LineString';

	$activities['activity']['file']['geojson']['features'][0]['geometry']['coordinates'] = $new_coord;

	$activities['activity']['file']['geojson']['features'][0]['properties']['id'] = @$body_res->id;

	$activities['activity']['file']['geojson']['properties']['name'] = @$body_res->name;

	$activities['activity']['file']['geojson']['properties']['time'] = '2021-09-27T12:08:18+00:00';

	$activities['activity']['data']['distance'] = @$body_res->distance;

	$activities['activity']['data']['time']['moving'] = 3446;

	$activities['activity']['data']['time']['total'] = 5295;

	$activities['activity']['data']['elevation']['min'] = @$body_res->elev_low;

	$activities['activity']['data']['elevation']['max'] = @$body_res->elev_high;

	$activities['activity']['data']['elevation']['gain'] = @$body_res->total_elevation_gain;

	$activities['activity']['data']['pace'] = 1026.97;

	$activities['activity']['data']['speed'] = 0.97;

	echo json_encode(['status' => 200, 'data' => $activities]);

	wp_die();

}

add_action( 'wp_ajax_wpe_strava_activity', 'wpe_strava_activity' );

add_action("wp_ajax_nopriv_wpe_strava_activity", "wpe_strava_activity");



function wpe_lookup($data1,$data2) {

	

	$aargs = [

		'headers' => [

			'Content-Type' => 'application/json' 

		]

	];

	$response = wp_remote_get( 'https://api.open-elevation.com/api/v1/lookup?locations='.$data1.','.$data2, $aargs );

	$body_res = json_decode(wp_remote_retrieve_body($response));



	return $body_res->results[0]->elevation;

	

}

function wpe_design() {


	global $wpdb;

	$wie_gpx = $wpdb->prefix.'wie_gpx';

	$config = stripslashes($_POST['config']);

	$product_strip = stripslashes($_POST['product_data']);

	$product_variation = json_decode($product_strip);

	

	

	$guid = $_POST['guid'];

	$design = []; 

	$msg = '';

	$dec_config = json_decode($config);

	$hex = wgenerate_num(4).'-'.wgenerate_num(2).'-'.wgenerate_num(2).'-'.wgenerate_num(2).'-'.wgenerate_num(6);

	$design['design'] = ['config' => $dec_config];

	

	$design['design']['wp'] = null;

	$design['design']['status'] = 'draft';

	$gpx_data = $wpdb->get_row("SELECT * FROM $wie_gpx WHERE config_id = '$guid'");



	if(empty($gpx_data)) {

		if(!empty($guid) && isset($guid)) {

			$hex = $guid;

		}

		$design['design']['id'] = '';

		$design['design']['guid'] = $hex;

		$design['design']['time_insert'] = date('Y-m-d h:i:s', time());

		$wpdb->insert(

		    $wie_gpx,

		    array(

		        'gpx_file_data' => json_encode($design),

		        'config_id' => $hex,

		        'product_variation_id' => $product_variation->id,

		    )

		);

		$msg = 'Design Inserted';



	} else {

		$design['design']['id'] = $gpx_data->id;

		$design['design']['time_update'] = date('Y-m-d h:i:s', time());

        $wpdb->update(

            $wpdb->prefix .'wie_gpx', 

            array( 

                    'gpx_file_data' => json_encode($design),

                    'product_variation_id' => $product_variation->id,

                    'updated_at' => date('Y-m-d h:i:s', time()),

            ), 

            array(

                    "config_id" => $guid,

            ) 

        );

        $msg = 'Design Updated';

    }

	echo json_encode(['status' => 200, 'data' => $design, 'message' => $msg]);

	wp_die();

	

}

add_action( 'wp_ajax_wpe_design', 'wpe_design' );

add_action("wp_ajax_nopriv_wpe_design", "wpe_design");





function wpe_design_show() {

	global $wpdb;

	$wie_gpx = $wpdb->prefix.'wie_gpx';

	$guid = $_POST['guid'];

	$gpx_data = $wpdb->get_row("SELECT * FROM $wie_gpx WHERE config_id = '$guid' ");

	$dec_config = json_decode($gpx_data->gpx_file_data);

	$design = $dec_config;

	echo json_encode(['status' => 200, 'data' => $design]);

	wp_die();

}

add_action( 'wp_ajax_wpe_design_show', 'wpe_design_show' );

add_action("wp_ajax_nopriv_wpe_design_show", "wpe_design_show");







function wpe_parse_gpx() {



		$gpx = new phpGPX();

		

		$file = $gpx->load($_FILES['file']['tmp_name']);

		

		

		if(!empty($file->tracks)) {

			foreach ($file->tracks as $track) {



					$tracks = $track->stats->toArray();

					$distance  = $tracks['distance'];



					$avg_speed  = $tracks['avgSpeed'];

					$avg_pace  = $tracks['avgPace'];

					$min_eles  = $tracks['minAltitude'];

					$max_eles  = $tracks['maxAltitude'];

					$cumulative_ele_gain  = $tracks['cumulativeElevationGain'];

					$cumulative_ele_loss  = $tracks['cumulativeElevationLoss'];

					$started_at  = $tracks['startedAt'];

					$finished_at  = $tracks['finishedAt'];

					$duration  = $tracks['duration'];

					

			}



		



			

			$old_coord = [];

			$new_coord = [];

			$gpx = simplexml_load_file($_FILES['file']['tmp_name']);

			$count = 0;

			$last_lat = false;

			$last_lon = false;

			$total_distance = 0;

			$msg = '';



			foreach($gpx->trk->trkseg->{'trkpt'} as $trkpt ) {



				$trkpt_lat = xml_2_string($trkpt->attributes()->lat);

				$trkpt_lon = xml_2_string($trkpt->attributes()->lon);

					$old_coord[$count]['lat'] = floatval(xml_2_string($trkpt->attributes()->lat));

					$old_coord[$count]['lon'] = floatval(xml_2_string($trkpt->attributes()->lon));

					$old_coord[$count]['ele'] = round(xml_2_string($trkpt->ele), 2);

					$old_coord[$count]['time'] = date('c', strtotime($trkpt->time));

					$new_coord[$count]['0'] = floatval(xml_2_string($trkpt->attributes()->lon));

					$new_coord[$count]['1'] = floatval(xml_2_string($trkpt->attributes()->lat));

					$count++;

					if($last_lat){

							$total_distance += distance($trkpt_lat, $trkpt_lon, $last_lat, $last_lon, 'k');

					}

					$last_lat = $trkpt_lat;

					$last_lon = $trkpt_lon;

			}







		

			$activities = [];

			$activities['activity']['id'] = wgenerate_num(4).'-'.wgenerate_num(2).'-'.wgenerate_num(2).'-'.wgenerate_num(2).'-'.wgenerate_num(6);

			$activities['activity']['name'] = xml_2_string(@$gpx->trk->name);

			$activities['activity']['time'] = date('c', strtotime($gpx->metadata->time));

			$activities['activity']['data']['distance'] = @$total_distance;

			if(!empty($gpx->metadata) && isset($gpx->metadata)) {

				$activities['activity']['file']['data']['meta_data']['links'][0]['href'] = xml_2_string(@$gpx->metadata->link->attributes()->href);

				$activities['activity']['file']['data']['meta_data']['links'][0]['text'] =  xml_2_string(@$gpx->metadata->link->text);

				$activities['activity']['file']['data']['meta_data']['time'] =  date('c', strtotime(@$gpx->metadata->time));

			} 

			$activities['activity']['file']['data']['trk'][0]['name'] = xml_2_string(@$gpx->trk->name);



			$activities['activity']['file']['data']['trk'][0]['trkseg'][]['points'] = $old_coord;

			$activities['activity']['file']['data']['version'] = xml_2_string($gpx->attributes()->version);

			$activities['activity']['file']['data']['creator'] = xml_2_string($gpx->attributes()->creator);

			$activities['activity']['file']['geojson']['type'] = 'FeatureCollection';

			$activities['activity']['file']['geojson']['features'][0]['type'] = 'Feature';

			$activities['activity']['file']['geojson']['features'][0]['geometry']['type'] = 'LineString';

			$activities['activity']['file']['geojson']['features'][0]['geometry']['coordinates'] = $new_coord;

			$activities['activity']['file']['geojson']['features'][0]['properties']['id'] = '568a01ac-4025-48e8-b30b-cbfcf7db5ffd';

			$activities['activity']['file']['geojson']['features'][0]['properties']['name'] = xml_2_string(@$gpx->trk->name);

			if(!empty($gpx->metadata) && isset($gpx->metadata)) {

				$activities['activity']['file']['geojson']['properties']['links'][0]['href'] = xml_2_string($gpx->metadata->link->attributes()->href);

				$activities['activity']['file']['geojson']['properties']['links'][0]['text'] = xml_2_string($gpx->metadata->link->text);

				$activities['activity']['file']['geojson']['properties']['time'] = date('c', strtotime($gpx->metadata->time));

			}



			$ddistance = round($distance,2);

			if($avg_speed == 0) {

				$avg_speed = 1;

			}



			$activities['activity']['data']['distance'] = $ddistance;

			$activities['activity']['data']['time']['moving'] = $avg_speed;



			$activities['activity']['data']['time']['total'] = $duration;

			$activities['activity']['data']['elevation']['min'] = $min_eles;

			$activities['activity']['data']['elevation']['max'] = $max_eles;

			$activities['activity']['data']['elevation']['gain'] = $cumulative_ele_gain;

			$activities['activity']['data']['pace'] = $avg_pace ;

			$activities['activity']['data']['speed'] = gpx_speed($ddistance, $avg_speed);

			$status = 200;

			$msg = 'Success';

		

		} else {

			$status = 400;

			$msg = 'Cannot parse GPX data.';

			$activities = [];

		}

		echo json_encode(['status' => $status, 'data' => $activities, 'msg' => $msg]);

		wp_die();





	 

 

}

add_action( 'wp_ajax_wpe_parse_gpx', 'wpe_parse_gpx' );

add_action("wp_ajax_nopriv_wpe_parse_gpx", "wpe_parse_gpx");







add_action('wp_ajax_configurator_cart', 'configurator_cart');

add_action('wp_ajax_nopriv_configurator_cart', 'configurator_cart');

function configurator_cart() {

    global $woocommerce, $wpdb;

    $product_data = stripslashes($_POST['product_data']);

    $product_val = json_decode($product_data,true);
	// echo '<pre>';
	// print_r($product_val);
	// exit();
	// echo get_product_variation_id('20x30cm', 'blue', 'plexiglas');

    $qty = 1;

    $woocommerce->cart->add_to_cart($product_val['id'], $qty);

	echo json_encode(['redirect' => get_site_url().'/cart']);

	wp_die();



}





add_action('wp_ajax_get_gpx_file', 'get_gpx_file');

add_action('wp_ajax_nopriv_get_gpx_file', 'get_gpx_file');

function get_gpx_file() {

    global $wpdb;

    $wie_gpx = $wpdb->prefix.'wie_gpx';

    $guid = $_POST['guid'];

    $variate_pro = $_POST['variate_pro'];

    $gpx_data = $wpdb->get_row("SELECT * FROM $wie_gpx WHERE config_id = '$guid' AND product_variation_id = $variate_pro");



   

	echo $gpx_data->gpx_file_data;

	wp_die();



}







function get_product_variation_id( $size, $color, $material, $poster = 0 ) {
    global $wpdb;
	$where_poster = '';
	if($poster != 0) {
		$where_poster = " AND pm2.meta_key = 'attribute_pa_kleur-houten-lijst'
        AND pm2.meta_value LIKE '$poster'";
	}
	if($poster != 0) {
		$join_poster = " JOIN {$wpdb->prefix}postmeta as pm4 ON p.ID = pm4.post_id";
	}
    return $wpdb->get_var( "
        SELECT p.ID
        FROM {$wpdb->prefix}posts as p
        JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id
        JOIN {$wpdb->prefix}postmeta as pm2 ON p.ID = pm2.post_id
		JOIN {$wpdb->prefix}postmeta as pm3 ON p.ID = pm3.post_id 
		$join_poster 
        WHERE pm.meta_key = 'attribute_pa_color'
        AND pm.meta_value LIKE '$color'
        AND pm2.meta_key = 'attribute_pa_size'
        AND pm2.meta_value LIKE '$size' 
		$where_poster 
		AND pm3.meta_key = 'attribute_pa_material'
        AND pm3.meta_value LIKE '$material'
        AND p.post_parent = '5634' ");
}