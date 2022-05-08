<?php
	function gen_uuid() {
	    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	        mt_rand( 0, 0xffff ),
	        mt_rand( 0, 0x0fff ) | 0x4000,
	        mt_rand( 0, 0x3fff ) | 0x8000,
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	    );
	}

	function get_api_key() {
		$secret = fopen("secret.txt", "r");
		$api_key = fgets($secret);
		fclose($secret);
		return $api_key;
	}

	include __DIR__ . '/crypto.php';
	include __DIR__ . '/wifi.php';
	include __DIR__ . '/walk.php';
	include __DIR__ . '/4square.php';
	include __DIR__ . '/airbnb.php';
	include __DIR__ . '/db.php';

	//TODO: move to secrets
	$secret = "l6mid0gOJCAxMyUzBwxjaJ4HOLE=";
	$action = $_GET["action"];
	switch ($action) {
		case 'get_suggestions':
			$search_string = str_replace('%20','+',$_GET["string"]);
			$uuid = $_GET["uuid"];
			$apiKey = get_api_key();

			$ch = curl_init();

			$url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input='.$search_string.'&key='.$apiKey.'&sessiontoken='.$uuid;
			
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			$data = curl_exec($ch); 
			curl_close($ch); 
			echo '{ "predictions" : '.$data.'}';
		break;
		case 'get_details':
			//get_details
			/*if(existAddress($_GET["addr"])) {
				echo '{"image" : "'.$url.'"}';
				exit(0);
			}*/
			$place_id = $_GET["place_id"];
			$apiKey = get_api_key();
			$ch = curl_init();
			$url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id='.$place_id.'&key='.$apiKey;
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			$data = curl_exec($ch); 
			curl_close($ch); 
			$json = json_decode($data);
			$lat = floatval($json->result->geometry->location->lat);
			$lng = floatval($json->result->geometry->location->lng);
			$imgUrl = array();
			$diff = 0.0010000;
			$originallat = floatval($json->result->geometry->location->lat);
			$oringinallng = floatval($json->result->geometry->location->lng);
			
			//get_addr_info
			$addr = str_replace('%20','+',$_GET["addr"]);
			$airbnbPhotos = getAirbnbPhotos($addr);
			$nextPage = searchResultPage($addr);
			$wifi = array();
			if($nextPage !== "") {
				$wifi = searchWifiResults($nextPage);
			}
			$walk_score = getWalkScore($addr);
			
			//get_image_from_street_view
			$apiKeyStreetView = get_api_key();
			$urlStreetView = signUrl("https://maps.googleapis.com/maps/api/streetview?size=600x400&location=".$addr.'&key='.$apiKeyStreetView, $secret);
			//$airbnbPhotos = array_unshift($airbnbPhotos, $urlStreetView);

			//saveAddress($address, $walkScore, $bike, $transit, $internet, $images)

			echo '{ "api" : "'.$apiKey.'", "images" : '.json_encode($airbnbPhotos).', "result" : '.$data.', "latitude" : '.$originallat.', "longitude" : '.$oringinallng.',  "providers" : '.json_encode($wifi).', "walk" : "'.$walk_score.'"}';
		break;
		case 'get_new_session_token':
			echo '{ "uuid" : "'.gen_uuid().'"}';
		break;
		case 'get_4square_info':
			$lat = $_GET["latitude"];
			$lon = $_GET["longitude"];
			$radius =  $_GET["radius"];
			$section =  $_GET["section"];
			$grocery = get4squareResults($lat, $lon, $radius, $section);
			echo '{ "grocery" : '.json_encode($grocery).' }';
		break;
		default:
			echo '{ "error" : "no method found" }';
		break;
	}
?>
